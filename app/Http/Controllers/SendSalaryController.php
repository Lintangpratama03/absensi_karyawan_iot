<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\Salary;
use App\Models\Setting;
use Carbon\CarbonPeriod;
use Dompdf\Dompdf;

class SendSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrolls = Payroll::all();
        return view('dashboard.payroll.send_salary.index', compact('payrolls'));
    }

    public function checkExisting(Request $request)
    {
        $month = $request->input('month');
        //dd($month);
        $exists = Payroll::where('month', $month)->exists();
        // dd($exists);
        return response()->json([
            'exists' => $exists,
            'month' => Carbon::createFromFormat('Y-m', $month)->format('F Y')
        ]);
    }
    public function storeAllPayrolls(Request $request)
    {
        $validatedData = $request->validate([
            'month' => 'required',
        ]);

        $month = $request->input('month');
        // dd($month);
        $posts = Post::all();

        foreach ($posts as $post) {
            // Hitung total hari kehadiran
            $attendanceDates = DB::table('attendances')
                ->where('tag', $post->tag)
                ->whereMonth('date', Carbon::createFromFormat('Y-m', $month)->month)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('attendances as in_attendance')
                        ->whereRaw('in_attendance.date = attendances.date')
                        ->where('in_attendance.status', 'Masuk');
                })
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('attendances as out_attendance')
                        ->whereRaw('out_attendance.date = attendances.date')
                        ->where('out_attendance.information', 'Out');
                })
                ->distinct('date')
                ->count('date');
            $lateCount = DB::table('attendances')
                ->where('tag', $post->tag)
                ->whereMonth('date', Carbon::createFromFormat('Y-m', $month)->month)
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('attendances as in_attendance')
                        ->whereRaw('in_attendance.date = attendances.date')
                        ->where('in_attendance.status', 'Telat');
                })
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('attendances as out_attendance')
                        ->whereRaw('out_attendance.date = attendances.date')
                        ->where('out_attendance.information', 'Out');
                })
                ->distinct('date')
                ->count('date');
            // dd($attendanceDates);
            // $lateCount = Attendance::where('tag', $post->tag)
            //     ->whereMonth('date', Carbon::createFromFormat('Y-m', $month)->month)
            //     ->where('status', 'Telat')
            //     ->where('information', 'Out')
            //     ->count();
            // Hitung total hari dalam bulan ini
            $totalDaysInMonth = Carbon::createFromFormat('Y-m', $month)->endOfMonth()->day;

            // Hitung jumlah hari libur
            $holidayCount = $totalDaysInMonth - $attendanceDates - $lateCount;

            $salary = $post->salary;
            if ($attendanceDates > 2) {
                $holidaySalary = $post->holiday_salary;
            } else {
                $holidaySalary = 0;
            }

            // cek nominal telat
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
            // dd($nominal_cut);
            $bonus = ($holidayCount <= 2) ? 100000 : 0;
            $totalSalary = $attendanceDates * $salary;
            $cut = $lateCount * $nominal_cut;
            $totalTransport = $attendanceDates * $nominal_transport;
            $amount = $totalSalary + $holidaySalary + $bonus + $totalTransport - $cut;

            Payroll::create([
                'month' => $month,
                'name' => $post->name,
                'tag' => $post->tag,
                'count' => $attendanceDates,
                'holiday' => $holidayCount,
                'late' => $lateCount,
                'salary' => $salary,
                'holiday_salary' => $holidaySalary,
                'bonus' => $bonus,
                'total_salary' => $totalSalary,
                'cut' => $cut,
                'total_transport' => $totalTransport,
                'amount' => $amount,
                'note' => 'Gaji bulan ' . Carbon::createFromFormat('Y-m', $month)->format('F Y'),
            ]);
        }
        return response()->json([
            'message' => 'Data gaji untuk seluruh karyawan pada bulan ini telah dikirim.'
        ]);
    }
}
