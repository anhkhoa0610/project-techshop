<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;


final class ProductTable extends PowerGridComponent
{
    public string $tableName = 'productTable';
    public string $sortField = 'product_id';
    public string $primaryKey = 'product_id';

    use WithExport;

    public function getIdAttribute()
    {
        return $this->product_id;
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns() 
                ->includeViewOnTop('components.add-product-button')->showSearchInput(), 
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50]) 
                ->showRecordCount(),
            PowerGrid::exportable(fileName: 'products-export')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::detail()
                ->view('components.product_details')
                ->showCollapseIcon(),
        ];
    }

    public function datasource(): Builder
    {
        return Product::query()
            ->with(['category', 'supplier']);
    }

    public function relationSearch(): array
    {
        return [
            'category' => ['category_name'],
            'supplier' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('product_id')
            ->add('product_name')
            ->add('description')
            ->add('stock_quantity')
            ->add('price')
            ->add(
                'cover_image',
                fn($product) =>
                '<img src="' . asset('uploads/' . $product->cover_image) . '" class="h-12 w-12 object-cover rounded">'
            )
            ->add('cover_image_filename', fn($product) => $product->cover_image)
            ->add('volume_sold')
            ->add('category_name', fn($product) => $product->category->category_name ?? 'N/A')
            ->add('supplier_name', fn($product) => $product->supplier->name ?? 'N/A')
            ->add('warranty_period')
            ->add('release_date')
            ->add('embed_url_review')
        ;
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'product_id')
                ->sortable()
                ->searchable(),

            Column::make('Name', 'product_name')
                ->sortable()
                ->searchable(),

            Column::make('Image', 'cover_image')
                ->visibleInExport(false),

            Column::make('Price', 'price')
                ->sortable()
                ->searchable(),

            Column::make('Stock', 'stock_quantity')
                ->sortable()
                ->searchable(),

            Column::make('Category', 'category_name')
                ->searchable(),

            Column::make('Supplier', 'supplier_name')
                ->searchable(),

            Column::make('Sold', 'volume_sold')
                ->sortable()
                ->searchable(),

            Column::make('Description', 'description')
                ->hidden(),

            Column::make('Warranty', 'warranty_period')
                ->sortable()
                ->searchable(),

            Column::make('Release Date', 'release_date')
                ->sortable()
                ->searchable()
                ->hidden(),

            Column::make('Review URL', 'embed_url_review')
                ->sortable()
                ->searchable()
                ->hidden(),

            Column::action('Action')->visibleInExport(false)
        ];
    }
    public function filters(): array
    {
        return [
            Filter::inputText('product_id')
                ->operators(['contains']),

            Filter::inputText('product_name')
                ->operators(['contains']),

            Filter::inputText('description')
                ->operators(['contains']),

            Filter::select('category_name', 'category_id')
                ->dataSource(\App\Models\Category::all())
                ->optionLabel('category_name')
                ->optionValue('category_id'),

            Filter::select('supplier_name', 'supplier_id')
                ->dataSource(\App\Models\Supplier::all())
                ->optionLabel('name')
                ->optionValue('supplier_id'),

            Filter::number('price'),

            Filter::number('stock_quantity'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.product-actions', ['product' => $row]);
    }

    public function noDataLabel(): string|View
    {
        return view('components.product_nodata');
    }
}