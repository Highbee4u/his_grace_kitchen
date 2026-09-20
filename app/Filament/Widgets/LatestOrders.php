<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Support\Money;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->latest()->limit(8))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Order #')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Customer')
                    ->description(fn (Order $record) => $record->customer_phone),
                Tables\Columns\TextColumn::make('fulfilment_type')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'delivered', 'paid', 'confirmed' => 'success',
                        'preparing', 'ready_for_pickup', 'out_for_delivery' => 'warning',
                        'cancelled', 'refunded' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('total_minor')
                    ->label('Total')
                    ->formatStateUsing(fn ($state, Order $record) => Money::format($state, $record->currency)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Placed')
                    ->since(),
            ]);
    }
}
