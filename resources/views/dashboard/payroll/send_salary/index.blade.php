@extends('dashboard.layouts.main')

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@section('container')
    <style>
        label {
            font-weight: bold;
        }
    </style>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Kirim Gaji Karyawan</h1>
    </div>

    <a href="#" class="btn btn-primary mb-3" id="send-salary">Kirim Gaji untuk Seluruh Karyawan</a>

    <table class="table table-striped table-bordered yajra-datatable">
        <thead class="table-dark">
            <tr>
                <th>Bulan</th>
                <th>Nama Karyawan</th>
                <th>Total Gaji</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payrolls as $payroll)
                <tr>
                    <td>{{ $payroll->month }}</td>
                    <td>{{ $payroll->name }}</td>
                    <td>Rp.{{ number_format($payroll->amount, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-toggle="modal"
                            data-target="#detailModal-{{ $payroll->id }}">Detail</button>
                    </td>
                </tr>

                <!-- Modal -->
                <div class="modal fade" id="detailModal-{{ $payroll->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="detailModalLabel">Detail Gaji</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Karyawan</label>
                                            <input type="text" class="form-control" value="{{ $payroll->name }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Bulan</label>
                                            <input type="text" class="form-control" value="{{ $payroll->month }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Tag</label>
                                            <input type="text" class="form-control" value="{{ $payroll->tag }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Jumlah Hari Kerja</label>
                                            <input type="text" class="form-control" value="{{ $payroll->count }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Jumlah Hari Libur</label>
                                            <input type="text" class="form-control" value="{{ $payroll->holiday }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Jumlah Hari Terlambat</label>
                                            <input type="text" class="form-control" value="{{ $payroll->late }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gaji Pokok</label>
                                            <input type="text" class="form-control"
                                                value="Rp.{{ number_format($payroll->salary, 0, ',', '.') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Gaji Hari Libur</label>
                                            <input type="text" class="form-control"
                                                value="Rp.{{ number_format($payroll->holiday_salary, 0, ',', '.') }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Bonus</label>
                                            <input type="text" class="form-control"
                                                value="Rp.{{ number_format($payroll->bonus, 0, ',', '.') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Gaji</label>
                                            <input type="text" class="form-control"
                                                value="Rp.{{ number_format($payroll->total_salary, 0, ',', '.') }}"
                                                readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Potongan</label>
                                            <input type="text" class="form-control"
                                                value="Rp.{{ number_format($payroll->cut, 0, ',', '.') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Total Transportasi</label>
                                            <input type="text" class="form-control"
                                                value="Rp.{{ number_format($payroll->total_transport, 0, ',', '.') }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Jumlah Diterima</label>
                                            <input type="text" class="form-control"
                                                value="Rp.{{ number_format($payroll->amount, 0, ',', '.') }}" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Catatan</label>
                                            <textarea class="form-control" readonly>{{ $payroll->note }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </tbody>
    </table>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            var table = null;

            function initDataTable() {
                if (table) {
                    table.destroy();
                }

                table = $('.yajra-datatable').DataTable({
                    order: [
                        [0, 'desc']
                    ]
                });
            }

            initDataTable();

            $('#send-salary').click(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Kirim Gaji Karyawan',
                    text: 'Apakah Anda yakin ingin mengirim gaji untuk seluruh karyawan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Kirim',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Masukkan Bulan',
                            html: '<input type="month" id="month-input" class="swal2-input">',
                            showCancelButton: true,
                            confirmButtonText: 'Kirim',
                            cancelButtonText: 'Batal',
                            preConfirm: () => {
                                const month = document.getElementById('month-input')
                                    .value;
                                if (!month) {
                                    Swal.showValidationMessage(
                                        'Anda harus memilih bulan!');
                                }
                                return month;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const selectedMonth = result.value;
                                $.ajax({
                                    url: "{{ route('payroll.check_existing') }}",
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        month: result.value
                                    },
                                    success: function(response) {
                                        if (response.exists) {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Peringatan',
                                                text: 'Gaji bulan ' +
                                                    response.month +
                                                    ' sudah terkirim untuk semua karyawan.',
                                                confirmButtonText: 'OK'
                                            });
                                        } else {
                                            $.ajax({
                                                url: "{{ route('payroll.store_all') }}",
                                                type: "POST",
                                                data: {
                                                    _token: "{{ csrf_token() }}",
                                                    month: result.value
                                                },
                                                success: function(
                                                    response) {
                                                    Swal.fire({
                                                        icon: 'success',
                                                        title: 'Berhasil!',
                                                        text: response
                                                            .message,
                                                        confirmButtonText: 'OK'
                                                    }).then(
                                                        () => {
                                                            location
                                                                .reload();
                                                        });
                                                },
                                                error: function(xhr,
                                                    status, error) {
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Oops...',
                                                        text: 'Terjadi kesalahan: ' +
                                                            error
                                                    });
                                                }
                                            });
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Terjadi kesalahan: ' +
                                                error
                                        });
                                    }
                                });
                            }
                        });
                    }
                });
            });

            $(window).on('load', function() {
                initDataTable();
            });
        });
    </script>
@endsection
