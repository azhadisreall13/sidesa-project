<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Resident;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getDashboardAdmin()
    {
        $user = Auth::user();

        //     dd([
        //     'user' => $user,
        //     'resident' => $user->resident,
        //     'resident_id' => optional($user->resident)->id,
        //     'complaints' => Complaint::where('resident_id', optional($user->resident)->id)->get()
        // ]);

        if ($user->role_id == 1) {
            $totalComplaints = Complaint::count();
            $complaintBaru = Complaint::where('status', 'new')->count();
            $complaintDiproses = Complaint::where('status', 'processing')->count();
            $complaintSelesai = Complaint::where('status', 'completed')->count();

            $resident = Resident::all();
            $resident->load(['complaints' => function($query) {
                    $query->orderBy('created_at', 'desc')->limit(5);
                },
                'incomingLetters' => function($query) {
                    $query->orderBy('created_at', 'desc')->limit(5);
                }
            ]);

            $latestRequestAccount = User::where('status', 'submitted')
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['email', 'created_at']);

            $totalResident = Resident::count();

            $maleCount = Resident::where('gender', 'Laki-laki')->count();

            $femaleCount = Resident::where('gender', 'Perempuan')->count();

            $totalAccount = User::where('role_id', 2)->where('status', 'approved')->count();
        }

        return view('pages.dashboard-admin', [
            'totalComplaints' => $totalComplaints,
            'complaintBaru' => $complaintBaru,
            'complaintDiproses' => $complaintDiproses,
            'complaintSelesai' => $complaintSelesai,
            'resident' => $resident,
            'latestRequestAccount' => $latestRequestAccount,
            'totalResident' => $totalResident,
            'maleCount' => $maleCount,
            'femaleCount' => $femaleCount,
            'totalAccount' => $totalAccount,
            // 'user' => $dataUser
        ]);
    }
    public function getDashboardUser()
    {
        $user = Auth::user();

        //     dd([
        //     'user' => $user,
        //     'resident' => $user->resident,
        //     'resident_id' => optional($user->resident)->id,
        //     'complaints' => Complaint::where('resident_id', optional($user->resident)->id)->get()
        // ]);
        $residentId = optional($user->resident)->id;

        if (!$residentId) {
            return view ('pages.dashboard-user', [
                'totalComplaints' => 0,
                'complaintBaru' => 0,
                'complaintDiproses' => 0,
                'complaintSelesai' => 0,
                'resident' => null
            ]);
        }

        $totalComplaints = Complaint::where('resident_id', $residentId)->count();
        $complaintBaru = Complaint::where('resident_id', $residentId)->where('status', 'new')->count();
        $complaintDiproses = Complaint::where('resident_id', $residentId)->where('status', 'processing')->count();
        $complaintSelesai = Complaint::where('resident_id', $residentId)->where('status', 'completed')->count();

        $resident = Resident::with(['complaints' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            }])->find($residentId);

            // $dataUser = Resident::with(['users' => function ($query) {
            //     $query->latest()->take(1);
            // }])->get();

        return view('pages.dashboard-user', [
            'totalComplaints' => $totalComplaints,
            'complaintBaru' => $complaintBaru,
            'complaintDiproses' => $complaintDiproses,
            'complaintSelesai' => $complaintSelesai,
            'resident' => $resident
            // 'user' => $dataUser
        ]);
    }
}