<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Support\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Orders & Kitchen';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->relationship('order', 'order_number')
                    ->searchable()
                    ->nullable(),
                Forms\Components\Select::make('gateway')
                    ->options([
                        'paystack' => 'Paystack (NGN)',
                        'stripe' => 'Stripe (GBP/USD/CAD/EUR)',
                        'bank_transfer' => 'Bank Transfer',
                        'cash_on_delivery' => 'Cash / POS on Delivery',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('reference')
                    ->required()
                    ->default(fn () => 'PAY-'.strtoupper(uniqid())),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'successful' => 'Successful / Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\TextInput::make('amount_minor')
                    ->label('Amount in Minor Units')
                    ->required()
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
                Forms\Components\DateTimePicker::make('paid_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('order.order_number')
                    ->searchable()
                    ->label('Order #'),
                Tables\Columns\TextColumn::make('gateway')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('amount_minor')
                    ->label('Amount')
                    ->formatStateUsing(fn ($state, Payment $record) => Money::format($state, $record->currency))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'successful', 'paid' => 'success',
                        'pending' => 'warning',
                        'failed', 'refunded' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
