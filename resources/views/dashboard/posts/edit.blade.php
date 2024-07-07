@extends('dashboard.layouts.main')

@section('container')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Data Karyawan</h1>
    </div>

    <div class="col-lg-8">
        <form method="post" action="/dashboard/posts/{{ $post->id }}" enctype="multipart/form-data">
            @method('put')
            @csrf
            <div class="mb-3">
                <label for="start" class="form-label">Mulai Bekerja</label>
                <input type="date" class="form-control @error('start') is-invalid @enderror" id="start" name="start"
                    required value="{{ old('start', $post->start) }}">
                @error('start')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="salaryy" class="form-label">Gaji Pokok Saat Ini</label>
                <input type="text" class="form-control @error('salary') is-invalid @enderror" id="salaryy"
                    name="salaryy" required value="{{ old('salary', $post->salary) }}" disabled>
                @error('salary')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="salary" class="form-label">Gaji Pokok</label>
                <select class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" required>
                    <option value="0">
                        Silahkan Pilih Gaji Pokok</option>
                    @foreach ($gaji as $g)
                        <option value="{{ $g->nominal }}" {{ old('salary') == $g->nominal ? 'selected' : '' }}>
                            {{ $g->nominal }} [{{ $g->name }}] </option>
                    @endforeach
                </select>
                @error('salary')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="holiday_salary" class="form-label">Gaji Libur</label>
                <input type="text" class="form-control" id="holiday_salary" name="holiday_salary" readonly>
            </div>
            <script>
                document.getElementById('salary').addEventListener('change', function() {
                    var salaryValue = parseFloat(this.value);
                    if (!isNaN(salaryValue)) {
                        document.getElementById('holiday_salary').value = salaryValue * 2;
                    } else {
                        document.getElementById('holiday_salary').value = '';
                    }
                });
            </script>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
@endsection
