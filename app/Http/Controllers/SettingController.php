<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('dashboard.setting.index', [
            'settings' => Setting::all()
        ]);
    }

    public function create()
    {
        return view('dashboard.setting.create');
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'in_start' => 'required',
            'in_end' => 'required',
            'end_end' => 'required',
            'out_start' => 'required',
            'izin' => 'required',
        ]);

        Setting::create($validatedData);
        return redirect('/dashboard/setting')->with('success', 'New time has been added!');
    }


    public function show(Setting $setting)
    {
        //
    }

    public function edit(Setting $setting)
    {
        return view('dashboard.setting.edit', [
            'setting' => $setting,
        ]);
    }


    public function update(Request $request, Setting $setting)
    {
        $rules = [
            'in_start' => 'required',
            'in_end' => 'required',
            'end_end' => 'required',
            'out_start' => 'required',
            'izin' => 'required',
        ];

        $validatedData = $request->validate($rules);
        Setting::where('id', $setting->id)
            ->update($validatedData);
        return redirect('/dashboard/setting')->with('success', 'Data has been updated!');
    }


    public function destroy(Setting $setting)
    {
        Setting::destroy($setting->id);
        return redirect('/dashboard/setting')->with('success', 'Data has been deleted!');
    }
}
