@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Vip Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/vips')}}">Vip Settings
                                </a></li>
                        <li class="breadcrumb-item active">Vip Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Vip Settings</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ url('vp/settings-app') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="vip_bg_image">VIP Background Image <span class="required">*</span></label>
                                <input
                                    name="vip_bg_image"
                                    type="file"
                                    id="vip_bg_image"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                    data-default-file="{{ URL::to($setting ? $setting->vip_bg_image : '') }}"
                                />
                                @error('vip_bg_image')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="vip_mgs">VIP Error Message <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="vip_mgs"
                                    class="form-control"
                                    id="vip_mgs"
                                    placeholder="VIP Error Message"
                                    value="{{ old('vip_mgs', $setting ? $setting->vip_mgs : "") }}"
                                >
                                @error('vip_mgs')
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
