<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

use App\Models\Siswa;

class SiswaTable extends DataTableComponent
{
    protected $model = Siswa::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setAdditionalSelects(['siswas.id']);
    }

    public function columns(): array
    {
        return [
            Column::make("Nama", "nama")
                ->sortable()
                ->searchable(),
            Column::make("NIS", "nis")
                ->sortable()
                ->searchable(),
            Column::make("JK", "jk")
                ->sortable(),
            Column::make("Kelas", "kelas.nama_kelas")
                ->sortable()
                ->searchable(),
            Column::make('Actions')
                ->label(function ($row, Column $column) {
                    return view('livewire.modal.edit-siswa', ['users' => $row]);
                }),
            // ->attributes(function ($row) {
            //     return [
            //         'class' => 'space-x-2',
            //     ];
            // })
            // ->buttons([
            //     LinkColumn::make('Edit')
            //         ->title(fn ($row) => 'Edit ' . $row->name)
            //         ->location(fn ($row) => route('siswa', $row))
            //         ->attributes(function ($row) {
            //             return [
            //                 'wire:click' => 'edit(' . $row->id . ')',
            //                 'class' => 'btn btn-sm btn-warning',
            //             ];
            //         }),
            //     LinkColumn::make('Delete')
            //         ->title(fn ($row) => 'Delete ' . $row->name)
            //         ->location(fn ($row) => route('siswa', $row))
            //         ->attributes(function ($row) {
            //             return [
            //                 'class' => 'btn btn-sm btn-danger',
            //             ];
            //         }),
            // ]),
        ];
    }
}
