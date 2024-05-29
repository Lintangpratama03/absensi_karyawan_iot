<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Payroll;
use App\Models\Attendance;
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
            $attendanceDates = Attendance::where('tag', $post->tag)
                ->whereMonth('date', Carbon::createFromFormat('Y-m', $month)->month)
                ->where('status', 'Masuk')
                ->count();

            $lateCount = Attendance::where('tag', $post->tag)
                ->whereMonth('date', Carbon::createFromFormat('Y-m', $month)->month)
                ->where('status', 'Telat')
                ->count();
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
            $bonus = ($holidayCount <= 2) ? 100000 : 0;
            $totalSalary = $attendanceDates * $salary;
            $cut = $lateCount * Setting::first()->fine;
            $totalTransport = $attendanceDates * Setting::first()->fuel;
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
