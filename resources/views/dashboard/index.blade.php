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

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Absen Bekerja Setiap Hari</h5>
                    <form action="{{ route('dashboard') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <select name="month" class="form-select">
                                @foreach ($months as $month)
                                    <option value="{{ $month }}" {{ $selectedMonth == $month ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-secondary" type="submit">Filter</button>
                        </div>
                    </form>
                    <canvas id="dailyAttendanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('dailyAttendanceChart').getContext('2d');
        var chartData = @json($dailyAttendance);

        var labels = chartData.map(function(item) {
            return item.date;
        });

        var checkInsData = chartData.map(function(item) {
            return item.check_ins;
        });

        var checkOutsData = chartData.map(function(item) {
            return item.check_outs;
        });

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Absen Masuk',
                        data: checkInsData,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    },
                    {
                        label: 'Absen Keluar',
                        data: checkOutsData,
                        borderColor: 'rgb(255, 99, 132)',
                        tension: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Karyawan'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tanggal'
                        }
                    }
                }
            }
        });
    });
</script>
