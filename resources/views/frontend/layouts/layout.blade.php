<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', 'Logic Partners')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .auth-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .auth-card {
      background: white;
      border-radius: 15px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.1);
      overflow: hidden;
      width: 100%;
      max-width: 450px;
      margin: 20px;
    }

    .auth-header {
      background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
      color: white;
      padding: 30px 20px;
      text-align: center;
    }

    .auth-header h3 {
      margin: 0;
      font-weight: 600;
    }

    .auth-header p {
      margin: 10px 0 0 0;
      opacity: 0.9;
    }

    .auth-body {
      padding: 30px;
    }

    .form-label {
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 8px;
    }

    .input-group-text {
      background-color: #f8f9fa;
      border: 1px solid #ced4da;
      border-right: none;
    }

    .form-control {
      border-left: none;
      padding: 12px;
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .input-group:focus-within .input-group-text {
      border-color: #667eea;
      background-color: #e9ecef;
    }

    .btn-auth {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      color: white;
      padding: 12px;
      width: 100%;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
    }

    .btn-auth:hover {
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .password-strength {
      height: 5px;
      margin-top: 8px;
      border-radius: 3px;
      transition: all 0.3s ease;
      background: #e9ecef;
    }

    .strength-weak { background: #dc3545; width: 25%; }
    .strength-medium { background: #ffc107; width: 50%; }
    .strength-strong { background: #28a745; width: 100%; }

    .toggle-password {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .toggle-password:hover {
      background-color: #e9ecef;
    }

    .form-check-input:checked {
      background-color: #667eea;
      border-color: #667eea;
    }

    a {
      color: #667eea;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    a:hover {
      color: #764ba2;
    }

    .text-muted {
      font-size: 0.875rem;
    }
  </style>
</head>

<body>
  <!-- Flash success message -->
  @if(session('success'))
  <div class="flas-message">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      const Toast = Swal.mixin({
        toast: true,
        position: 'top-right',
        iconColor: 'white',
        customClass: {
          popup: 'colored-toast',
        },
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true,
      });

      (async () => {
        await Toast.fire({
          icon: 'success',
          title: '{{ session('success') }}',
        });
      })();
    </script>
  </div>
  @endif

  <div class="auth-container">
    @yield('content')
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
