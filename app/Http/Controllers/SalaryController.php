<?php

namespace App\Http\Controllers;

use App\Models\Salary;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Salary::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="' . route('salaries.edit', $row->id) . '" class="edit btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>';
                    $btn .= ' <button class="btn btn-danger btn-sm delete-button" data-id="' . $row->id . '"><i class="fas fa-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.salary.index');
    }
    public function create()
    {
        return view('dashboard.salary.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'nominal' => 'required|integer'
        ]);

        $nominal = (int)str_replace(['Rp', '.', ','], '', $request->nominal);

        Salary::create([
            'name' => $request->name,
            'nominal' => $nominal
        ]);

        return redirect()->route('salaries.index')->with('success', 'Salary created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'nominal' => 'required|integer'
        ]);

        $nominal = (int)str_replace(['Rp', '.', ','], '', $request->nominal);

        $salary = Salary::findOrFail($id);
        $salary->update([
            'name' => $request->name,
            'nominal' => $nominal
        ]);

        return redirect()->route('salaries.index')->with('success', 'Salary updated successfully.');
    }



    public function show($id)
    {
        $salary = Salary::findOrFail($id);
        return view('dashboard.salary.show', compact('salary'));
    }

    public function edit($id)
    {
        $salary = Salary::findOrFail($id);
        return view('dashboard.salary.edit', compact('salary'));
    }

    public function destroy($id)
    {
        $salary = Salary::findOrFail($id);
        $salary->delete();

        return redirect()->route('salaries.index')->with('success', 'Salary deleted successfully.');
    }
}
