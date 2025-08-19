<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ResidentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $residents = Resident::with('user')
        ->when($search, function($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('nik', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(5)
        ->appends(['search'=>$search]);

        return view ('pages.resident.index', [
            'resident' => $residents
        ]);
    }

    public function create() 
    {
        return view ('pages.resident.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => ['required', 'min:16', 'max:16'],
            'name' => ['required', 'max:100'],
            'gender' => ['required', Rule::in(values: ['Laki-laki', 'Perempuan'])],
            'birth_date' => ['required', 'string'],
            'birth_place' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'religion' => ['required', 'max:50'],
            'marital_status' => ['required', Rule::in(['Belum Menikah', 'Menikah', 'Cerai', 'Duda/Janda'])],
            'occupation' => ['required', 'max:100'],
            'phone' => ['required', 'max:15'],
            'status' => ['required', Rule::in(['Hidup', 'Pindah', 'Meninggal dunia'])]
        ]);

        Resident::create($validatedData);

        return redirect('/resident')->with('success', 'Berhasil menambahkan data');
    }

    public function edit($id)
    {
        $residents = Resident::findOrFail($id);

        return view ('pages.resident.edit', [
            'resident' => $residents
        ]);
    }

    public function update(Request $request, $id) 
    {
        $validatedData = $request->validate([
            'nik' => ['required', 'min:16', 'max:16'],
            'name' => ['required', 'max:100'],
            'gender' => ['required', Rule::in(values: ['Laki-laki', 'Perempuan'])],
            'birth_date' => ['required', 'string'],
            'birth_place' => ['required', 'max:100'],
            'address' => ['required', 'max:100'],
            'religion' => ['required', 'max:50'],
            'marital_status' => ['required', Rule::in(['Belum Menikah', 'Menikah', 'Cerai', 'Duda/Janda'])],
            'occupation' => ['required', 'max:100'],
            'phone' => ['required', 'max:15'],
            'status' => ['required', Rule::in(['Hidup', 'Pindah', 'Meninggal dunia'])]
        ]);

        Resident::findOrFail($id)->update($validatedData);

        return redirect('/resident')->with('success', 'Berhasil menambahkan data');
    }

    public function destroy($id)
    {
        $residents = Resident::findOrFail($id);
        $residents->delete();

        return redirect('/resident')->with('success', 'Berhasil menghapus');
    }
}
