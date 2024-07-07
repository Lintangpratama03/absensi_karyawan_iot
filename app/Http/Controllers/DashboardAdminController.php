<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

        $dailyAttendance = Attendance::select(
            'date',
            DB::raw('COUNT(DISTINCT CASE WHEN information = "In" THEN tag END) as check_ins'),
            DB::raw('COUNT(DISTINCT CASE WHEN information = "Out" THEN tag END) as check_outs')
        )
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $months = Attendance::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'))
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        return view('dashboard.index', compact('checkInsToday', 'checkOutsToday', 'totalEmployees', 'workingToday', 'date', 'dailyAttendance', 'selectedMonth', 'months'));
    }
}
