<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Complaint;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Notifications\NotificationStatusChanged;

class ComplaintController extends Controller
{
    public function complaintView(Request $request) 
    {
        $search = $request->input('search');
        $residentId = Auth::user()->resident->id ?? null;
        $complaints = Complaint::when(Auth::user()->role_id == 2, function ($query) use ($residentId) {
            $query->where('resident_id', $residentId);
        })
        ->when($search, function($query) use ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        })
        ->orderBy('created_at', 'desc')
        ->paginate(5)
        ->appends(['search'=>$search]);

        return view('pages.complaint.index', [
            'complaint' => $complaints
        ]);
    }

    public function create() 
    {
        $resident = Auth::user()->resident;

        if (!$resident) {
            return redirect('/complaint')->with('error', 'Akun anda belum terhubung ke data penduduk');
        }

        return view ('pages.complaint.create');
    }
    public function store(Request $request) 
    {
        $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:2000'],
            'images' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ]);

        $resident = Auth::user()->resident;

        if (!$resident) {
            return redirect('/complaint')->with('error', 'Akun anda belum terhubung ke data penduduk');
        }

        $complaint = new Complaint();
        $complaint->resident_id = $resident->id;
        $complaint->title = $request->input('title');
        $complaint->description = $request->input('description');

        if ($request->hasFile('images')) {
            $filePath = $request->file('images')->store('public/uploads');
            $complaint->images = $filePath;
        }

        $complaint->save();

       $admins = User::where('role_id', 1)->get();
        foreach ($admins as $admin){
            $admin->notify(new AdminNotification($complaint));
        }

        return redirect ('/complaint')->with('success', 'Berhasil membuat aduan');
    }

    public function edit($id) 
    {
        $resident = Auth::user()->resident;

        if (!$resident) {
            return redirect('/complaint')->with('error', 'Akun anda belum terhubung ke data penduduk');
        }

        $complaint = Complaint::findOrFail($id);
        if ($complaint->status != 'new') {
            return redirect('/complaint')->with('error', "Gagal mengubah aduan, status aduan anda saat ini adalah $complaint->status_label");
        }

        return view('pages.complaint.edit', [
            'complaint' => $complaint
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['required', 'min:3', 'max:2000'],
            'images' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048']
        ]);

        $resident = Auth::user()->resident;

        if (!$resident) {
            return redirect('/complaint')->with('error', 'Akun anda belum terhubung ke data penduduk');
        }

        $complaint = Complaint::findOrFail($id);
        $complaint->resident_id = $resident->id;
        $complaint->title = $request->input('title');
        $complaint->description = $request->input('description');

        if ($request->hasFile('images')) {
            if (isset($complaint->images))
                Storage::delete($complaint->images);
            $filePath = $request->file('images')->store('public/uploads');
            $complaint->images = $filePath;
        }

        $complaint->save();

        return redirect ('/complaint')->with('success', 'Berhasil mengubah aduan');
    }

    public function destroy($id)
    {
        $resident = Auth::user()->resident;

        if (!$resident) {
            return redirect('/complaint')->with('error', 'Akun anda belum terhubung ke data penduduk');
        }

        $complaint = Complaint::findOrFail($id);
        if ($complaint->status != 'new'){
            return redirect('/complaint')->with('error', "Gagal menghapus aduan, status aduan anda saat ini adalah $complaint->status_label");
        }

        $complaint->delete();

        return redirect('/complaint')->with('success', 'Berhasil menghapus aduan');
    }

    public function update_status(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', Rule::in(['new', 'processing', 'completed'])]
        ]);

        $resident = Auth::user()->resident;
        if (Auth::user()->role_id == 2 && !$resident) {
            return redirect('/complaint')->with('error', 'Akun anda belum terhubung ke data penduduk');
        }

        $complaint = Complaint::findOrFail($id);
        $oldStatus = $complaint->status_label;
        $complaint->status = $request->input('status');
        $complaint->save();

        $newStatus = $complaint->status_label;

        User::where('id', $complaint->resident->user_id)
        ->firstOrFail()
        ->notify(new NotificationStatusChanged($complaint, $oldStatus, $newStatus));

        return redirect ('/complaint')->with('success', 'Berhasil mengubah status');
    }
}
