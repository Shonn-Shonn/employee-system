<?php

namespace App\Filament\Resources\EmployeeResource\Widgets;

use App\Filament\Resources\EmployeeResource\Pages\ListEmployees;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class EmployeerOverview extends BaseWidget
{
 
    public ?Model $record = null;

    protected function getStats(): array
    {
        return [
            //
        ];
    }
}
