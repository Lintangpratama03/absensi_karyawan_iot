<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class DashboardAdminController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $checkInsToday = Attendance::where('date', $date)
            ->where('information', 'In')
            ->count();

        $checkOutsToday = Attendance::where('date', $date)
            ->where('information', 'Out')
            ->count();

        $totalEmployees = User::where('role', 'karyawan')->count();

        $workingToday = Attendance::where('date', $date)
            ->select('tag')
            ->groupBy('tag')
            ->havingRaw('COUNT(DISTINCT information) = 2')
            ->count();

        return view('dashboard.index', compact('checkInsToday', 'checkOutsToday', 'totalEmployees', 'workingToday', 'date'));
    }
}
