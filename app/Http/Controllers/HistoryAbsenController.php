<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Salary;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Setting;

class HistoryAbsenController extends Controller
{
    public function index(Request $request)
    {

        $filterDate = $request->input('filterDate');
        $filterMonth = $request->input('filterMonth');

        $attendancesQuery = Attendance::query();

        if ($filterDate) {
            $filterDate = Carbon::parse($filterDate)->format('Y-m-d');
            $attendancesQuery->whereDate('date', $filterDate);
        }
        if ($filterMonth) {
            $filterMonth = Carbon::parse($filterMonth)->format('Y-m');
            $attendancesQuery->whereMonth('date', Carbon::parse($filterMonth)->month);
        }

        $attendances = $attendancesQuery->join('users', 'attendances.tag', '=', 'users.tag')
            ->get();

        // dd($attendances);
        $dailyRecords = $attendances->groupBy('date')->map(function ($dateGroup) {
            $in = $dateGroup->where('information', 'In')->first();
            $out = $dateGroup->where('information', 'Out')->first();
            // dd($in);
            $status = 'Bekerja';
            $penalty = 0;

            $late = $dateGroup->where('status', 'Telat')->first();
            $nominal_cut = Salary::where('name', 'telat')->first();
            if ($nominal_cut == null) {
                $nominal_cut = 0;
            } else {
                $nominal_cut = Salary::where('name', 'telat')->first()->nominal;
            }
            // cek nominal transport
            $nominal_transport = Salary::where('name', 'transport')->first();
            if ($nominal_transport == null) {
                $nominal_transport = 0;
            } else {
                $nominal_transport = Salary::where('name', 'transport')->first()->nominal;
            }

            if ($late) {
                $penalty = $nominal_cut;
            }

            return [
                'date' => $dateGroup->first()->date,
                'name' => $dateGroup->first()->name,
                'time_in' => $in ? $in->time : '-',
                'status_in' => $in ? $in->status : '-',
                'time_out' => $out ? $out->time : '-',
                'status_out' => $out ? $out->status : '-',
                'penalty' => $penalty,
                'work_status' => $status
            ];
        });
        // dd($dailyRecords);
        return view('dashboard.history-absen.index', compact('dailyRecords'));
    }
}
