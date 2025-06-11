<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SAP System - @yield('title')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">SAP System</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    @auth
                        @role('SuperAdmin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.companies.index') }}">Perusahaan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('superadmin.admins.index') }}">Admin Perusahaan</a>
                            </li>
                        @endrole

                        @role('Admin Perusahaan')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('companyadmin.employees.index') }}">Karyawan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('companyadmin.salaries.index') }}">Gaji</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('companyadmin.leaves.index') }}">Cuti</a>
                            </li>
                        @endrole

                        @role('Karyawan')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employee.leaves.index') }}">Cuti</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('employee.salaries.index') }}">Gaji</a>
                            </li>
                        @endrole
                    @endauth
                </ul>

                <ul class="navbar-nav ml-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('profile.show') }}">Profil</a>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>