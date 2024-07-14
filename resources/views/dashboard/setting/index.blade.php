@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Setting Waktu Absen</h1>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive col-lg-11">
        <a href="/dashboard/setting/create" class ="btn btn-primary mb-3">Setting Waktu</a>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Awal Absen Masuk</th>
                    <th scope="col">Akhir Absen Masuk</th>
                    <th scope="col">Akhir Absen Telat</th>
                    <th scope="col">Absen Keluar</th>
                    <th scope="col">Jam Izin</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($settings as $setting)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $setting->in_start }}</td>
                        <td>{{ $setting->in_end }}</td>
                        <td>{{ $setting->end_end }}</td>
                        <td>{{ $setting->out_start }}</td>
                        <td>{{ $setting->izin }}</td>
                        <td>
                            <a href="/dashboard/setting/{{ $setting->id }}/edit" class="badge bg-warning"> <svg
                                    class="bi">
                                    <use xlink:href="#pencil-square" />
                                </svg></a>
                            <form action ="/dashboard/setting/{{ $setting->id }}" method="post" class="d-inline">
                                @method('delete')
                                @csrf
                                <button class="badge bg-danger border-0" onclick="return confirm('Are you sure?')"><svg
                                        class="bi">
                                        <use xlink:href="#trash" />
                                    </svg></button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
