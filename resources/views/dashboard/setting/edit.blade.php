@extends('dashboard.layouts.main')

@section('container')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Data</h1>
  </div>

  <div class="col-lg-8">
      <form method="post" action="/dashboard/setting/{{ $setting->id }}" enctype="multipart/form-data">
        @method('put')
        @csrf
        <div class="mb-3">
          <label for="in_start" class="form-label">Awal Absen Masuk :</label>
          <input type="time" class="form-control @error('in_start') is-invalid @enderror" id="in_start" 
          name="in_start" required value="{{old('in_start', $setting->in_start)}}">
          @error('in_start')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
          @enderror
      </div>
              <div class="mb-3">
          <label for="in_end" class="form-label">Akhir Absen Masuk :</label>
          <input type="time" class="form-control @error('in_end') is-invalid @enderror" id="in_end" 
          name="in_end" required value="{{old('in_end', $setting->in_end)}}">
          @error('in_end')
          <div class="invalid-feedback">
            {{ $message }}
          </div>
          @enderror
      </div>
    <div class="mb-3">
      <label for="out_start" class="form-label">Absen Keluar :</label>
      <input type="time" class="form-control @error('out_start') is-invalid @enderror" id="out_start"
      name="out_start" required value="{{old('out_start', $setting->out_start)}}">
      @error('out_start')
      <div class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
  </div>
<div class="mb-3">
    <label for="fine" class="form-label">Denda :</label>
    <input type="text" class="form-control @error('fine') is-invalid @enderror" id="fine"
    name="fine" required value="{{old('fine', $setting->fine)}}">
    @error('fine')
    <div class="invalid-feedback">
      {{ $message }}
    </div>
    @enderror
</div>
<div class="mb-3">
  <label for="fuel" class="form-label">Bensin :</label>
  <input type="text" class="form-control @error('fuel') is-invalid @enderror" id="fuel"
  name="fuel" required value="{{old('fuel', $setting->fuel)}}">
  @error('fuel')
  <div class="invalid-feedback">
    {{ $message }}
  </div>
  @enderror
</div>
      <button type="submit" class="btn btn-primary">Edit</button>
      </form>
  </div>

@endsection