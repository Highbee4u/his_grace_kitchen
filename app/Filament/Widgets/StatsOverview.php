<?php

namespace App\Filament\Widgets;

use App\Models\CateringRequest;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Payment;
use App\Support\Money;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $totalRevenueMinor = Payment::whereIn('status', ['successful', 'paid'])->sum('amount_minor');
        $pendingCatering = CateringRequest::whereIn('status', ['submitted', 'under_review'])->count();
        $activeDishes = MenuItem::where('is_available', true)->count();

        return [
            Stat::make("Today's Orders", (string) $todayOrders)
                ->description('Orders placed today')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),

            Stat::make('Total Revenue', Money::format($totalRevenueMinor, 'NGN'))
                ->description('Verified payments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make('Pending Quotes', (string) $pendingCatering)
                ->description('Catering & custom requests awaiting review')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('warning'),

            Stat::make('Active Dishes', (string) $activeDishes)
                ->description('Available on public storefront')
                ->descriptionIcon('heroicon-m-cake')
                ->color('info'),
        ];
    }
}
