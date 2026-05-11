<?php

namespace App\Filament\Widgets;

use App\Models\Store;
use App\Models\District;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('إجمالي المحلات', Store::count())
                ->description('المحلات المسجلة في المبادرة')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),

            Stat::make('المناطق المغطاة', District::count())
                ->description('عدد المناطق في جبلة واللاذقية')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('info'),

            Stat::make('المحلات النشطة', Store::where('is_active', true)->count())
                ->description('تجار ملتزمون حالياً')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('warning'),
        ];
    }
}