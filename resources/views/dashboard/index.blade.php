@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Welcome back, {{ auth()->user()->name }}</h1>
    </div>

    <form action="{{ route('dashboard') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="date" class="form-control" name="date" value="{{ $date->format('Y-m-d') }}">
            <button class="btn btn-outline-secondary" type="submit">Filter</button>
        </div>
    </form>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-sign-in-alt fa-2x me-2"></i>
                        <h5 class="card-title mb-0">Total Absen Masuk</h5>
                    </div>
                    <p class="card-text display-4">{{ $checkInsToday }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-sign-out-alt fa-2x me-2"></i>
                        <h5 class="card-title mb-0">Total Absen Keluar</h5>
                    </div>
                    <p class="card-text display-4">{{ $checkOutsToday }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-user-clock fa-2x me-2"></i>
                        <h5 class="card-title mb-0">Total Bekerja</h5>
                    </div>
                    <p class="card-text display-4">{{ $workingToday }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-users fa-2x me-2"></i>
                        <h5 class="card-title mb-0">Total</h5>
                    </div>
                    <p class="card-text display-4">{{ $totalEmployees }} </p>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info">
        Showing data for: {{ $date->format('d F Y') }}
    </div>
@endsection
