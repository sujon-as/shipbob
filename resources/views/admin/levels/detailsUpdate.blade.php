@extends('admin_master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Vip Details</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{URL::to('/vips')}}">Vip Details
                                </a></li>
                            <li class="breadcrumb-item active">Vip Details</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="card card-success">
                <div class="card-header">
                    <h3>Update VIP Details for: {{ $level->title }}</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('vips.updateDetails', $level->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="progress_in_percentage">Progress in Percentage <span class="required">*</span></label>
                                    <input
                                        type="text"
                                        name="progress_in_percentage"
                                        class="form-control numericInput"
                                        id="progress_in_percentage"
                                        placeholder="Progress in Percentage"
                                        required=""
                                        value="{{ old('progress_in_percentage', ($vipDetails && $vipDetails->progress_in_percentage !== null) ? $vipDetails->progress_in_percentage : 0) }}"
                                    >
                                    @error('progress_in_percentage')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="upgrade_text">Upgrade Text <span class="required">*</span></label>
                                    <textarea class="form-control description" required="" name="upgrade_text">{!! old('upgrade_text',$vipDetails->upgrade_text ?? '') !!}</textarea>
                                    @error('upgrade_text')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="showing_amount_text">Showing Amount Text <span class="required">*</span></label>
                                    <textarea class="form-control description" required="" name="showing_amount_text">{!! old('showing_amount_text',$vipDetails->showing_amount_text ?? '') !!}</textarea>
                                    @error('showing_amount_text')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="authority_text">Authority Text <span class="required">*</span></label>
                                    <textarea class="form-control description" required="" name="authority_text">{!! old('authority_text',$vipDetails->authority_text ?? '') !!}</textarea>
                                    @error('authority_text')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-success">Save Changes</button>
                        </div>
                    </div>
                    <!-- /.card-body -->
                </form>
            </div>
        </section>
    </div>
@endsection
