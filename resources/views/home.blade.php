@extends('layouts.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (visible on desktop screens) -->
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-none d-md-block bg-dark sidebar">
            <div id="leftbar" class="leftbar">
                <div class="top p-1 d-flex">
                    <button type="button" class="btn-custom me-1">
                        <i class="las la-podcast"></i> Live
                    </button>
                    <button type="button" class="btn-custom light">
                        <i class="las la-meteor"></i> Upcoming
                    </button>
                </div>
                <ul class="main">
                    <li>
                        <a>
                            <i aria-hidden="true" class="far fa-futbol"></i><span>Sports</span>
                        </a>
                    </li>
                    <li>
                        <a data-bs-toggle="collapse" href="#collapse1" role="button" aria-expanded="true" aria-controls="collapseExample" class="dropdown-toggle">
                            <i aria-hidden="true" class="far fa-futbol"></i> Soccer
                            <span class="count"><span class="font-italic">(0)</span></span>
                        </a>
                        <div id="collapse1" class="collapse" style="">
                            <ul>
                                <li>
                                    <a class="sidebar-link">
                                        <i aria-hidden="true" class="far fa-hand-point-right"></i> English Premier league
                                    </a>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <a class="sidebar-link">
                                        <i aria-hidden="true" class="far fa-hand-point-right"></i> Spanish La Liga
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a data-bs-toggle="collapse" href="#collapse10" role="button" aria-expanded="true" aria-controls="collapseExample" class="dropdown-toggle">
                            <i aria-hidden="true" class="far fa-futbol"></i> Live Betting
                            <span class="count"><span class="font-italic">(0)</span></span>
                        </a>
                        <div id="collapse10" class="collapse" style="">
                            <ul>
                                <!-- Add sub-list items for Live Betting here -->
                                <li>
                                    <a class="sidebar-link">
                                        <i aria-hidden="true" class="far fa-hand-point-right"></i> English Premier league
                                    </a>
                                </li>
                                <li>
                                    <a class="sidebar-link">
                                        <i aria-hidden="true" class="far fa-hand-point-right"></i> Spanish La Liga
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <!-- Other sidebar items -->
                </ul>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="row">
                <div class="col-md-12">
                    <!-- Carousel with padding -->
                    <div class="p-3" style="height: 50vh;">
                        <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <!-- First slide -->
                                <div class="carousel-item active">
                                    <img src="types-of-sports-betting.png" style="width: 100%; height: auto; display: inline;">
                                </div>
                                <!-- Second slide -->
                                <div class="carousel-item">
                                    <img class="image_main" src="as-sports-betting.webp" style="width: 100%; height: auto; display: inline;">
                                </div>
                                <!-- Third slide -->
                                <div class="carousel-item">
                                    <img src="sports-betting-business.jpg" style="width: 100%; height: auto; display: inline;">
                                </div>
                            </div>
                        </div>
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                              <a class="nav-link active" aria-current="page" href="#">Sports</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#">Live</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#">Soccer</a>
                            </li>
                            <li class="nav-item">
                              <a class="nav-link" href="#" tabindex="-1" aria-disabled="true">Virtuals</a>
                            </li>
                          </ul>
                    </div>
                    <!-- End Carousel -->
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div>
                        {{-- <h1>Welcome to Home Page</h1> --}}
                        <!-- Your home page content here -->
                    </div>
                </div>
            </div>
        </main>
    </div>
  
    </nav>
   
</div>

<nav class="navbar fixed-bottom navbar-expand-lg navbar-dark bg-dark d-md-none">
    <div class="container-fluid">
        <ul class="nav mx-auto d-flex">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <i class="fas fa-home"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link">
                    <i class="fas fa-play"></i> Live
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link">
                    <i class="fas fa-vr-cardboard"></i> Virtuals
                </a>
            </li>
        </ul>
    </div>
</nav>

@endsection

<script>
    // Enable Bootstrap dropdown functionality
    var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
        return new bootstrap.Dropdown(dropdownToggleEl);
    });
</script>

<style>
    .sidebar {
        height: 100vh;
        position: sticky;
        top: 0;
    }

    .sidebar ul {
        list-style-type: none;
        padding-left: 0; /* Remove default left padding */
    }

    .leftbar {
        height: 100%;
        overflow-y: auto;
    }

    .leftbar .top button {
        margin-right: 10px;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #007bff; /* Change to your desired color */
        color: #fff;
        font-size: 16px;
    }

    .leftbar .top button.light {
        background-color: #28a745; /* Change to your desired color */
    }

    .leftbar .top button:hover {
        background-color: #0056b3; /* Change to your desired color */
    }

    .main li a {
        display: block;
        color: #fff;
        text-decoration: none;
        padding: 10px;
        border-radius: 5px;
    }

    .main li a:hover {
        background-color: #555; /* Change to your desired hover color */
    }

    /* Additional styles for sidebar content */

    /* Hide sidebar on mobile screens */
    @media (max-width: 992px) {
        .sidebar {
            display: none;
        }
    }
    .navbar .nav-link {
    color: white; /* Set default color for inactive links */
}

.navbar .nav-link.active {
    color: blue; /* Set color for active link */
}
</style>
