<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>CBE Bets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40; /* Dark green background color */
            color: #fff; /* Text color */
            min-height: 100vh; /* Ensure the body takes up at least the full height of the viewport */
            display: flex;
            flex-direction: column;
        }

        .nav-item {
            margin-right: 10px; /* Adjust the value as needed */
        }

        main {
            flex-grow: 1; /* Allow main content to grow to fill remaining space */
        }

        footer {
            flex-shrink: 0; /* Prevent footer from shrinking */
        }

        /* Override navbar link colors */
        .navbar-nav .nav-link {
            color: #fff; /* Text color for navbar links */
        }
        .navbar-nav .nav-link.active {
            color: blue; /* Set active color to blue */
        }

        /* Override navbar link hover colors */
        .navbar-nav .nav-link:hover {
            color: #ccc; /* Text color for navbar links on hover */
        }

        /* Reduce logo size for smaller screens */
        @media (max-width: 576px) {
            .navbar-brand img {
                width: 80px;
            }
        }

        /* Adjust alignment for deposit button and ETB label */
        .deposit-button {
            display: flex;
            align-items: center;
        }

        /* Style for dropdown menu */
        .dropdown-menu {
            background-color: #343a40;
            border: none;
            border-radius: 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            border-right: 1px solid #ffffff; /* Right border */
        }

        .dropdown-menu a.dropdown-item {
            color: #fff;
        }

        .dropdown-menu a.dropdown-item:hover {
            background-color: #495057;
        }

        .dropdown-menu hr.dropdown-divider {
            border-top-color: #adb5bd;
        }

        .dropdown-menu button.dropdown-item {
            color: #fff;
            background-color: transparent;
            border: none;
        }

        .dropdown-menu button.dropdown-item:hover {
            background-color: #495057;
        }

        .dropdown-menu-container {
 
            margin-top: 1%; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: Add shadow */
        }

        .nav-link.dropdown-toggle {
            color: #fff;
        }

        .dropdown-menu {
            background-color: #343a40; /* Dark green background */
        }

        .dropdown-item {
            color: #fff;
        }

        .dropdown-item:hover {
            background-color: #495057; /* Dark gray background on hover */
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-dark">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">
                    <img src="betting_site_logo.jpg" alt="Logo" width="100" height="60">
                </a>

                <div id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <div class="navbar-nav me-auto">
                         <!-- Update the Home link -->
                         <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        <!-- Add the About link -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                        </li>
                       </div>
                    </ul>
                </div>

                @if (Route::has('login'))
                    <ul class="navbar-nav ml-auto align-items-center">
                        <div class="navbar-nav me-auto">
                            @auth
                                @if (Auth::check())
                                    @php
                                        $totalDepositedAmount = App\Models\Transaction::where('user_id', Auth::id())->sum('amount');
                                    @endphp
                                    <li class="nav-item">
                                        <div class="deposit-button">
                                            <button class="btn btn-warning">{{ $totalDepositedAmount }} ETB</button>
                                            {{-- <a class="nav-link btn btn-warning text-dark hidden" style="padding: 0.375rem 0.75rem; margin-left: 5px;" href="{{ route('deposit') }}">Deposit</a> --}}
                                        </div>
                                    </li>
                                    <div class="dropdown-menu-container">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle btn btn-outline-light" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-user-circle me-2"></i> <!-- Profile icon -->
                                                {{ Auth::user()->name }}
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <li><a class="dropdown-item" href="{{ route('withdraw.options') }}">Withdraw</a></li>

                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item">Logout</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </li>
                                    </div>
                                @endif
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                                    </li>
                                @endif
                            @endauth
                        </div>
                    </ul>
                @endif
            </div>
        </nav>
    </header>
 
    <main class="">
        @yield('content')
    </main>

    <footer class="footer py-3 bg-dark">
        <div class="container text-center">
            <span class="text-light">© 2024 CBE Betting All rights reserved.</span>
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0-alpha1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
