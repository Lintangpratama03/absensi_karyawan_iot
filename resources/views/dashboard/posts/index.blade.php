@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Karyawan</h1>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive col-lg-11">
        <a href="/dashboard/posts/create" class ="btn btn-primary mb-3">Tambahkan Data Baru</a>
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Tag</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Alamat</th>
                    <th scope="col">Telp</th>
                    <th scope="col">Mulai Bekerja</th>
                    <th scope="col">Gaji Pokok</th>
                    <th scope="col">Gaji Libur</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($posts as $post)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $post->tag }}</td>
                        <td>{{ $post->name }}</td>
                        <td>{{ $post->address }}</td>
                        <td>{{ $post->telp }}</td>
                        <td>{{ $post->start }}</td>
                        <td>Rp.{{ number_format($post->salary, 0, ',', '.') }}</td>
                        <td>Rp.{{ number_format($post->holiday_salary, 0, ',', '.') }}</td>
                        <td>
                            <a href="/dashboard/posts/{{ $post->id }}" class="badge bg-info"> <svg class="bi">
                                    <use xlink:href="#eye" />
                                </svg> </a>
                            <a href="/dashboard/posts/{{ $post->id }}/edit" class="badge bg-warning"> <svg
                                    class="bi">
                                    <use xlink:href="#pencil-square" />
                                </svg></a>
                            <form action ="/dashboard/posts/{{ $post->id }}" method="post" class="d-inline">
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
