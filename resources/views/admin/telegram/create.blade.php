@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Telegram Numbers</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/telegram-check')}}">All Telegram Numbers</a></li>
                        <li class="breadcrumb-item active">Add Telegram Numbers</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Telegram Numbers</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('telegram.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Enter comma separated numbers:</label>
                            <textarea name="phones" class="form-control" rows="3" placeholder="+88017..., +88016..., +88019..."></textarea>
                        </div>
                    </div>

                    <div class="form-group w-100 py-2">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    </div>
                    <!-- /.card-body -->
            </form>
        </div>
    </section>
</div>

@endsection

@push('scripts')

  <script>

  </script>

@endpush
