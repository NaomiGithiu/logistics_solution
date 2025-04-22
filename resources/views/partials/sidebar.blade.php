<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Logistic Solution</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{route('home')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Interface
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
            aria-expanded="true" aria-controls="collapseTwo">
            <i class="fas fa-fw fa-cog"></i>
            <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Custom Components:</h6>
                <a class="collapse-item" href="buttons.html">Buttons</a>
                <a class="collapse-item" href="cards.html">Cards</a>
            </div>
        </div>
    </li>

{{-- Admin --}}

@if (auth()->check() && auth()->user()->role == '1')
       
     <div class="dropdown">
        <button class="btn dropdown-toggle text-light " type="button" id="manageTripsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Manage Users
        </button>
        <ul class="dropdown-menu" aria-labelledby="manageTripsDropdown">
            <li>
               <a class="dropdown-item text-dark" href="{{ route('corporates.index') }}">
                    Manage corporate
                </a>

            </li>
            <li>
                <a class="dropdown-item text-dark" href="{{ url('users') }}">
                    <i class="bi bi-person me-2"></i>  <!-- Simple user icon -->
                    Manage Users
                </a>
            </li>
        </ul>
    </div>

   <div class="dropdown">
        <button class="btn dropdown-toggle text-light " type="button" id="manageTripsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Manage Trips
        </button>
        <ul class="dropdown-menu" aria-labelledby="manageTripsDropdown">
            <li>
                <a class="dropdown-item text-dark" href="{{ route('admin.pending') }}">
                    Pending Trips
                </a>
            </li>
            <li>
                <a class="dropdown-item text-dark" href="{{ route('admin.canceledTrips') }}">
                    Canceled Trips
                </a>
            </li>
            <li>
                <a class="dropdown-item text-dark" href="{{ route('assignedTrips') }}">
                    🚖 Assigned Trips
                </a>
            </li>
        </ul>
    </div>

    

    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Addons
    </div>

    <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-folder"></i>
                <span>Roles & Permissions</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a href="{{ route('roles.index') }}"><i class="fa fa-circle-o "></i> Manage Roles</a><br>
                    {{-- <a href="{{ route('permissions.index') }}"><i class="fa fa-circle-o"></i> Manage Permissions</a> --}}
                </div>
            </div>
        </li>

        <div class="dropdown">
            <button class="btn dropdown-toggle text-light " type="button" id="manageTripsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                Reports
            </button>
            <ul class="dropdown-menu" aria-labelledby="manageTripsDropdown">
            
                <li>
                    <a class="nav-link" href="{{route('incomereport')}}">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Income Reports</span>
                    </a>

                    <a class="nav-link" href="{{route('tripreport')}}">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Trips Report</span>
                    </a>

                    {{-- <a class="nav-link" href="{{route('report')}}">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Users Report</span>
                    </a> --}}
                </li>
            </ul>
        </div>

        {{-- Driver --}}

@elseif(auth()->check() && auth()->user()->role=='3')

    <a class="dropdown-item text-light fw-bold" href="{{ route('trips.accepted') }}">
        🚖 Assigned Trips
    </a>
    
    <a class="dropdown-item text-light fw-bold" href="{{ route('trips.completed') }}">
        🚖 Completed Trips</a>

        {{-- customer --}}

@elseif(auth()->check() && auth()->user()->role=='4')
    <a class="dropdown-item text-light fw-bold" href="{{ route('booking') }}">
        Booking
    </a>

    <a href="{{ route('bookings.my') }}" class="dropdown-item text-light fw-bold">
        📋 My Bookings
    </a>
  
@else

<li>
    <a class="dropdown-item text-light" href="{{ url('users') }}">
        <i class="bi bi-person me-2"></i>  <!-- Simple user icon -->
        Manage Users
    </a>
</li>

<div class="dropdown">
    <button class="btn dropdown-toggle text-light " type="button" id="manageTripsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        Manage Trips
    </button>
    <ul class="dropdown-menu" aria-labelledby="manageTripsDropdown">
        <li>
            <a class="dropdown-item text-dark" href="{{ route('admin.pending') }}">
                Pending Trips
            </a>
        </li>
        <li>
            <a class="dropdown-item text-dark" href="{{ route('admin.canceledTrips') }}">
                Canceled Trips
            </a>
        </li>
        <li>
            <a class="dropdown-item text-dark" href="{{ route('assignedTrips') }}">
                🚖 Assigned Trips
            </a>
        </li>
    </ul>

    <li>
        <a class="dropdown-item text-light fw-bold" href="{{ route('bookings.bulk') }}">
            Bulk Booking
        </a>
    </li>

    <ul class="dropdown-menu" aria-labelledby="manageTripsDropdown">
        <li>
            <a class="dropdown-item text-dark" href="{{ route('bookings.pending-approvals') }}">
                Pending Trips
            </a>
        </li>
       
    </ul>
</div>


@endif

    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="tables.html">
            <i class="fas fa-fw fa-table"></i>
            <span>Tables</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>

<!-- Bootstrap CSS (via CDN) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS (via CDN) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
