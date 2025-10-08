@extends('frontend.layouts.layout')
@section('title', 'FMS-Login')
@section('content')
<div class="auth-card">
  <div class="auth-header">
    <h3><i class="fas fa-sign-in-alt me-2"></i>Welcome Back</h3>
    <p>Sign in to your account</p>
  </div>

  <div class="auth-body">
    <form method="POST" action="{{ route('login') }}">
      @csrf

      <!-- Email Input -->
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" class="form-control @error('email') is-invalid @enderror"
                 id="email" name="email" value="{{ old('email') }}"
                 placeholder="Enter your email">
        </div>
        @error('email')
          <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>

      <!-- Password Input -->
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control @error('password') is-invalid @enderror"
                 id="password" name="password" placeholder="Enter your password">
          <button type="button" class="input-group-text toggle-password">
            <i class="fas fa-eye"></i>
          </button>
        </div>
        @error('password')
          <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>

      <!-- Remember Me & Forgot Password -->
      <div class="mb-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="remember" id="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
        <a href="{{ route('password.request') }}" class="text-decoration-none">Forgot password?</a>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-auth mb-3">
        <i class="fas fa-sign-in-alt me-2"></i>Sign In
      </button>

      <!-- Register Link -->
      <div class="text-center">
        <p class="mb-0">Don't have an account?
          <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Sign up here</a>
        </p>
      </div>
    </form>
  </div>
</div>

<script>
  // Toggle password visibility
  document.querySelector('.toggle-password').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      passwordInput.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  });
</script>
@endsection
