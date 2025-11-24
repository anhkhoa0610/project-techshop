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
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
final class OrderTable extends PowerGridComponent
{
    use WithExport;
    public string $tableName = 'orderTable';
    public string $sortField = 'order_id';

    // Override primary key method
    public string $primaryKey = 'order_id';
    public function getIdAttribute()
    {
        return $this->order_id;
    }

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->includeViewOnTop('components.add-order-button')
                ->showSearchInput(),
            PowerGrid::exportable(fileName: 'order-export')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::footer()
                ->showPerPage(5, [5, 10, 25, 50])
                ->showRecordCount(),
            PowerGrid::detail()
                ->view('components.order_details')
                ->showCollapseIcon(),
        ];
    }

    public function datasource()
    {
        return Order::query()
            ->leftJoin('vouchers', 'orders.voucher_id', '=', 'vouchers.voucher_id')
            ->leftJoin('users', 'orders.user_id', '=', 'users.user_id')
            ->select(
                'orders.*',              // tất cả cột từ orders
            );
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('order_id')
            // Lấy full_name từ user
            ->add('full_name', fn($order) => $order->user?->full_name ?? '---')

            ->add(
                'voucher_code',
                fn($order) => $order->voucher?->code ?? '---'
            )

            ->add('status', function ($order) {
                $color = match ($order->status) {
                    'pending' => 'yellow',
                    'completed' => 'green',
                    'cancelled' => 'red',
                    default => 'gray',
                };

                return '<span class="px-2 py-1 rounded text-black" style="background-color: ' . $color . '; width: 95px; display: inline-block; text-align: center;">'
                    . ucfirst($order->status) . '</span>';
            })
            ->add('payment_element', function ($order) {
                $color = match ($order->payment_method) {
                    'cash' => 'yellow',
                    'card' => 'olive',
                    'transfer' => 'orange',
                    'momo' => 'pink',
                    'vnpay' => 'aqua',
                    default => 'gray',
                };

                return '<span class="px-2 py-1 rounded text-black" style="background-color: ' . $color . '; width: 95px; display: inline-block; text-align: center;">'
                    . ucfirst($order->payment_method) . '</span>';
            })
            ->add('status_value', fn($order) => $order->status)
            ->add('total_price')
            ->add('created_at')
            ->add(
                'formatted_price',
                fn($order) =>
                number_format($order->total_price, 0, ',', '.') . ' đ'
            );
    }

    public function columns(): array
    {
        return [
            Column::make('Order ID', 'order_id')
                ->searchable()
                ->sortable(),

            Column::make('Customer', 'full_name'),

            Column::make('Voucher code', 'voucher_code')
                ->sortable(),

            Column::make('Status', 'status')
                ->searchable()
                ->sortable()->visibleInExport(false),

            Column::make('Status', 'status_value')
                ->sortable()->hidden()->visibleInExport(true),

            Column::make('shipping address', 'shipping_address')
                ->searchable()
                ->sortable(),

            Column::make('Payment method', 'payment_element')
                ->sortable(),

            Column::make('Total', 'formatted_price')
                ->sortable(),

            Column::make('Created At', 'created_at')
                ->sortable(),

            Column::action('Action')->visibleInExport(false),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('order_id', 'order_id')
                ->operators(['contains']),

            Filter::inputText('full_name', 'users.full_name')
                ->operators(['contains']),

            Filter::inputText('voucher_code', 'vouchers.code')
                ->operators(['contains']),

            Filter::select('status', 'orders.status')
                ->dataSource(Order::select('status')->distinct()->get())
                ->optionLabel('status')
                ->optionValue('status'),

            Filter::select('payment_element', 'orders.payment_method')
                ->dataSource(Order::select('payment_method')->distinct()->get())
                ->optionLabel('payment_method')
                ->optionValue('payment_method'),

            Filter::number('formatted_price','orders.total_price'),

            Filter::inputText('created_at', 'orders.created_at')
                ->operators(['contains']),

            Filter::inputText('shipping_address', 'shipping_address')
                ->operators(['contains']),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.order-actions', ['order' => $row]);
    }

    public function noDataLabel(): string|View
    {
        return view('components.orders_nodata');
    }
}
