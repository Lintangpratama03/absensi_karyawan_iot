<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Payroll;
use App\Models\Attendance;
use App\Models\Setting;
use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('dashboard.payroll.index', [
            'posts' => $posts
        ]);
    }

    public function dashboard(Request $request)
    {
        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $userTag = Auth::user()->tag;
        // dd($userTag);
        $checkInStatus = Attendance::where('date', $date)
            ->where('information', 'In')
            ->where('tag', $userTag)
            ->pluck('status')
            ->first();

        // dd($checkInStatus);
        $checkOutsToday = Attendance::where('date', $date)
            ->where('information', 'Out')
            ->where('tag', $userTag)
            ->pluck('status')
            ->first();

        // $totalEmployees = User::where('role', 'karyawan')->count();

        $workingToday = Attendance::where('date', $date)
            ->where('tag', $userTag)
            ->select('tag')
            ->groupBy('tag')
            ->havingRaw('COUNT(DISTINCT information) = 2')
            ->count();

        $date = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();
        $currentMonth = $date->month;
        $currentYear = $date->year;

        $workingMonth = Attendance::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('tag', $userTag)
            ->select('date')
            ->groupBy('date')
            ->havingRaw('COUNT(DISTINCT information) = 2')
            ->count();
        // dd($workingMonth);
        $selectedMonth = $request->input('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();

        $dailyAttendance = Attendance::select(
            'date',
            DB::raw('COUNT(DISTINCT CASE WHEN information = "In" THEN tag END) as check_ins'),
            DB::raw('COUNT(DISTINCT CASE WHEN information = "Out" THEN tag END) as check_outs')
        )
            ->whereBetween('date', [$startDate, $endDate])
            ->where('tag', $userTag)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $months = Attendance::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as month'))
            ->distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        return view('employee.dashboard', compact('currentMonth', 'checkInStatus', 'checkOutsToday',  'workingToday', 'date', 'dailyAttendance', 'selectedMonth', 'months', 'workingMonth'));
    }

    public function getSalary($id)
    {
        $post = Post::findOrFail($id);
        // dd($post);
        return response()->json(['salary' => $post->salary]);
    }

    public function getAttendanceCount($tag, $monthYear)
    {

        $monthYearObj = Carbon::createFromFormat('Y-m', $monthYear);

        $month = $monthYearObj->month;
        $year = $monthYearObj->year;

        $attendanceCount = DB::table('attendances')
            ->where('tag', $tag)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'Masuk')
            ->count();

        return response()->json(['attendanceCount' => $attendanceCount]);
    }


    public function storePayroll(Request $request)
    {
        // dd($request->all());
        $validatedData = $request->validate([
            'month' => 'required',
            'name' => 'required',
            'tag' => 'required',
            'count' => 'required',
            'holiday' => 'required',
            'late' => 'required',
            'salary' => 'required',
            'holiday_salary' => 'required',
            'bonus' => 'required',
            'total_salary' => 'required',
            'cut' => 'required',
            'total_transport' => 'required',
            'amount' => 'required',
            'note' => 'required',
        ]);

        Payroll::create($validatedData);
        return redirect('/dashboard/payroll')->with('success', 'Data payroll has been added');
    }
    public function getHolidaySalary($postId)
    {
        $post = Post::findOrFail($postId);
        $holidaySalary = $post->holiday_salary;

        return response()->json(['holiday_salary' => $holidaySalary]);
    }
    public function getLateCount($tag, $monthYear)
    {
        $monthYearObj = Carbon::createFromFormat('Y-m', $monthYear);
        $month = $monthYearObj->month;
        $year = $monthYearObj->year;

        $lateCount = DB::table('attendances')
            ->where('tag', $tag)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->where('status', 'Telat')
            ->count();

        return response()->json(['lateCount' => $lateCount]);
    }

    public function getFineValue()
    {
        $fineValue = Setting::first()->fine;
        return response()->json(['fineValue' => $fineValue]);
    }
    public function getTransport()
    {
        $fuelValue = Setting::first()->fuel;
        return response()->json(['fuelValue' => $fuelValue]);
    }
    public function showPayrollForm(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'post' => 'required',
        ]);

        $month = $request->input('month');
        $postId = $request->input('post');

        $payrolls = Payroll::where('month', $month)
            ->whereHas('post', function ($query) use ($postId) {
                $query->where('id', $postId);
            })
            ->get();

        return view('dashboard.payroll.form', compact('payrolls'));
    }
    public function showPayroll(Request $request)
    {
        $posts = Post::all();
        return view('dashboard.payroll.show', compact('posts'));
    }

    public function generatePDF()
    {
        $payroll = Payroll::all();
        $dompdf = new Dompdf();
        $html = view('pdf.dashboard', compact('payroll'))->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A5', 'portrait');
        $dompdf->render();

        return $dompdf->stream("payrolls.pdf");
    }
}
