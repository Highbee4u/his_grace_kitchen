<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Support\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Orders & Kitchen';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Overview')
                    ->schema([
                        Forms\Components\TextInput::make('number')
                            ->default(fn () => 'INV-2026-'.str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT))
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'paid' => 'Paid',
                                'overdue' => 'Overdue',
                                'void' => 'Void',
                            ])
                            ->default('draft')
                            ->required(),
                        Forms\Components\Select::make('order_id')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->nullable(),
                        Forms\Components\DatePicker::make('due_date')
                            ->default(now()->addDays(7)),
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->nullable(),
                    ])->columns(3),

                Forms\Components\Section::make('Amount & Totals')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal_minor')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('delivery_fee_minor')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('discount_minor')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('tax_minor')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Forms\Components\TextInput::make('total_minor')
                            ->numeric()
                            ->default(0)
                            ->required(),
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
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->label('Order #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'sent' => 'info',
                        'overdue' => 'danger',
                        'void' => 'gray',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('total_minor')
                    ->label('Total')
                    ->formatStateUsing(fn ($state, Invoice $record) => Money::format($state, $record->currency))
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Invoice $record): string => route('invoices.download', $record->number))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('preview_pdf')
                    ->label('Preview')
                    ->icon('heroicon-o-printer')
                    ->color('info')
                    ->url(fn (Invoice $record): string => route('invoices.stream', $record->number))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('warning')
                    ->visible(fn (Invoice $record): bool => $record->status !== 'paid')
                    ->action(fn (Invoice $record) => $record->update(['status' => 'paid', 'paid_at' => now()])),

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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
