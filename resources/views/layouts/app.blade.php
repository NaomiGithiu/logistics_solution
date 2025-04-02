
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
                <!-- /.container-fluid -->
                    @yield('content')
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            {{-- @include('partials.footer') --}}
            <!-- End of Footer -->

   

  @include('partials.javascript')

</body>

</html>