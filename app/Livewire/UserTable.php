<?php

namespace App\Livewire;

use App\Models\User;
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
use PowerComponents\LivewirePowerGrid\Components\SetUp\Exportable;
use Illuminate\View\View;


final class UserTable extends PowerGridComponent
{
    public string $tableName = 'userTable';
    public string $primaryKey = 'user_id';

    public string $sortField = 'user_id';



    use WithExport;

    public function getIdAttribute()
    {
        return $this->user_id;
    }

    public function setUp(): array
    {

        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->includeViewOnTop('components.add-user-button')->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::exportable(fileName: 'users-export')
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            PowerGrid::detail()
                ->view('components.user_details')
                ->showCollapseIcon(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()
        ->with(['profile']);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('user_id')
            ->add('full_name')
            ->add('password')
            ->add('email')
            ->add('phone')
            ->add('address')
            ->add('role')
            ->add('birth')
            ->add('is_tdc_student');

    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'user_id')
                ->searchable()
                ->sortable(),

            Column::make('Fullname', 'full_name')
                ->searchable()
                ->sortable(),

            Column::make('Email', 'email')
                ->searchable()
                ->sortable(),

            Column::make('Phone', 'phone')
                ->searchable(),

            Column::make('Address', 'address')
                ->searchable(),

            Column::make('Role', 'role')
                ->searchable()
                ->sortable(),

            Column::make('Birth', 'birth')
                ->searchable(),

            Column::make('Is TDC Student', 'is_tdc_student')
                ->searchable(),

            Column::action('Action')
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('user_id')
                ->operators(['contains']),

            Filter::inputText('full_name')
                ->operators(['contains']),

            Filter::inputText('email')
                ->operators(['contains']),

            Filter::inputText('phone')
                ->operators(['contains']),

            Filter::inputText('address')
                ->operators(['contains']),

            Filter::inputText('birth')
                ->operators(['contains']),

            Filter::select('is_tdc_student', 'is_tdc_student')
                ->dataSource(User::select('is_tdc_student')->distinct()->get())
                ->optionLabel('is_tdc_student')
                ->optionValue('is_tdc_student'),

            Filter::select('role', 'role')
                ->dataSource(User::select('role')->distinct()->get())
                ->optionLabel('role')
                ->optionValue('role'),

        ];
    }

    public function actionsFromView($row): View
    {
        return view('components.user-actions', ['user' => $row]);
    }

    public function noDataLabel(): string|View
    {
        return view('components.user_nodata');
    }


}
