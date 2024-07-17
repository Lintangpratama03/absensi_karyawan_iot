@extends('dashboard.layouts.main')

@section('container')
    <style>
        label {
            font-weight: bold;
        }
    </style>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Create Salary</h1>
    </div>
    <h6>*denda telat = 'telat'</h6>
    <h6>*uang transport = 'transport'</h6>
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

    <form method="POST" action="{{ route('salaries.store') }}" id="salaryForm">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                        required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nominal">Nominal</label>
                    <input type="text" class="form-control" id="nominal" name="nominal" value="{{ old('nominal') }}"
                        required>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var nominalInput = document.getElementById('nominal');
            nominalInput.addEventListener('input', function() {
                var nominal = this.value.replace(/\D/g, '');
                this.value = formatRupiah(nominal, 'Rp ');
            });

            document.getElementById('salaryForm').addEventListener('submit', function() {
                nominalInput.value = nominalInput.value.replace(/[^0-9]/g, '');
            });

            function formatRupiah(angka, prefix) {
                var number_string = angka.toString().replace(/\D/g, '');
                var split = number_string.split(',');
                var sisa = split[0].length % 3;
                var rupiah = split[0].substr(0, sisa);
                var ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    var separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                return prefix == undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
            }
        });
    </script>
@endsection
