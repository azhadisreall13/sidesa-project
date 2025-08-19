<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function accountRequestView()
    {
        $user = User::where('status', 'submitted')->paginate(5);
        $residents = Resident::where('user_id', null)->get();

        return view ('pages.account-request.index', [
            'users' => $user,
            'residents' => $residents
        ]);
    }

    public function account_approval(Request $request, $userId)
    {
        $request->validate([
            'for' => ['required', Rule::in(['approve', 'rejected', 'activate', 'deactivate'])],
            'resident_id' => ['nullable', 'exists:residents,id']
        ]);

        $for = $request->input('for');
        $user = User::findOrFail($userId);
        $user->status = $for == 'approve' || $for == 'activate' ? 'approved' : 'rejected';
        $user->save();

        $residentId = $request->input('resident_id');

        if ($request->has('resident_id') && isset($residentId))  {
            Resident::where('id', $residentId)
            ->update([
                'user_id' => $user->id
            ]);
        }

        if ($for == 'activate') {
            return back()->with('success', 'Berhasil mengaktifkan akun ini');
        } else if ($for == 'deactivate') {
            return back()->with('success', 'Berhasil menonaktifkan akun ini');
        }
        
        return back()->with('success', $for == 'approve' ? 'Berhasil menyetujui akun ini' : 'Berhasil menolak akun ini');
    }

    public function accountListView() 
    {
        $users = User::where('role_id', 2)->where('status', '!=', 'submitted')->paginate(5);

        return view ('pages.account-list.index', [
            'users' => $users
        ]);
    }

    public function profileView()
    { 
        return view ('pages.profile.index');
    }
    public function profileUpdate(Request $request, $userId)
    { 
        $request->validate([
            'name' => 'required|min:3'
        ]);

        $user = User::findOrFail($userId);
        $user->name = $request->input('name');
        $user->save();

        return back()->with('success', 'Berhasil mengubah data profil');
    }

    public function PasswordView()
    { 
        return view ('pages.change-password.index');
    }

    public function changePassword(Request $request, $userId)
    {
        $request->validate([
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8'
        ]);

        $user = User::findOrFail($userId);

        $currentPassword = Hash::check($request->input('old_password'), $user->password);
        if ($currentPassword) {
            $user->password = $request->input('new_password');
            $user->save();

            return back()->with('success', 'Berhasil mengubah password');
        }

        return back()->with('error', 'Gagal mengubah password, password lama tidak valid');
    }

    public function notificationAlert(Request $request, $id)
    {
        $notification = DB::table('notifications')->where('id', $id);
        $notification->update([
            'read_at' => DB::raw('CURRENT_TIMESTAMP')
        ]);

        $dataArray = json_decode($notification->firstOrFail()->data, true);

        if ($dataArray['complaint_id']) {
            return redirect('/complaint');
        }

        return back();
    }
}
