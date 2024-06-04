@extends('dashboard.layouts.main')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Custom styles for this template -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="/css/dashboard.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<!-- Custom styles for this template -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="/css/dashboard.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Employee</h1>
    </div>

    <div class="row">
        <div class="col-md-6">
            <form action="{{ route('employee.posts.index') }}" method="GET">
                <div class="form-group">
                    <label for="filterDate">Filter dengan Tanggal:</label>
                    <input type="date" id="filterDate" name="filterDate" class="form-control">
                    <button type="submit" class="btn btn-primary mt-2">Filter</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <form action="{{ route('employee.posts.index') }}" method="GET">
                <div class="form-group">
                    <label for="filterMonth">Filter dengan Bulan:</label>
                    <input type="month" id="filterMonth" name="filterMonth" class="form-control">
                    <button type="submit" class="btn btn-primary mt-2">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-3"></div>

    <ul class="nav nav-tabs" id="attendanceTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="in-tab" data-bs-toggle="tab" data-bs-target="#in" type="button"
                role="tab" aria-controls="in" aria-selected="true">Data Absensi</button>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="in" role="tabpanel" aria-labelledby="in-tab">
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama</th>
                                <th>Waktu Masuk</th>
                                <th>Status Masuk</th>
                                <th>Waktu Keluar</th>
                                <th>Status Keluar</th>
                                <th>Denda</th>
                                <th>Status Kerja</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyRecords as $record)
                                <tr>
                                    <td>{{ $record['date'] }}</td>
                                    <td>{{ $record['name'] }}</td>
                                    <td>{{ $record['time_in'] }}</td>
                                    <td>{{ $record['status_in'] }}</td>
                                    <td>{{ $record['time_out'] }}</td>
                                    <td>{{ $record['status_out'] }}</td>
                                    <td>{{ $record['penalty'] }}</td>
                                    <td>{{ $record['work_status'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var t = $('#myTable').DataTable({
                "paging": true,
                "searching": true,
                "columnDefs": [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 0
                }]

            });
        });
    </script>
@endsection
