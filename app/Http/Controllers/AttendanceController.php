<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'tag' => 'required',
            'information' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $setting = Setting::first();
        $requestTime = Carbon::createFromFormat('H:i:s', $request->time)->format('H:i:s');
        $inStart = Carbon::createFromFormat('H:i:s', $setting->in_start)->format('H:i:s');
        $inEnd = Carbon::createFromFormat('H:i:s', $setting->in_end)->format('H:i:s');
        $endEnd = Carbon::createFromFormat('H:i:s', $setting->end_end)->format('H:i:s');

        if ($requestTime >= $endEnd) {
            return response()->json(['message' => 'Telat Absen'], 202);
        }

        if ($requestTime < $inStart) {
            return response()->json(['message' => 'Waktu Masuk Belum Dimulai'], 205);
        }

        if ($requestTime >= $inStart && $requestTime <= $inEnd) {
            $status = 'Masuk';
            $statusCode = 200;
        } else {
            $status = 'Telat';
            $statusCode = 201;
        }

        $existingAttendance = Attendance::where('tag', $request->tag)
            ->where('information', $request->information)
            ->where('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return response()->json(['message' => 'Data sudah ada untuk tanggal ini'], 409);
        }

        $attendance = Attendance::create([
            'tag' => $request->tag,
            'information' => $request->information,
            'date' => $request->date,
            'time' => $request->time,
            'status' => $status,
        ]);

        return response()->json(['message' => 'Data berhasil disimpan'], $statusCode);
    }



    public function storeout(Request $request)
    {
        $request->validate([
            'tag' => 'required',
            'information' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $existingAttendance = Attendance::where('tag', $request->tag)
            ->where('information', $request->information)
            ->where('date', $request->date)
            ->first();

        if ($existingAttendance) {
            return response()->json(['message' => 'Data sudah ada untuk tanggal ini'], 409);
        }

        $setting = Setting::first();
        $requestTime = Carbon::createFromFormat('H:i:s', $request->time)->format('H:i:s');
        $outStart = Carbon::createFromFormat('H:i:s', $setting->out_start)->format('H:i:s');
        $izin = Carbon::createFromFormat('H:i:s', $setting->izin)->format('H:i:s');

        if ($requestTime >= $izin && $requestTime <= $outStart) {
            $status = 'Keluar';
            $attendance = Attendance::create([
                'tag' => $request->tag,
                'information' => $request->information,
                'date' => $request->date,
                'time' => $request->time,
                'status' => $status,
                'izin' => '1'
            ]);

            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        }
        if ($requestTime >= $outStart) {
            $status = 'Keluar';
            $attendance = Attendance::create([
                'tag' => $request->tag,
                'information' => $request->information,
                'date' => $request->date,
                'time' => $request->time,
                'status' => $status,
            ]);

            return response()->json(['message' => 'Data berhasil disimpan'], 200);
        } else if ($requestTime <= $outStart) {
            return response()->json(['message' => 'Belum Waktu Pulang'], 400);
        }
    }
}
