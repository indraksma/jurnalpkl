<?php

namespace App\Http\Livewire;

use App\Models\Siswa as ModelsSiswa;
use App\Models\Kelas as ModelsKelas;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

use Illuminate\Support\Facades\Storage;

use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;

class Siswa extends Component
{
    use LivewireAlert, WithFileUploads;

    public $nama, $nis, $jk, $kelas_id, $siswa_id;
    public $template_excel;
    public $openModal = false;
    public $iteration = 0;
    protected $listeners = ['edit', 'delete'];

    public function render()
    {
        $kelas = ModelsKelas::all();
        return view('livewire.siswa', [
            'iteration' => $this->iteration,
            'kelas' => $kelas,
        ])->extends('layouts.app');
    }

    public function resetInputFields()
    {
        $this->reset(['nama', 'kelas_id', 'nis', 'jk', 'siswa_id']);
        $this->iteration++;
        $this->resetErrorBag();
    }

    public function create()
    {
        $this->openModal = true;
    }

    public function store()
    {
        $messages = [
            '*.required'                => 'This column is required',
            '*.numeric'                 => 'This column is required to be filled in with number',
            '*.string'                  => 'This column is required to be filled in with letters',
        ];

        $this->validate([
            'nama'      => ['required'],
            'nis'      => ['required'],
            'jk'      => ['required'],
            'kelas_id'      => ['required']
        ], $messages);

        ModelsSiswa::updateOrCreate(['id' => $this->siswa_id], [
            'nama'      => $this->nama,
            'nis'      => $this->nis,
            'jk'      => $this->jk,
            'kelas_id'      => $this->kelas_id,
        ]);

        $this->emit('refreshSiswaTable');
        $this->alert('success', $this->siswa_id ? 'Data berhasil diubah!' : 'Data berhasil ditambahkan!');
        $this->closeModal();
    }

    public function closeModal()
    {
        $this->resetInputFields();
        $this->openModal = false;
    }

    public function edit($id)
    {
        $siswa = ModelsSiswa::findOrFail($id);

        $this->siswa_id = $id;
        $this->nama = $siswa->nama;
        $this->nis = $siswa->nis;
        $this->jk = $siswa->jk;
        $this->kelas_id = $siswa->kelas_id;

        $this->openModal = true;
    }

    public function import()
    {
        // dd($this->template_excel);
        $file_path = $this->template_excel->store('files', 'public');
        //dd($file_path);
        Excel::import(new SiswaImport, storage_path('/app/public/' . $file_path));
        Storage::disk('public')->delete($file_path);

        $this->resetInputFields();
        $this->emit('refreshSiswaTable');
        $this->alert('success', 'Data berhasil diimport!');
    }

    public function delete($id)
    {
        $sql = ModelsSiswa::where('id', $id)->firstOrFail();

        $sql->find($id)->delete();

        $this->alert('warning', 'Data berhasil dihapus!');
        $this->emit('refreshSiswaTable');
    }
}
