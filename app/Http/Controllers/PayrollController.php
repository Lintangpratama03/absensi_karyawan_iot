<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;

class PayrollController extends Controller
{

    public function showForm()
    {
        $userTag = Auth::user()->tag;
        $payrolls = Payroll::where('tag', $userTag)->get();
        // dd($payrolls);
        return view('employee.payroll.index', compact('payrolls'));
    }
    public function getPayrollData(Request $request)
    {
        $userTag = Auth::user()->tag;
        $query = Payroll::where('tag', $userTag);

        $date = $request->date;
        $month = $request->month;
        $year = $request->year;
        // dd($month);
        if ($date) {
            $query->where('month', 'like', "%$date%");
        }
        if ($month) {
            $query->where('month', 'like', "%-$month");
        }
        if ($year) {
            $query->where('month', 'like', "$year-%");
        }

        return datatables()->eloquent($query)->toJson();
    }


    public function generatePDF($id)
    {
        $payroll = Payroll::where('id', $id)->first();

        if ($payroll) {
            $dompdf = new Dompdf();
            $logoPath = public_path('img/logo.jpeg');
            $html = view('pdf.dashboard', compact('payroll', 'logoPath'))->render();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            return $dompdf->stream("payroll-{$payroll->id}.pdf");
        } else {
            return redirect()->back()->with('error', 'Payroll not found');
        }
    }
}
