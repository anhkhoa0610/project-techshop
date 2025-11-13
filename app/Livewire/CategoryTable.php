<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\View\View;
final class CategoryTable extends PowerGridComponent
{
    public string $tableName = 'categoryTable';
    public string $sortField = 'category_id';

    // Override primary key method
    public function primaryKey(): string
    {
        return 'category_id';
    }

    public function setUp(): array
    {

        return [
            PowerGrid::header()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50])
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Category::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('category_id')
            ->add('category_name')
            ->add('description')
            ->add('cover_image')
            ->add('created_at');
    }

    public function columns(): array
    {
        return [
            Column::make('Category id', 'category_id'),
            Column::make('Category name', 'category_name')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),

            Column::make('Cover image', 'cover_image')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
        ];
    }

      public function actionsFromView($row): View
    {
        return view('components.category-actions', ['category' => $row]);
    }

    public function noDataLabel(): string|View
    {
        return view('products.no-data');
    }

}
