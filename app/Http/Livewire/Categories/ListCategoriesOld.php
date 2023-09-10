<?php

namespace App\Http\Livewire\Categories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

final class ListCategoriesOld extends PowerGridComponent
{
    use ActionButton;
    public User $user;
    public Category $category;

    protected function getListeners(): array
    {
        return array_merge(
            parent::getListeners(),
            [
                'categories::index::created' => '$refresh',
                'categories::index::deleted' => '$refresh',
                'categories::index::updated' => '$refresh',
            ]
        );
    }
    public function boot()
    {
        $this->user = Auth::user();
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */
    public function datasource(): ?Collection
    {

        return $this->user->company->categories;
    }
    public function header(): array
    {
        return [
            Button::add('view')
                ->caption('Cadastrar Categoria')
                ->class('inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150')
                ->openModal('categories.create', []),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */
    public function setUp(): array
    {
        return [

            Header::make()->withoutLoading(),
            Header::make()->showSearchInput(),
            Footer::make()->showPerPage()->showRecordCount(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('status',fn($entry) => $entry->status == true ? 'ATIVA' : 'INATIVA')
            ->addColumn('created_at_formatted', function ($entry) {
                return Carbon::parse($entry->created_at)->format('d/m/Y');
            });
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
            Column::make('ID', 'id')->searchable()->sortable(),
            Column::make('Name', 'name')->searchable()->sortable(),
            Column::make('Status', 'status')->sortable(),
            Column::make('Criado em', 'created_at_formatted'),
        ];
    }
    public function actions(): array
    {
        return [

            Button::add('button-trash')
            ->render(function (Category $category) {
                return Blade::render(<<<HTML
                <x-button-trash primary icon="pencil" onclick="Livewire.emit('openModal', 'categories.delete', {{ json_encode(['category' => $category->id]) }})" />
                HTML);
            }),
            Button::add('button-update')
            ->render(function (Category $category) {
                return Blade::render(<<<HTML
                <x-button-update primary icon="pencil" onclick="Livewire.emit('openModal', 'categories.update', {{ json_encode(['category' => $category->id]) }})" />
                HTML);
            }),

        ];
    }
}
