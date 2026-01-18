<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="{{ route('admin.download.database') }}"
               class="btn btn-danger mr-2"
               onclick="return confirm('Are you sure you want to download full database?')">
                <i class="fa fa-database"></i> Download Database
            </a>
        </li>

        <!-- Navbar Search -->
        <li class="nav-item">
            <a href="{{url('/logout')}}" class="btn btn-primary font-weight-bold">LOGOUT</a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->
