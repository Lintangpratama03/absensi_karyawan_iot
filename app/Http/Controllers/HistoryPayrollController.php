<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Payroll;
use Illuminate\Support\Facades\Auth;
use Dompdf\Dompdf;

class HistoryPayrollController extends Controller
{

    public function showForm()
    {
        return view('dashboard.history.index');
    }
    public function getPayrollData(Request $request)
    {
        $query = Payroll::query(); // Mengubah dari $query = Payroll::; menjadi $query = Payroll::query();

        $date = $request->date;
        $month = $request->month;
        $year = $request->year;
        // dd($query);
        if ($date) {
            $query->where('month', 'like', "%$date%");
        }
        if ($month) {
            $query->where('month', 'like', "$year-$month%");
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
