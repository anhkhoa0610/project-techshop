<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\View\View;
use Illuminate\Support\Facades\Blade;
final class OrderTable extends PowerGridComponent
{
    public string $tableName = 'orderTable';
    public string $sortField = 'order_id';

    // Override primary key method
    public function primaryKey(): string
    {
        return 'order_id';
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->includeViewOnTop('components.add-order-button'),
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50])
                ->showRecordCount(),
        ];
    }

    public function datasource()
    {
        return Order::query()->with(['voucher', 'user']);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('order_id')

            // Lấy full_name từ user
            ->add('full_name', fn($order) => $order->user?->full_name ?? '---')

            // Lấy code từ quan hệ voucher
            ->add('voucher_code', fn($order) => $order->voucher?->code)

            // Hiển thị text thay thế
            ->add(
                'voucher_display',
                fn($order) =>
                $order->voucher?->code ?? 'Không áp dụng'
            )

            ->add('status', function ($order) {
                $color = match ($order->status) {
                    'pending' => 'yellow',
                    'completed' => 'green',
                    'canceled' => 'red',
                    default => 'gray',
                };

                return '<span class="px-2 py-1 rounded text-black" style="background-color: ' . $color . '; width: 95px; display: inline-block; text-align: center;">'
                    . ucfirst($order->status) . '</span>';
            })
            ->add('status_value', fn($order) => $order->status)
            ->add('total_price')
            ->add('created_at')

            ->add(
                'created_at_formatted',
                fn($order) =>
                Carbon::parse($order->created_at)->format('d/m/Y H:i:s')
            );
    }

    public function columns(): array
    {
        return [
            Column::make('Order ID', 'order_id')
                ->searchable()
                ->sortable(),

            Column::make('Customer', 'full_name')
                ->searchable()
                ->sortable(),

            Column::make('Voucher', 'voucher_display')
                ->searchable()
                ->sortable(),

            Column::make('Status', 'status')
                ->searchable()
                ->sortable(),
            Column::make('shipping address', 'shipping_address')
                ->searchable()
                ->sortable(),

            Column::make('Total', 'total_price')
                ->sortable(),

            Column::make('Created At', 'created_at_formatted', 'created_at')
                ->sortable(),

            Column::action('Action'),
        ];
    }

    public function filters(): array
    {
        return [

        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.order-actions', ['order' => $row]);
    }
}
