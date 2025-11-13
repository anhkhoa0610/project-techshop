<?php

namespace App\Livewire;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\View\View;

final class ProductTable extends PowerGridComponent
{
    public string $tableName = 'productTable';
    public string $sortField = 'product_id';

    // Override primary key method
    public function primaryKey(): string
    {
        return 'product_id';
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns() // ⭐ Thêm toggle columns
                ->includeViewOnTop('components.add-product-button'), // ⭐ Thêm button
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50]) // ⭐ Mặc định 5, options: 5, 10, 25, 50
                ->showRecordCount(),
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
            'supplier' => ['supplier_name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('product_id')
            ->add('product_name')
            ->add('description', function ($product) {
                return str(e($product->description))->words(8); //Gets the first 8 words
            })
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
                ->sortable(),

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

            // Ẩn các columns ít quan trọng, có thể toggle
            Column::make('Sold', 'volume_sold')
                ->sortable(),

            Column::make('Description', 'description')
                ->searchable(),

            Column::make('Warranty', 'warranty_period')
                ->sortable(),

            Column::make('Release Date', 'release_date'),

            Column::make('Review URL', 'embed_url_review')
                ->sortable()
                ->searchable(),

            Column::action('Action')->visibleInExport(false)
        ];
    }
    public function filters(): array
    {
        return [
            Filter::inputText('product_name')
                ->operators(['contains']),

            Filter::inputText('description')
                ->operators(['contains']),

            // Filter theo Category
            Filter::select('category_name', 'category_id')
                ->dataSource(\App\Models\Category::all())
                ->optionLabel('category_name')
                ->optionValue('category_id'),

            // Filter theo Supplier
            Filter::select('supplier_name', 'supplier_id')
                ->dataSource(\App\Models\Supplier::all())
                ->optionLabel('name')
                ->optionValue('supplier_id'),

            // Filter theo Price range
            Filter::number('price'),

            // Filter theo Stock
            Filter::number('stock_quantity'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.product-actions', ['product' => $row]);
    }

    public function noDataLabel(): string|View
    {
        return view('products.no-data');
    }
}