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
        <h1 class="h2">Tabel Gaji</h1>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="month">Bulan:</label>
                <select id="month" name="month" class="form-control">
                    <option value="">Pilih Bulan</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="form-group">
                <label for="year">Tahun:</label>
                <select id="year" name="year" class="form-control">
                    <option value="">Pilih Tahun</option>
                    @php
                        $current_year = date('Y');
                        for ($year = $current_year; $year >= 2000; $year--) {
                            echo "<option value='$year'>$year</option>";
                        }
                    @endphp
                </select>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <label>&nbsp;</label><br>
            <button id="filterButton" type="button" class="btn btn-primary btn-block">Filter</button>
        </div>
    </div>
    <table id="payrollTable" class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Masa Kerja</th>
                <th>Nama Karyawan</th>
                <th>Total Masuk</th>
                <th>Gaji Pokok</th>
                <th>Potongan</th>
                <th>Bonus</th>
                <th>Transport</th>
                <th>Gaji Libur</th>
                <th>Total Gaji</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            {{-- Data will be loaded via DataTables --}}
        </tbody>
    </table>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        var generatePdfRoute = "{{ route('dashboard.payroll.generate-pdf', ':id') }}";

        $(document).ready(function() {
            var table = $('#payrollTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('dashboard.payroll.data') }}",
                    type: 'GET',
                    data: function(d) {
                        var month = $('#month').val();
                        var year = $('#year').val();
                        if (month && year) {
                            d.date = year + '-' + month;
                        } else {
                            d.month = month;
                            d.year = year;
                        }
                    }
                },
                columns: [{
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'count',
                        name: 'count'
                    },
                    {
                        data: 'total_salary',
                        name: 'total_salary'
                    },
                    {
                        data: 'cut',
                        name: 'cut'
                    },
                    {
                        data: 'bonus',
                        name: 'bonus'
                    },
                    {
                        data: 'total_transport',
                        name: 'total_transport'
                    },
                    {
                        data: 'holiday_salary',
                        name: 'holiday_salary'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        render: function(data, type, row) {
                            var pdfUrl = generatePdfRoute.replace(':id', data);
                            return `<a href="${pdfUrl}" class="btn btn-primary btn-sm">Download</a>`;
                        }
                    }
                ]
            });

            $('#filterButton').click(function() {
                table.ajax.reload();
            });
        });
    </script>
@endsection
