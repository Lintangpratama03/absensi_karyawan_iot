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
                    name="salaryy" required value="Rp. {{ old('salary', number_format($post->salary, 0, ',', '.')) }}"
                    disabled>
                @error('salary')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="salary" class="form-label">Gaji Pokok</label>
                <select class="form-control @error('salary') is-invalid @enderror" id="salary" name="salary" required>
                    <option value="0">Silahkan Pilih Gaji Pokok</option>
                    @foreach ($gaji as $g)
                        <option value="{{ $g->nominal }}" {{ old('salary') == $g->nominal ? 'selected' : '' }}>
                            RP {{ number_format($g->nominal, 0, ',', '.') }} [{{ $g->name }}]
                        </option>
                    @endforeach
                </select>
                @error('salary')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3 d-none">
                <label for="holiday_salary" class="form-label">Gaji Libur</label>
                <input type="text" class="form-control" id="holiday_salary" name="holiday_salary" readonly>
            </div>

            <script>
                document.getElementById('salary').addEventListener('input', function() {
                    var salaryValue = parseFloat(this.value.replace(/[^\d.-]/g, '')); // Remove non-numeric characters
                    if (!isNaN(salaryValue)) {
                        var holidaySalary = salaryValue * 2; // Hitung gaji libur
                        document.getElementById('holiday_salary').value = holidaySalary.toFixed(
                            2); // Menetapkan nilai tanpa format mata uang
                    } else {
                        document.getElementById('holiday_salary').value = '';
                    }
                });
            </script>

            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
@endsection
