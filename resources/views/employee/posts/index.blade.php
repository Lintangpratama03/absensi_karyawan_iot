@extends('employee.layouts.main')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

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
                                <th>No</th>
                                <th>Tanggal</th>
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
                                    <td></td>
                                    <td>{{ $record['date'] }}</td>
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

<script>
    $(document).ready(function() {
        var t = $('#myTable').DataTable({
            "paging": true,
            "searching": true,
            "columnDefs": [{
                "searchable": false,
                "orderable": false,
                "targets": 0
            }],
            "order": [
                [1, 'desc']
            ],
            "drawCallback": function(settings) {
                var api = this.api();
                var start = api.page.info().start;
                api.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = start + i + 1;
                });
            }
        });

        // To apply ordering to the first column (numbering)
        t.on('order.dt search.dt', function() {
            t.column(0, {
                order: 'applied',
                search: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>
