@extends('auth.core')
{{-- Title --}}
@section('title', 'Login')

@section('content')
<section class="p-3 p-md-4 p-xl-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-xxl-11">
        <div class="">
          <div class="row g-0">
            <div class="col-12 col-md-6">
              <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="{{ asset('assets/img/PETERNAKAN.png') }}" alt="Welcome back you've been missed!">
            </div>
            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
              <div class="col-12 col-lg-11 col-xl-10">
                <div class="p-3 p-md-4 p-xl-5">

                  <form action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    <div class="row gy-3 overflow-hidden">
                      <div class="col-12">
                        <div class="form-floating mb-3">
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                          <label for="email" class="form-label">Email</label>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-floating mb-3">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                          <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                          <label for="password" class="form-label">Password</label>
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="d-grid">
                          <button class="btn btn-dark btn-lg" type="submit">Log in now</button>
                        </div>
                      </div>
                    </div>
                  </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
