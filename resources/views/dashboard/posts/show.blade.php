@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Profil Karyawan</h1>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Bagian untuk Gambar -->
                            <div class="col-md-6 d-flex align-items-center justify-content-center">
                                <img src="{{ asset('storage/' . $post->image) }}"alt="Employee Image" width="150"
                                    class="img-thumbnail">
                                <!-- Ganti 'path_ke_gambar' dengan path aktual gambar Anda -->
                            </div>

                            <!-- Bagian untuk Teks -->
                            <div class="col-md-6">
                                <h3 class="card-title">{{ $post->name }}</h3>
                                <p class="card-text"><strong>Alamat:</strong> {{ $post->address }}</p>
                                <p class="card-text"><strong>Telp:</strong> {{ $post->telp }}</p>
                                <p class="card-text"><strong>Mulai Bekerja:</strong> {{ $post->start }}</p>
                                <p class="card-text"><strong>Gaji Pokok:</strong> Rp
                                    {{ number_format($post->salary, 0, ',', '.') }}</p>
                                <a href="/dashboard/posts" class="btn btn-primary">Back</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
