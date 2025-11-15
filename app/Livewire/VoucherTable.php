<?php

namespace App\Livewire;

use App\Models\Voucher;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\View\View;

final class VoucherTable extends PowerGridComponent
{
    public string $tableName = 'voucherTable';
    public string $sortField = 'voucher_id';
    public string $primaryKey = 'voucher_id';

    public function getIdAttribute()
    {
        return $this->voucher_id;
    }
    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->includeViewOnTop('components.add-voucher-button')->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50])
                ->showRecordCount(),
            PowerGrid::detail()
                ->view('components.voucher_details')
                ->showCollapseIcon(),
        ];
    }

    public function datasource(): Builder
    {
        return Voucher::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('voucher_id')
            ->add('code')
            ->add('discount_type')
            ->add('discount_value')
            ->add('start_date_formatted', fn(Voucher $model) => Carbon::parse($model->start_date)->format('d/m/Y'))
            ->add('end_date_formatted', fn(Voucher $model) => Carbon::parse($model->end_date)->format('d/m/Y'))
            ->add('status')
            ->add('status_formatted', function (Voucher $model) {
                if ($model->status === 'active') {
                    return '<span class="badge bg-success text-white">Active</span>';
                }
                // Bạn có thể dùng bg-secondary, bg-danger, hoặc bg-warning tùy ý
                return '<span class="badge bg-secondary text-white">Inactive</span>';
            })
            ->add('created_at_formatted', fn(Voucher $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'))
            ->add('updated_at_formatted', fn(Voucher $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('Voucher id', 'voucher_id'),
            Column::make('Code', 'code')
                ->sortable()
                ->searchable(),

            Column::make('Discount type', 'discount_type')
                ->sortable()
                ->searchable(),

            Column::make('Discount value', 'discount_value')
                ->sortable()
                ->searchable(),

            Column::make('Start date', 'start_date_formatted', 'start_date')
                ->sortable(),

            Column::make('End date', 'end_date_formatted', 'end_date')
                ->sortable(),

            Column::make('Status', 'status_formatted', 'status')
                ->sortable()
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
            Filter::select('status')
                ->dataSource([
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'inactive', 'label' => 'Inactive'],
                ])
                ->optionValue('value')  // Tên key chứa giá trị
                ->optionLabel('label'), // Tên key chứa nhãn hiển thị
            Filter::select('discount_type')
                ->dataSource([
                    ['value' => 'percent', 'label' => 'percent'],
                    ['value' => 'amount', 'label' => 'amount'],
                ])
                ->optionValue('value')
                ->optionLabel('label'),
            Filter::datepicker('start_date'),
            Filter::datepicker('end_date'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.voucher-actions', ['voucher' => $row]);
    }

}
