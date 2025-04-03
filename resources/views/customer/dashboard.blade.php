
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        <a href="{{route('customer-report')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <div class="container-fluid">

                        <!-- Welcome Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info" role="alert">
                                    Welcome, {{ auth()->user()->name }}!
                                </div>
                            </div>
                            
                        </div>
                    
                        <!-- Trip History and Statistics -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card shadow mb-4">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-primary">Trip History</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <li class="list-group-item">Trip 1: Pickup - Location A, Dropoff - Location B, Date: 01/04/2025</li>
                                            <li class="list-group-item">Trip 2: Pickup - Location C, Dropoff - Location D, Date: 02/04/2025</li>
                                            <li class="list-group-item">Trip 3: Pickup - Location E, Dropoff - Location F, Date: 03/04/2025</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                    
                            <div class="col-md-6">
                                <div class="card shadow mb-4">
                                    <div class="card-header">
                                        <h6 class="m-0 font-weight-bold text-success">Trip Statistics</h6>
                                    </div>
                                    <div class="card-body">
                                        <p>Total Trips: 3</p>
                                        <p>Total Distance: 150 km</p>
                                        <p>Total Fare: KES 900</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Transaction Details -->
                        <div class="card shadow mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-info">Transaction Details</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>Date</th>
                                            <th>Amount (KES)</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#12345</td>
                                            <td>01/04/2025</td>
                                            <td>KES 100</td>
                                            <td><span class="badge badge-success">Completed</span></td>
                                        </tr>
                                        <tr>
                                            <td>#12346</td>
                                            <td>02/04/2025</td>
                                            <td>KES 200</td>
                                            <td><span class="badge badge-danger">Failed</span></td>
                                        </tr>
                                        <tr>
                                            <td>#12347</td>
                                            <td>03/04/2025</td>
                                            <td>KES 300</td>
                                            <td><span class="badge badge-warning">Pending</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    
                        <!-- Quick Actions -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <a href="{{ route('bookings.my') }}" class="btn btn-primary btn-block">
                                    <i class="fas fa-car"></i> View Trips
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn btn-success btn-block">
                                    <i class="fas fa-credit-card"></i> View Payments
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="#" class="btn btn-warning btn-block">
                                    <i class="fas fa-headset"></i> Contact Support
                                </a>
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