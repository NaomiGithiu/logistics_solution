
@include('partials.header')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('partials.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('partials.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Welcome, {{ Auth::user()->name }}!</h1>
                        <a href="{{route('earnings')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <div class="row">
                        
                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h4 class="card-title">Assigned Trips</h4>
                                    <p class="card-text display-4">{{ $assignedTrips }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-white bg-success mb-3">
                                <div class="card-body">
                                    <h4 class="card-title">Completed Trips</h4>
                                    <p class="card-text display-4">{{ $completedTrips }}</p>
                                </div>
                            </div>
                        </div>
                
                        <div class="col-md-4">
                            <div class="card text-white bg-danger mb-3">
                                <div class="card-body">
                                    <h4 class="card-title">Canceled Trips</h4>
                                    <p class="card-text display-4">{{ $canceledTrips }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container_fluid">
                        <h2 class="mb-4 text-primary">Earnings Summary</h2>
                    
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body bg-light">
                                <h5 class="mb-0">Total Earnings: 
                                    <strong class="text-success">Ksh {{ number_format($totalEarnings, 2) }}</strong>
                                </h5>
                            </div>
                        </div>
                    
                        <h4 class="mt-4 text-secondary">Completed Trips</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover align-middle shadow-sm">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Trip ID</th>
                                        <th>Pickup Location</th>
                                        <th>Dropoff Location</th>
                                        <th>Weight (kg)</th>
                                        <th>Fare (Ksh)</th>
                                        <th>Completed On</th>
                                    </tr>
                                </thead>
                                <tbody class="table-light">
                                    @foreach($completedBookings as $booking)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $booking->id }}</td>
                                            <td>{{ $booking->pickup_location }}</td>
                                            <td>{{ $booking->dropoff_location }}</td>
                                            <td class="text-center">{{ $booking->weight }}</td>
                                            <td class="text-success fw-bold"> {{ number_format($booking->estimated_fare, 2) }}</td>
                                            <td class="text-center">{{ $booking->updated_at->format('d M Y, H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>                            
                        </div>
                    </div>
                    

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            @include('partials.footer')
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <form action="{{route('logout')}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-secondary">Logout</button>
                        </form>
                    </div>
            </div>
        </div>
    </div>

  @include('partials.javascript')

</body>

</html>