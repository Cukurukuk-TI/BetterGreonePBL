<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;

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

        ];
    }
}
