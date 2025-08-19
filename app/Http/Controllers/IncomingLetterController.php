<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use App\Models\IncomingLetter;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\AdminLetterNotification;

class IncomingLetterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $residentId = Auth::user()->resident->id ?? null;

            $letters = IncomingLetter::when(Auth::user()->role_id == 2, function($query) use ($residentId) {
                $query->where('resident_id', $residentId);
            })
            ->when($search, function($query) use ($search) {
                $query->where('type', 'like', '%' . $search . '%');
            
        })
        ->orderBy('created_at', 'desc')
        ->paginate(5)
        ->appends(['search'=>$search]);;

        return view ('pages.incoming-letter.index', [
            'letter' => $letters,
            'search' => $search
        ]);
    }

    public function create()
    {
        $types = [
            'Surat Domisili',
            'Surat Keterangan Usaha',
            'SKTM',
            'Surat Kelahiran',
            'Surat Kematian'
        ];

        return view ('pages.incoming-letter.create', [
            'types' => $types
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'description' => 'required|string'
        ]);

        $residentId = Auth::user()->resident->id ?? null;
        IncomingLetter::create([
            'resident_id' => $residentId,
            'type' => $request->type,
            'description' => $request->description,
            'status' => 'pending'
        ]);
        return redirect('/letter')->with('success', 'Surat berhasil diajukan');
    }

    public function edit($id)
    {
        // if (Auth::user()->role_id != 1) {

        // }
        $letter = IncomingLetter::with('resident')->findOrFail($id);

        $statusOption = [
            'pending' => 'Terkirim',
            'processing' => 'Sedang Diproses',
            'completed' => 'Selesai'
        ];

        return view('pages.incoming-letter.edit', [
            'letter' => $letter,
            'statusOption' => $statusOption
        ]);
    }

    public function update(Request $request, $id)
    {
        $letter = IncomingLetter::findOrFail($id);

        $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'completed'])],
            'file' => ['nullable', 'mimes:pdf', 'max:2048']
        ]);
        
        if ($request->hasFile('file')) {
            if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
                Storage::disk('public')->delete($letter->file_path);
            }
            $filePath = $request->file('file')->store('letters', 'public');
            $letter->file_path = $filePath;
        }

        $letter->status = $request->status;
        $letter->save();

        return redirect('/letter')->with('success', 'Surat berhasil diupdate');
    }

    public function download($id)
    {
        $letter = IncomingLetter::findOrFail($id);

        if ($letter->file_path && Storage::disk('public')->exists($letter->file_path)) {
            return Storage::disk('public')->download($letter->file_path);
        }

        return back()->with('error', 'File tidak ditemukan');
    }
}
