@extends('frontend.layouts.layout')
@section('title', 'FMS-Register')
@section('content')
<div class="auth-card">
  <div class="auth-header">
    <h3><i class="fas fa-user-plus me-2"></i>Create Account</h3>
    <p>Join us today</p>
  </div>

  <div class="auth-body">
    <form method="POST" action="{{ route('register') }}">
      @csrf

      <!-- Name Input -->
      <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-user"></i></span>
          <input type="text" class="form-control"
                 id="name" name="name" value="{{ old('name') }}"
                 placeholder="Enter your full name">
        </div>
      </div>

      <!-- Email Input -->
      <div class="mb-3">
        <label for="email" class="form-label">Email Address</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
          <input type="email" class="form-control"
                 id="email" name="email" value="{{ old('email') }}"
                 placeholder="Enter your email">
        </div>
      </div>

      <!-- Password Input -->
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control"
                 id="password" name="password" placeholder="Create password"
                 oninput="checkPasswordStrength(this.value)">
          <button type="button" class="input-group-text toggle-password">
            <i class="fas fa-eye"></i>
          </button>
        </div>
        <div class="password-strength" id="passwordStrength"></div>
        <small class="form-text text-muted">
          Use 8+ characters with mix of letters, numbers & symbols
        </small>
      </div>

      <!-- Confirm Password Input -->
      <div class="mb-3">
        <label for="password_confirmation" class="form-label">Confirm Password</label>
        <div class="input-group">
          <span class="input-group-text"><i class="fas fa-lock"></i></span>
          <input type="password" class="form-control"
                 id="password_confirmation" name="password_confirmation"
                 placeholder="Confirm your password"
                 oninput="checkPasswordMatch()">
          <button type="button" class="input-group-text toggle-password">
            <i class="fas fa-eye"></i>
          </button>
        </div>
        <div id="passwordMatch" class="small mt-1"></div>
      </div>

      <!-- Terms and Conditions -->
      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input"
                 type="checkbox" name="terms" id="terms">
          <label class="form-check-label" for="terms">
            I agree to the <a href="#" class="text-decoration-none">Terms & Conditions</a>
          </label>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn btn-auth mb-3">
        <i class="fas fa-user-plus me-2"></i>Create Account
      </button>

      <!-- Login Link -->
      <div class="text-center">
        <p class="mb-0">Already have an account?
          <a href="{{ url('/') }}" class="text-decoration-none fw-bold">Sign in here</a>
        </p>
      </div>
    </form>
  </div>
</div>

<script>
  // Toggle password visibility
  document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
      const input = this.closest('.input-group').querySelector('input');
      const icon = this.querySelector('i');

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
  });

  // Password strength checker
  function checkPasswordStrength(password) {
    const strengthBar = document.getElementById('passwordStrength');
    let strength = 0;

    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/\d/)) strength++;
    if (password.match(/[^a-zA-Z\d]/)) strength++;

    strengthBar.className = 'password-strength';
    if (password.length === 0) {
      strengthBar.style.width = '0%';
    } else if (strength < 2) {
      strengthBar.classList.add('strength-weak');
    } else if (strength < 4) {
      strengthBar.classList.add('strength-medium');
    } else {
      strengthBar.classList.add('strength-strong');
    }
  }

  // Password confirmation match checker
  function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const matchDiv = document.getElementById('passwordMatch');

    if (confirmPassword === '') {
      matchDiv.innerHTML = '';
      matchDiv.className = 'small mt-1';
    } else if (password === confirmPassword) {
      matchDiv.innerHTML = '<i class="fas fa-check-circle"></i> Passwords match';
      matchDiv.className = 'small mt-1 text-success';
    } else {
      matchDiv.innerHTML = '<i class="fas fa-times-circle"></i> Passwords do not match';
      matchDiv.className = 'small mt-1 text-danger';
    }
  }
</script>
@endsection
