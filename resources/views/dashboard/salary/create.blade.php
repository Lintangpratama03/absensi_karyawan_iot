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

    <form method="POST" action="{{ route('salaries.store') }}">
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
                    <input type="number" class="form-control" id="nominal" name="nominal" value="{{ old('nominal') }}"
                        required>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
@endsection
