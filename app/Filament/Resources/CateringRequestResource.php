<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CateringRequestResource\Pages;
use App\Models\CateringRequest;
use App\Notifications\CateringQuoteSentNotification;
use App\Support\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;

class CateringRequestResource extends Resource
{
    protected static ?string $model = CateringRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Catering & Custom Requests';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Catering Event Details')
                    ->schema([
                        Forms\Components\TextInput::make('reference')
                            ->required()
                            ->default(fn () => 'CAT-'.strtoupper(uniqid()))
                            ->readOnly(),
                        Forms\Components\Select::make('catering_package_id')
                            ->label('Requested Package')
                            ->relationship('package', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        Forms\Components\TextInput::make('guest_count')
                            ->label('Guest Count')
                            ->numeric()
                            ->required(),
                        Forms\Components\DatePicker::make('event_date')
                            ->required(),
                        Forms\Components\TextInput::make('venue')
                            ->placeholder('e.g. Landmark Event Centre, VI')
                            ->required(),
                        Forms\Components\Textarea::make('dietary_notes')
                            ->label('Dietary Requirements / Menu Preferences')
                            ->columnSpanFull(),
                    ])->columns(2),

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

                Forms\Components\Section::make('Quotation & Status Workflow')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'submitted' => 'Submitted (New)',
                                'under_review' => 'Under Review',
                                'quote_sent' => 'Quote Sent to Client',
                                'accepted' => 'Quote Accepted',
                                'deposit_paid' => 'Deposit Paid',
                                'in_preparation' => 'In Preparation',
                                'completed' => 'Completed',
                                'declined' => 'Declined',
                            ])
                            ->required()
                            ->default('submitted'),
                        Forms\Components\TextInput::make('quoted_total_minor')
                            ->label('Quoted Total (Minor Units)')
                            ->numeric()
                            ->nullable(),
                        Forms\Components\TextInput::make('deposit_minor')
                            ->label('Deposit Required / Paid (Minor Units)')
                            ->numeric()
                            ->nullable(),
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
                            ->label('Admin Internal Notes / Custom Quotation Details')
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
                    ->description(fn (CateringRequest $record): string => $record->customer_email ?? ''),
                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('guest_count')
                    ->label('Guests')
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed', 'deposit_paid', 'accepted' => 'success',
                        'quote_sent', 'under_review', 'in_preparation' => 'warning',
                        'declined' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('quoted_total_minor')
                    ->label('Quoted Total')
                    ->formatStateUsing(fn ($state, CateringRequest $record) => $state ? Money::format($state, $record->currency) : 'Pending Quote')
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
                    ->visible(fn (CateringRequest $record): bool => in_array($record->status, ['submitted', 'under_review']))
                    ->form([
                        Forms\Components\TextInput::make('quoted_total_minor')
                            ->label('Total Event Quote (Minor Units / Kobo)')
                            ->numeric()
                            ->required()
                            ->default(fn (CateringRequest $record) => $record->quoted_total_minor ?? ($record->package ? $record->package->price_minor * $record->guest_count : 50000000)),
                        Forms\Components\TextInput::make('deposit_minor')
                            ->label('Required Deposit (Minor Units / Kobo)')
                            ->numeric()
                            ->required()
                            ->default(fn (CateringRequest $record) => (int) (($record->quoted_total_minor ?? 50000000) * 0.5)),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Quotation Notes for Client (Menu inclusions, staffing setup)')
                            ->default('Includes full buffet chafing setup, live suya station, and 4 uniformed servers.'),
                    ])
                    ->action(function (CateringRequest $record, array $data) {
                        $record->update([
                            'quoted_total_minor' => $data['quoted_total_minor'],
                            'deposit_minor' => $data['deposit_minor'],
                            'admin_notes' => $data['admin_notes'] ?? null,
                            'status' => 'quote_sent',
                        ]);

                        try {
                            if ($record->user) {
                                $record->user->notify(new CateringQuoteSentNotification($record));
                            } else {
                                Notification::route('mail', $record->customer_email)
                                    ->notify(new CateringQuoteSentNotification($record));
                            }
                        } catch (\Exception $e) {
                        }
                    }),

                Tables\Actions\Action::make('mark_deposit')
                    ->label('Deposit Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (CateringRequest $record): bool => in_array($record->status, ['quote_sent', 'accepted']))
                    ->requiresConfirmation()
                    ->action(fn (CateringRequest $record) => $record->update(['status' => 'deposit_paid'])),

                Tables\Actions\Action::make('start_prep')
                    ->label('Prepare')
                    ->icon('heroicon-o-fire')
                    ->color('info')
                    ->visible(fn (CateringRequest $record): bool => $record->status === 'deposit_paid')
                    ->action(fn (CateringRequest $record) => $record->update(['status' => 'in_preparation'])),

                Tables\Actions\Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (CateringRequest $record): bool => $record->status === 'in_preparation')
                    ->action(fn (CateringRequest $record) => $record->update(['status' => 'completed'])),

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
            'index' => Pages\ListCateringRequests::route('/'),
            'create' => Pages\CreateCateringRequest::route('/create'),
            'edit' => Pages\EditCateringRequest::route('/{record}/edit'),
        ];
    }
}
