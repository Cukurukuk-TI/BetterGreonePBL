<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Product;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Total Pelanggan', User::count())
                ->description('Jumlah seluruh pelanggan terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Produk', Product::count())
                ->description('Jumlah semua varian produk')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

        ];
    }
}
