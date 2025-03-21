<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>{{ config('app.name', 'Laravel') }} - Login</title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/material-dashboard.css?v=3.2.0') }}" rel="stylesheet" />
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Round" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #1a73e8;
      --primary-dark: #1557b0;
      --secondary-color: #637381;
    }

    .bg-gradient-primary {
      background-image: linear-gradient(195deg, #1a73e8 0%, #1557b0 100%);
    }

    .bg-gradient-secondary {
      background-image: linear-gradient(195deg, #637381 0%, #49545c 100%);
    }

    .bg-gradient-success {
      background-image: linear-gradient(195deg, #4CAF50 0%, #2E7D32 100%);
    }

    .bg-gradient-warning {
      background-image: linear-gradient(195deg, #FB8C00 0%, #F57C00 100%);
    }

    .bg-gradient-danger {
      background-image: linear-gradient(195deg, #EF5350 0%, #D32F2F 100%);
    }

    /* Loading Overlay */
    .fixed-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.7);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .fixed-overlay.show {
      opacity: 1;
      visibility: visible;
    }

    .loading-spinner {
      text-align: center;
    }

    /* Toast Container for Notifications */
    .toast-container {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1050;
    }

    .custom-toast {
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      margin-bottom: 10px;
      opacity: 0;
      transition: opacity 0.3s ease-in-out;
    }

    .custom-toast.show {
      opacity: 1;
    }

    /* Animation classes */
    .fade-in {
      animation: fadeIn 0.3s ease-in;
    }

    .fade-out {
      animation: fadeOut 0.3s ease-out;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }

    /* Input focus effects */
    .form-control {
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(26, 115, 232, 0.25);
    }

    /* Button hover effects */
    .btn {
      transition: all 0.2s ease;
    }
    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }

    /* Card hover effects */
    .card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 7px 14px rgba(50, 50, 93, 0.1), 0 3px 6px rgba(0, 0, 0, 0.08);
    }
  </style>
</head>

<body class="bg-gray-200">
  <!-- Loading Overlay -->
  <div id="loadingOverlay" class="fixed-overlay">
    <div class="loading-spinner">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2 text-white" id="loadingMessage">Logging in...</p>
    </div>
  </div>

  <!-- Toast Container for Notifications -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="toastContainer"></div>
  </div>

  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur border-radius-xl top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid ps-2 pe-0">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3" href="{{ url('/') }}">
              {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
              <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{ url('/') }}">
                    <i class="material-symbols-rounded opacity-6 text-dark me-1">home</i>
                    Home
                  </a>
                </li>
                @if (Route::has('register'))
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ route('register') }}">
                    <i class="material-symbols-rounded opacity-6 text-dark me-1">person_add</i>
                    Register
                  </a>
                </li>
                @endif
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ route('login') }}">
                    <i class="material-symbols-rounded opacity-6 text-dark me-1">login</i>
                    Sign In
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content mt-0">
    <div class="page-header align-items-start min-vh-100" style="background-image: url('https://images.unsplash.com/photo-1497294815431-9365093b7331?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1950&q=80');">
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container my-auto">
        <div class="row">
          <div class="col-lg-4 col-md-8 col-12 mx-auto">
            <div class="card z-index-0 fadeIn3 fadeInBottom">
              <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                  <h4 class="text-white font-weight-bolder text-center mt-2 mb-0">Sign in</h4>
                  <div class="row mt-3">
                    <div class="col-2 text-center ms-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-facebook text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center px-1">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-github text-white text-lg"></i>
                      </a>
                    </div>
                    <div class="col-2 text-center me-auto">
                      <a class="btn btn-link px-3" href="javascript:;">
                        <i class="fa fa-google text-white text-lg"></i>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <!-- Session Status -->
                @if (session('status'))
                <div class="alert alert-success alert-dismissible text-white" role="alert">
                  <span class="text-sm">{{ session('status') }}</span>
                  <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible text-white" role="alert">
                  <span class="text-sm">{{ __('Whoops! Something went wrong.') }}</span>
                  <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                  <button type="button" class="btn-close text-lg py-3 opacity-10" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif

                <form role="form" method="POST" action="{{ route('login') }}" class="text-start" id="loginForm">
                  @csrf
                  <div class="input-group input-group-outline my-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                  </div>
                  <div class="input-group input-group-outline mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                  </div>
                  <div class="form-check form-switch d-flex align-items-center mb-3">
                    <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                    <label class="form-check-label mb-0 ms-3" for="remember_me">Remember me</label>
                  </div>
                  <div class="text-center">
                    <button type="submit" class="btn bg-gradient-primary w-100 my-4 mb-2">Sign in</button>
                  </div>
                  <p class="mt-4 text-sm text-center">
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-primary text-gradient font-weight-bold">Forgot your password?</a>
                    @endif
                  </p>
                  <p class="text-sm text-center">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary text-gradient font-weight-bold">Sign up</a>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer position-absolute bottom-2 py-2 w-100">
        <div class="container">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-12 col-md-6 my-auto">
              <div class="copyright text-center text-sm text-white text-lg-start">
                © {{ date('Y') }} {{ config('app.name', 'Laravel') }}
              </div>
            </div>
            <div class="col-12 col-md-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="javascript:;" class="nav-link text-white">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="javascript:;" class="nav-link text-white">Privacy Policy</a>
                </li>
                <li class="nav-item">
                  <a href="javascript:;" class="nav-link pe-0 text-white">Contact</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  <!--   Core JS Files   -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  
  <script>
    // Handle input focus for outline inputs
    document.addEventListener('DOMContentLoaded', function() {
      const inputs = document.querySelectorAll('.input-group-outline input');
      
      inputs.forEach(input => {
        if (input.value !== '') {
          input.parentElement.classList.add('is-filled');
        }
        
        input.addEventListener('focus', () => {
          input.parentElement.classList.add('is-focused');
        });
        
        input.addEventListener('blur', () => {
          input.parentElement.classList.remove('is-focused');
          if (input.value !== '') {
            input.parentElement.classList.add('is-filled');
          } else {
            input.parentElement.classList.remove('is-filled');
          }
        });
      });

      // Initialize form with filled classes if needed
      inputs.forEach(input => {
        if (input.value !== '') {
          input.parentElement.classList.add('is-filled');
        }
      });

      // Show loading overlay on form submit
      const loginForm = document.getElementById('loginForm');
      if (loginForm) {
        loginForm.addEventListener('submit', function() {
          showLoading('Logging in...');
        });
      }

      // Convert all session flash messages to toasts
      const flashTypes = ['success', 'error', 'warning', 'info'];
      flashTypes.forEach(type => {
        const flashMessage = document.querySelector(`.alert-${type}`);
        if (flashMessage) {
          const message = flashMessage.querySelector('.text-sm').textContent;
          flashMessage.remove();
          showToast(message, type);
        }
      });
    });

    // Loading overlay functions
    function showLoading(message = 'Loading...') {
      document.getElementById('loadingMessage').textContent = message;
      const overlay = document.getElementById('loadingOverlay');
      overlay.classList.add('show');
    }

    function hideLoading() {
      const overlay = document.getElementById('loadingOverlay');
      overlay.classList.remove('show');
    }

    // Toast notification functions
    function showToast(message, type = 'success') {
      const toast = document.createElement('div');
      toast.className = `custom-toast ${type}`;
      
      const colors = {
        success: 'bg-gradient-success',
        error: 'bg-gradient-danger',
        warning: 'bg-gradient-warning',
        info: 'bg-gradient-info'
      };
      
      toast.innerHTML = `
        <div class="alert alert-dismissible text-white ${colors[type]} mb-0">
          <span class="text-sm">${message}</span>
          <button type="button" class="btn-close text-lg py-3 opacity-10" 
                  aria-label="Close" 
                  onclick="this.closest('.custom-toast').remove()">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      `;
      
      document.getElementById('toastContainer').appendChild(toast);
      
      // Add show class after a brief delay to trigger animation
      setTimeout(() => toast.classList.add('show'), 100);
      
      // Auto-remove after 5 seconds
      setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
      }, 5000);
    }
  </script>
</body>

</html>
