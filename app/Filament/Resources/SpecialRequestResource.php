<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialRequestResource\Pages;
use App\Models\SpecialRequest;
use App\Notifications\SpecialRequestQuoteSentNotification;
use App\Support\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;

class SpecialRequestResource extends Resource
{
    protected static ?string $model = SpecialRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Catering & Custom Requests';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Custom Off-Menu Request')
                    ->schema([
                        Forms\Components\TextInput::make('reference')
                            ->required()
                            ->default(fn () => 'REQ-'.strtoupper(uniqid()))
                            ->readOnly(),
                        Forms\Components\TextInput::make('quantity')
                            ->label('Servings / Portions')
                            ->required()
                            ->numeric()
                            ->default(1),
                        Forms\Components\DatePicker::make('needed_by')
                            ->label('Needed By Date'),
                        Forms\Components\TextInput::make('reference_image_url')
                            ->label('Customer Reference Photo Link / Media')
                            ->placeholder('https://...')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->label('Detailed Dish Description & Custom Ingredients')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('Customer Contact')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->required(),
                        Forms\Components\TextInput::make('customer_email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('customer_phone')
                            ->tel()
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Kitchen Quotation & Status')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'submitted' => 'Submitted (New)',
                                'under_review' => 'Under Review',
                                'quote_sent' => 'Quote Sent',
                                'accepted' => 'Quote Accepted',
                                'in_preparation' => 'In Preparation',
                                'completed' => 'Delivered / Completed',
                                'declined' => 'Declined',
                            ])
                            ->required()
                            ->default('submitted'),
                        Forms\Components\TextInput::make('budget_minor')
                            ->label('Customer Budget (Minor Units)')
                            ->numeric(),
                        Forms\Components\TextInput::make('quoted_total_minor')
                            ->label('Kitchen Quoted Price (Minor Units)')
                            ->numeric(),
                        Forms\Components\Select::make('currency')
                            ->options([
                                'NGN' => 'NGN (₦)',
                                'GBP' => 'GBP (£)',
                                'USD' => 'USD ($)',
                                'CAD' => 'CAD ($)',
                                'EUR' => 'EUR (€)',
                            ])
                            ->default('NGN')
                            ->required(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Kitchen Response & Sourcing Notes')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->description(fn (SpecialRequest $record): string => $record->customer_phone ?? ''),
                Tables\Columns\TextColumn::make('description')
                    ->limit(40)
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Portions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('needed_by')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed', 'accepted' => 'success',
                        'quote_sent', 'under_review', 'in_preparation' => 'warning',
                        'declined' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('quoted_total_minor')
                    ->label('Quoted')
                    ->formatStateUsing(fn ($state, SpecialRequest $record) => $state ? Money::format($state, $record->currency) : 'Pending Quote')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('send_quote')
                    ->label('Send Quote')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->visible(fn (SpecialRequest $record): bool => in_array($record->status, ['submitted', 'under_review']))
                    ->form([
                        Forms\Components\TextInput::make('quoted_total_minor')
                            ->label('Chef Quoted Price (Minor Units / Kobo)')
                            ->numeric()
                            ->required()
                            ->default(fn (SpecialRequest $record) => $record->budget_minor ?? 3500000),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Chef Notes on Ingredient Sourcing & Prep')
                            ->default('Fresh native ingredients confirmed from our partner market suppliers. Prepared fresh to order.'),
                    ])
                    ->action(function (SpecialRequest $record, array $data) {
                        $record->update([
                            'quoted_total_minor' => $data['quoted_total_minor'],
                            'admin_notes' => $data['admin_notes'] ?? null,
                            'status' => 'quote_sent',
                        ]);

                        try {
                            if ($record->user) {
                                $record->user->notify(new SpecialRequestQuoteSentNotification($record));
                            } else {
                                Notification::route('mail', $record->customer_email)
                                    ->notify(new SpecialRequestQuoteSentNotification($record));
                            }
                        } catch (\Exception $e) {
                        }
                    }),

                Tables\Actions\Action::make('start_cooking')
                    ->label('Cook')
                    ->icon('heroicon-o-fire')
                    ->color('info')
                    ->visible(fn (SpecialRequest $record): bool => in_array($record->status, ['quote_sent', 'accepted']))
                    ->action(fn (SpecialRequest $record) => $record->update(['status' => 'in_preparation'])),

                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (SpecialRequest $record): bool => $record->status === 'in_preparation')
                    ->action(fn (SpecialRequest $record) => $record->update(['status' => 'completed'])),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecialRequests::route('/'),
            'create' => Pages\CreateSpecialRequest::route('/create'),
            'edit' => Pages\EditSpecialRequest::route('/{record}/edit'),
        ];
    }
}
