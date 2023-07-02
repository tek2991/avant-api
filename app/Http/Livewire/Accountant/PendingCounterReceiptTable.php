<?php

namespace App\Http\Livewire\Accountant;

use App\Models\CounterReceipt;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class PendingCounterReceiptTable extends PowerGridComponent
{
    use ActionButton;

    public string $sortField = 'id';
    public string $sortDirection = 'desc';

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\CounterReceipt>
     */
    public function datasource(): Builder
    {
        return CounterReceipt::query()
            ->where('completed', false)
            ->with(['student.user.userDetail', 'standard', 'createdBy', 'counterReceiptItems']);
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [
            'student.user.userDetail' => ['name'],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('standard_id')
            ->addColumn('standard', fn (CounterReceipt $model) => $model->standard->name)
            ->addColumn('student_id')
            ->addColumn('student', fn (CounterReceipt $model) => $model->student->user->userDetail->name)
            ->addColumn('remarks')

            ->addColumn('total_amount', fn (CounterReceipt $model) => "₹ " . $model->totalAmountInCents() / 100)

            /** Example of custom column using a closure **/
            ->addColumn('remarks_lower', function (CounterReceipt $model) {
                return strtolower(e($model->remarks));
            })

            // ->addColumn('payment_mode')
            // ->addColumn('cheque_number')
            // ->addColumn('cheque_date')
            // ->addColumn('bank_name')

            ->addColumn('completed')
            ->addColumn('completed_formatted', fn (CounterReceipt $model) => $model->completed ? 'Yes' : 'No')
            ->addColumn('created_by')
            ->addColumn('created_by_name', fn (CounterReceipt $model) => $model->createdBy->username)
            ->addColumn('created_at_formatted', fn (CounterReceipt $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i'))
            ->addColumn('updated_at_formatted', fn (CounterReceipt $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i'));
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('STANDARD', 'standard')
                ->sortable()
                ->searchable(),

            Column::make('STUDENT', 'student')
                ->sortable()
                ->searchable(),

            Column::make('TOTAL AMOUNT', 'total_amount')
                ->sortable()
                ->searchable(),

            Column::make('COMPLETED', 'completed_formatted', 'completed')
                ->sortable()
                ->searchable()
                ->makeBooleanFilter(),

            Column::make('CREATED BY', 'created_by_name', 'created_by')
                ->sortable()
                ->searchable(),

            Column::make('ISSUED AT', 'created_at_formatted', 'created_at')
                ->searchable()
                ->sortable()
                ->makeInputDatePicker(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid CounterReceipt Action Buttons.
     *
     * @return array<int, Button>
     */


    public function actions(): array
    {
        return [
            Button::make('edit', 'Edit')
                ->class('bg-indigo-500 cursor-pointer text-white px-3 py-1.5 m-1 rounded text-sm')
                ->route('accountant.counter-receipts.edit', ['counter_receipt' => 'id'])
                ->target(''),

            Button::make('show', 'Show')
                ->class('bg-green-500 cursor-pointer text-white px-3 py-1.5 m-1 rounded text-sm')
                ->route('accountant.counter-receipts.show', ['counter_receipt' => 'id'])
                ->target(''),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid CounterReceipt Action Rules.
     *
     * @return array<int, RuleActions>
     */


    public function actionRules(): array
    {
        return [

            //Hide button edit if completed
            Rule::button('edit')
                ->when(fn ($counter_receipt) => $counter_receipt->completed)
                ->hide(),

            //Hide button show if not completed
            Rule::button('show')
                ->when(fn ($counter_receipt) => !$counter_receipt->completed)
                ->hide(),
        ];
    }
}
