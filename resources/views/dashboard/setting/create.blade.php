@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Setting Waktu Absen</h1>
    </div>

    <div class="col-lg-2">
        <form method="post" action="/dashboard/setting" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="in_start" class="form-label">Awal Absen Masuk :</label>
                <input type="time" class="form-control @error('in_start') is-invalid @enderror" id="in_start"
                    name="in_start" required value="{{ old('in_start') }}">
                @error('in_start')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="in_end" class="form-label">Akhir Absen Masuk :</label>
                <input type="time" class="form-control @error('in_end') is-invalid @enderror" id="in_end"
                    name="in_end" required value="{{ old('in_end') }}">
                @error('in_end')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="end_end" class="form-label">Akhir Absen Telat :</label>
                <input type="time" class="form-control @error('end_end') is-invalid @enderror" id="end_end"
                    name="end_end" required value="{{ old('end_end') }}">
                @error('end_end')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="out_start" class="form-label">Absen Keluar :</label>
                <input type="time" class="form-control @error('out_start') is-invalid @enderror" id="out_start"
                    name="out_start" required value="{{ old('out_start') }}">
                @error('out_start')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="izin" class="form-label">Jam Izin :</label>
                <input type="time" class="form-control @error('izin') is-invalid @enderror" id="izin" name="izin"
                    required value="{{ old('izin') }}">
                @error('izin')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Kirim</button>
        </form>
    </div>
@endsection
