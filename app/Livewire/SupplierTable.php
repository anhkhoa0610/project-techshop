<?php

namespace App\Livewire;

use App\Models\Supplier;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\View\View;

final class SupplierTable extends PowerGridComponent
{
    public string $tableName = 'supplierTable';
    public string $sortField = 'supplier_id';
    public string $primaryKey = 'supplier_id';

    public function getIdAttribute()
    {
        return $this->supplier_id;
    }
    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->includeViewOnTop('components.add-supplier-button')->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50])
                ->showRecordCount(),
            PowerGrid::detail()
                ->view('components.supplier_details')
                ->showCollapseIcon(),
        ];
    }

    public function datasource(): Builder
    {
        return Supplier::query()
            ->withCount(['products'])
            ->withSum('orderDetails', 'quantity');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('supplier_id')
            ->add('name')
            ->add('email')
            ->add('phone')
            ->add('address')
            ->add('description')
            ->add(
                'logo_html',
                fn($supplier) =>
                '<img src="' . asset($supplier->logo ? 'uploads/' . $supplier->logo : 'uploads/place-holder.png') . '" class="h-12 w-12 object-cover rounded">'
            )
            ->add('created_at')
            ->add('created_at_formatted', fn(Supplier $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }
    public function columns(): array
    {
        return [
            Column::make('Supplier id', 'supplier_id'),
            Column::make('Logo', 'logo_html'),

            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->sortable()
                ->searchable(),

            Column::make('Phone', 'phone')
                ->sortable()
                ->searchable(),

            Column::make('Address', 'address')
                ->sortable()
                ->searchable(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::datePicker('created_at', 'created_at'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.supplier-actions', ['supplier' => $row]);
    }
}
