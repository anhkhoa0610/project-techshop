<?php

namespace App\Livewire;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Illuminate\View\View;
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;


final class ReviewTable extends PowerGridComponent
{
    public string $tableName = 'reviewTable';

    public string $primaryKey = 'review_id';
    public string $sortField = 'review_id';

    use WithExport;

    public function getIdAttribute()
    {
        return $this->review_id;
    }

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            // PowerGrid::header()
            //     ->showSearchInput(),
            // PowerGrid::footer()
            //     ->showPerPage()
            //     ->showRecordCount(),

            PowerGrid::header()
                ->showToggleColumns()
                ->includeViewOnTop('components.add-review-button')->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),

            PowerGrid::exportable(fileName: 'reviews-export')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::detail()
                ->view('components.review_details')
                ->showCollapseIcon(),
        ];
    }

    public function datasource(): Builder
    {
        return Review::query()
            ->with(['product', 'user']);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('review_id')
            ->add('product_name', fn($review) => $review->product->product_name ?? 'N/A')
            ->add('full_name', fn($review) => $review->user->full_name ?? 'N/A')
            ->add('rating')
            ->add('comment')
            ->add('review_date');
    }

    public function columns(): array
    {
        return [
            Column::make('Review_id', 'review_id')
                ->searchable()
                ->sortable(),

            Column::make('Product_name', 'product_name'),

            Column::make('Full_name', 'full_name'),

            Column::make('Rating', 'rating')
                ->searchable()
                ->sortable(),
            Column::make('Comment', 'comment')
                ->searchable()
                ->sortable(),
            Column::make('Review_date', 'review_date')
                ->searchable()
                ->sortable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('review_id')
                ->operators(['contains']),

            Filter::inputText('rating')
                ->operators(['contains']),

            Filter::inputText('comment')
                ->operators(['contains']),

            Filter::inputText('review_date')
                ->operators(['contains']),

            Filter::select('product_name', 'product_id')
                ->dataSource(\App\Models\Product::all())
                ->optionLabel('product_name')
                ->optionValue('product_id'),

            Filter::select('full_name', 'user_id')
                ->dataSource(\App\Models\User::all())
                ->optionLabel('full_name')
                ->optionValue('user_id'),
        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.review-actions', ['review' => $row]);
    }

     public function noDataLabel(): string|View
    {
        return view('components.review_nodata');
    }
   
}
