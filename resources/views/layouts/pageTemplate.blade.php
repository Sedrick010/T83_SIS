<!--
=========================================================
* Material Dashboard 3 - v3.2.0
=========================================================

* Product Page: https://www.creative-tim.com/product/material-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <title>
   @yield('title')
  </title>
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

    .btn {
      text-transform: none;
      font-weight: 500;
    }

    .nav-link.active {
      background-image: linear-gradient(195deg, #1a73e8 0%, #1557b0 100%);
    }

    .sidenav .navbar-brand {
      padding: 1rem;
    }

    .form-label {
      color: var(--secondary-color);
    }

    .input-group.input-group-static .form-control {
      border-color: #ddd;
    }

    .input-group.input-group-static .form-control:focus {
      border-color: var(--primary-color);
    }

    .table thead th {
      color: var(--secondary-color);
      font-weight: 600;
    }

    .badge {
      text-transform: none;
      font-weight: 500;
    }

    .card {
      box-shadow: 0 2px 12px 0 rgba(0,0,0,0.1);
    }

    .card .card-header {
      background: transparent;
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand px-4 py-3 m-0" href="{{ route('admin.dashboard') }}">
        <img src="{{ asset('assets/img/logo-ct-dark.png') }}" class="navbar-brand-img" width="26" height="26" alt="main_logo">
        <span class="ms-1 text-sm text-dark">SIS Admin</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.dashboard') }}">
            <i class="material-symbols-rounded opacity-5">dashboard</i>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.students.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.students.index') }}">
            <i class="material-symbols-rounded opacity-5">group</i>
            <span class="nav-link-text ms-1">Students</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.subjects.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.subjects.index') }}">
            <i class="material-symbols-rounded opacity-5">library_books</i>
            <span class="nav-link-text ms-1">Subjects</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.enrollments.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.enrollments.index') }}">
            <i class="material-symbols-rounded opacity-5">how_to_reg</i>
            <span class="nav-link-text ms-1">Enrollments</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('admin.grades.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('admin.grades.index') }}">
            <i class="material-symbols-rounded opacity-5">grade</i>
            <span class="nav-link-text ms-1">Grades</span>
          </a>
        </li>
       
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('profile.edit') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('profile.edit') }}">
            <i class="material-symbols-rounded opacity-5">person</i>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer position-absolute w-100 bottom-0 ">
      <div class="mx-3">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="btn bg-gradient-dark w-100">
            <i class="material-symbols-rounded opacity-5">logout</i>
            Logout
          </button>
        </form>
      </div>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.dashboard') }}">Admin</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('title')</li>
          </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          
          <ul class="navbar-nav d-flex align-items-center justify-content-end">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item dropdown">
                @include('components.notifications')
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-2">
    @yield('content')
      </div>
     
    </div>
  </main>
  
  <!--   Core JS Files   -->
  <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script src="{{ asset('assets/js/material-dashboard.min.js?v=3.2.0') }}"></script>

  <!-- Chart initialization only if charts exist -->
  @if(request()->routeIs('admin.dashboard'))
  <script src="{{ asset('assets/js/plugins/chartjs.min.js') }}"></script>
  <script>
    var ctx = document.getElementById("chart-bars");
    if (ctx) {
      ctx = ctx.getContext("2d");
      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["M", "T", "W", "T", "F", "S", "S"],
          datasets: [{
            label: "Views",
            tension: 0.4,
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            backgroundColor: "#43A047",
            data: [50, 45, 22, 28, 50, 60, 76],
            barThickness: 'flex'
          }, ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: '#e5e5e5'
              },
              ticks: {
                suggestedMin: 0,
                suggestedMax: 500,
                beginAtZero: true,
                padding: 10,
                font: {
                  size: 14,
                  lineHeight: 2
                },
                color: "#737373"
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                color: '#737373',
                padding: 10,
                font: {
                  size: 14,
                  lineHeight: 2
                },
              }
            },
          },
        },
      });
    }

    var ctx2 = document.getElementById("chart-line");
    if (ctx2) {
      ctx2 = ctx2.getContext("2d");
      new Chart(ctx2, {
        type: "line",
        data: {
          labels: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
          datasets: [{
            label: "Sales",
            tension: 0,
            borderWidth: 2,
            pointRadius: 3,
            pointBackgroundColor: "#43A047",
            pointBorderColor: "transparent",
            borderColor: "#43A047",
            backgroundColor: "transparent",
            fill: true,
            data: [120, 230, 130, 440, 250, 360, 270, 180, 90, 300, 310, 220],
            maxBarThickness: 6
          }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            },
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: true,
                drawTicks: false,
                borderDash: [5, 5],
                color: '#e5e5e5'
              },
              ticks: {
                display: true,
                suggestedMin: 0,
                suggestedMax: 500,
                padding: 10,
                color: "#737373",
                font: {
                  size: 14,
                  lineHeight: 2,
                },
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: false,
                drawOnChartArea: false,
                drawTicks: false,
                borderDash: [5, 5]
              },
              ticks: {
                display: true,
                color: '#737373',
                padding: 10,
                font: {
                  size: 14,
                  lineHeight: 2,
                },
              },
            },
          },
        },
      });
    }
  </script>
  @endif

  <!-- Initialize perfect scrollbar -->
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

  <!-- Stack any additional scripts -->
  @stack('scripts')
</body>

</html>