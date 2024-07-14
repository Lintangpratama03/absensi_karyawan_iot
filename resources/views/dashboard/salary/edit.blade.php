@extends('dashboard.layouts.main')

@section('container')
    <style>
        label {
            font-weight: bold;
        }
    </style>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Salary</h1>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('salaries.update', $salary->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $salary->name }}" required>
        </div>
        <div class="form-group">
            <label for="nominal">Nominal</label>
            <input type="text" class="form-control" id="nominal" name="nominal"
                value="Rp. {{ number_format($salary->nominal, 0, ',', '.') }}" required>
        </div>
        <script>
            // Format input nominal dengan pemisah ribuan dan RP
            document.addEventListener('DOMContentLoaded', function() {
                var nominalInput = document.getElementById('nominal');
                nominalInput.addEventListener('input', function() {
                    // Menghapus semua karakter non-digit
                    var nominal = this.value.replace(/\D/g, '');

                    // Format sebagai RP dengan separator ribuan
                    this.value = formatRupiah(nominal, 'Rp ');

                    // Fungsi untuk memformat angka menjadi format Rupiah
                    function formatRupiah(angka, prefix) {
                        var number_string = angka.toString().replace(/\D/g, '');
                        var split = number_string.split(',');
                        var sisa = split[0].length % 3;
                        var rupiah = split[0].substr(0, sisa);
                        var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                        // Tambahkan separator ribuan
                        if (ribuan) {
                            var separator = sisa ? '.' : '';
                            rupiah += separator + ribuan.join('.');
                        }

                        // Tambahkan koma jika ada nilai desimal
                        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                        return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
                    }
                });
            });
        </script>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
