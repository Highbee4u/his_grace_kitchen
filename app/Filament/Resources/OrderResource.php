<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Payment;
use App\Notifications\OrderPaidNotification;
use App\Notifications\OrderStatusUpdatedNotification;
use App\Support\Money;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Notification;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Orders & Kitchen';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->default(fn () => 'NK-'.strtoupper(uniqid()))
                            ->readOnly(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending_payment' => 'Pending Payment',
                                'paid' => 'Paid',
                                'confirmed' => 'Confirmed',
                                'preparing' => 'In Kitchen (Preparing)',
                                'ready_for_pickup' => 'Ready for Pickup',
                                'out_for_delivery' => 'Out for Delivery',
                                'delivered' => 'Delivered / Completed',
                                'cancelled' => 'Cancelled',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('pending_payment'),
                        Forms\Components\Select::make('fulfilment_type')
                            ->options([
                                'delivery' => 'Delivery',
                                'pickup' => 'Kitchen Pickup',
                            ])
                            ->required()
                            ->default('delivery'),
                        Forms\Components\DateTimePicker::make('scheduled_for')
                            ->label('Scheduled Date / Time'),
                        Forms\Components\Select::make('delivery_zone_id')
                            ->relationship('deliveryZone', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Customer Contact & Delivery')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->required(),
                        Forms\Components\TextInput::make('customer_email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('customer_phone')
                            ->tel()
                            ->required(),
                        Forms\Components\Textarea::make('delivery_address')
                            ->label('Delivery Address Details')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Special Kitchen / Delivery Notes')
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('Financials')
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
                        Forms\Components\TextInput::make('total_minor')
                            ->label('Total (Minor Units)')
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
                        Forms\Components\TextInput::make('promo_code')
                            ->nullable(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->searchable()
                    ->description(fn (Order $record): string => $record->customer_phone ?? ''),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'delivered', 'paid', 'confirmed' => 'success',
                        'preparing', 'ready_for_pickup', 'out_for_delivery' => 'warning',
                        'cancelled', 'refunded' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('fulfilment_type')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('total_minor')
                    ->label('Total')
                    ->formatStateUsing(fn ($state, Order $record) => Money::format($state, $record->currency))
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_for')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending_payment' => 'Pending Payment',
                        'paid' => 'Paid',
                        'confirmed' => 'Confirmed',
                        'preparing' => 'Preparing',
                        'ready_for_pickup' => 'Ready for Pickup',
                        'out_for_delivery' => 'Out for Delivery',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('fulfilment_type')
                    ->options([
                        'delivery' => 'Delivery',
                        'pickup' => 'Pickup',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_paid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record): bool => $record->status === 'pending_payment')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $prev = $record->status;
                        $record->update(['status' => 'paid']);
                        Payment::where('order_id', $record->id)->update([
                            'status' => 'successful',
                            'paid_at' => now(),
                        ]);
                        try {
                            if ($record->user) {
                                $record->user->notify(new OrderPaidNotification($record));
                            } else {
                                Notification::route('mail', $record->customer_email)
                                    ->notify(new OrderPaidNotification($record));
                            }
                        } catch (\Exception $e) {
                        }
                    }),

                Tables\Actions\Action::make('prepare')
                    ->label('Cook')
                    ->icon('heroicon-o-fire')
                    ->color('warning')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['paid', 'confirmed']))
                    ->action(function (Order $record) {
                        $prev = $record->status;
                        $record->update(['status' => 'preparing']);
                        try {
                            if ($record->user) {
                                $record->user->notify(new OrderStatusUpdatedNotification($record, $prev));
                            } else {
                                Notification::route('mail', $record->customer_email)
                                    ->notify(new OrderStatusUpdatedNotification($record, $prev));
                            }
                        } catch (\Exception $e) {
                        }
                    }),

                Tables\Actions\Action::make('dispatch')
                    ->label('Dispatch')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Order $record): bool => $record->status === 'preparing')
                    ->action(function (Order $record) {
                        $prev = $record->status;
                        $newStatus = $record->fulfilment_type === 'pickup' ? 'ready_for_pickup' : 'out_for_delivery';
                        $record->update(['status' => $newStatus]);
                        try {
                            if ($record->user) {
                                $record->user->notify(new OrderStatusUpdatedNotification($record, $prev));
                            } else {
                                Notification::route('mail', $record->customer_email)
                                    ->notify(new OrderStatusUpdatedNotification($record, $prev));
                            }
                        } catch (\Exception $e) {
                        }
                    }),

                Tables\Actions\Action::make('deliver')
                    ->label('Complete')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Order $record): bool => in_array($record->status, ['out_for_delivery', 'ready_for_pickup']))
                    ->action(function (Order $record) {
                        $prev = $record->status;
                        $record->update(['status' => 'delivered']);
                        try {
                            if ($record->user) {
                                $record->user->notify(new OrderStatusUpdatedNotification($record, $prev));
                            } else {
                                Notification::route('mail', $record->customer_email)
                                    ->notify(new OrderStatusUpdatedNotification($record, $prev));
                            }
                        } catch (\Exception $e) {
                        }
                    }),

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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
