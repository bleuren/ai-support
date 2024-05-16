<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\SupportService;
use Filament\Actions\Exports\ExportColumn;
use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SupportService::class, function ($app) {
            return new SupportService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Action::configureUsing(function (Action $action): void {
            $action->translateLabel();
        });
        Column::configureUsing(function (Column $column): void {
            $column->translateLabel();
        });
        Filter::configureUsing(function (Filter $filter): void {
            $filter->translateLabel();
        });
        Field::configureUsing(function (Field $field): void {
            $field->translateLabel();
        });
        Entry::configureUsing(function (Entry $entry): void {
            $entry->translateLabel();
        });
        ExportColumn::configureUsing(function (ExportColumn $exportColumn): void {
            $exportColumn->label(__($exportColumn->getLabel()));
        });
    }
}
