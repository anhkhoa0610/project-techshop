<?php

namespace App\Livewire;

use App\Models\Spec;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\View\View;

final class SpecTable extends PowerGridComponent
{
    public string $tableName = 'specTable';
    public string $sortField = 'spec_id';
    public string $primaryKey = 'spec_id';

    public function getIdAttribute()
    {
        return $this->spec_id;
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->includeViewOnTop('components.add-spec-button')->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50])
                ->showRecordCount(),
            PowerGrid::detail()
                ->view('components.spec_details')
                ->showCollapseIcon(),
        ];
    }

    // 1. SỬA DATASOURCE: Lấy đúng cột 'products.product_name'
    public function datasource(): Builder
    {
        return Spec::query()
            ->join('products', 'specs.product_id', '=', 'products.product_id')
            ->select([
                'specs.*',
                // Vì tên cột bên products là 'product_name', ta lấy nó luôn
                'products.product_name as product_name_display'
            ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    // 2. SỬA FIELDS: Khớp với tên alias vừa đặt ở trên
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('spec_id')
            ->add('product_id')
            ->add('product_name_display') // Khớp với 'as product_name_display'
            ->add('name')
            ->add('value')
            ->add('created_at')
            ->add('created_at_formatted', fn(Spec $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    // 3. SỬA COLUMNS: Thêm tham số thứ 3 để tìm kiếm đúng bảng
    public function columns(): array
    {
        return [
            Column::make('Spec id', 'spec_id'),
            Column::make('Product id', 'product_id')
                ->sortable()
                ->searchable(),

            Column::make('Product Name', 'product_name_display', 'products.product_name')
                ->searchable(),

            Column::make('Name', 'name')
                ->searchable(),

            Column::make('Value', 'value')
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable()
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('product_id', 'specs.product_id')
                ->operators(['contains']),
            Filter::inputText('product_name_display', 'products.product_name')
                ->operators(['contains']),
            Filter::datepicker('created_at'),
            Filter::select('name', 'specs.name')
                ->dataSource(Spec::select('name')->distinct()->get()) 
                ->optionValue('name')
                ->optionLabel('name'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.spec-actions', ['spec' => $row]);
    }
}