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
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;


final class CategoryTable extends PowerGridComponent
{
    public string $tableName = 'categoryTable';
    public string $sortField = 'category_id';

    use WithExport;

    // Override primary key method
    public function primaryKey(): string
    {
        return 'category_id';
    }

    public function setUp(): array
    {

        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->includeViewOnTop('components.add-category-button')->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50])
                ->showRecordCount(),
            PowerGrid::exportable(fileName: 'categories-export')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
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
                ->hidden(),

            Column::make('Created at', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('category_id')
                ->operators(['contains']),
            Filter::inputText('category_name')
                ->operators(['contains']),
            Filter::inputText('description')
                ->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.category-actions', ['category' => $row]);
    }

    public function noDataLabel(): string|View
    {
        return view('components.categories_nodata');
    }

}
