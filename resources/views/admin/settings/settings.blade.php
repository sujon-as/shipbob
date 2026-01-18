@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Settings</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/settings')}}">Settings
                                </a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Settings</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ url('settings-app') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="company_name">Company name</label>
                                <input type="text" name="company_name" class="form-control" id="company_name"
                                    placeholder="Company name"  value="{{old('company_name', ($setting && $setting->company_name) ? $setting->company_name : "")}}">
                                @error('company_name')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telegram_group_link">Telegram group Link <span class="required">*</span></label>
                                <input type="text" name="telegram_group_link" class="form-control numericInput" id="telegram_group_link"
                                       placeholder="Telegram group Link"  value="{{old('telegram_group_link', $setting ? $setting->telegram_group_link : "")}}">
                                @error('telegram_group_link')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="company_logo">Company Logo <span class="required">*</span></label>
                                <input
                                    name="company_logo"
                                    type="file"
                                    id="company_logo"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                    data-default-file="{{ URL::to($setting ? $setting->company_logo : '') }}"
                                />
                                @error('company_logo')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="daily_task_limit">Minimum Task Limit <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="daily_task_limit"
                                    class="form-control numericInput"
                                    id="daily_task_limit"
                                    placeholder="Minimum Task Limit"
                                    required=""
                                    value="{{ old('daily_task_limit', ($setting && $setting->daily_task_limit !== null) ? $setting->daily_task_limit : '') }}"
                                >
                                @error('daily_task_limit')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="rtt_trial_balance">
                                    Round Trial Task Balance
                                    <span class="required">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="rtt_trial_balance"
                                    class="form-control numericInput"
                                    id="rtt_trial_balance"
                                    placeholder="Round Trial Task Balance"
                                    required=""
                                    value="{{ old('rtt_trial_balance', ($setting && !empty($setting->rtt_trial_balance)) ? $setting->rtt_trial_balance : 0) }}"
                                >
                                @error('rtt_trial_balance')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="is_site_active">Is Site Active <span class="required">*</span></label>
                                <select class="form-control select2bs4" name="is_site_active" id="is_site_active" required="">
                                    <option value="" selected="" disabled="">Select One</option>
                                    <option value="Active" @if($setting->is_site_active === 'Active') selected @endif>Active</option>
                                    <option value="Inactive" @if($setting->is_site_active === 'Inactive') selected @endif>Inactive</option>
                                </select>
                                @error('is_site_active')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="min_cash_out_amount">Minimum Cashout Amount (BDT) <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="min_cash_out_amount"
                                    class="form-control numericInput"
                                    id="min_cash_out_amount"
                                    placeholder="Minimum Cashout Amount (BDT)"
                                    required=""
                                    value="{{ old('min_cash_out_amount', ($setting && $setting->min_cash_out_amount !== null) ? $setting->min_cash_out_amount : 0) }}"
                                >
                                @error('min_cash_out_amount')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reserved_amount_text">Reserved Amount Text <span class="required">*</span></label>
                                <input type="text" name="reserved_amount_text" class="form-control" id="reserved_amount_text"
                                       placeholder="Reserved Amount Text"  value="{{old('reserved_amount_text', $setting ? $setting->reserved_amount_text : "")}}">
                                @error('reserved_amount_text')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="trail_balance_text">Trail Balance Text <span class="required">*</span></label>
                                <input type="text" name="trail_balance_text" class="form-control" id="trail_balance_text"
                                       placeholder="Trail Balance Text"  value="{{old('trail_balance_text', $setting ? $setting->trail_balance_text : "")}}">
                                @error('trail_balance_text')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="maintain_title_text">Maintain Title Text <span class="required">*</span></label>
                                <input type="text" name="maintain_title_text" class="form-control" id="maintain_title_text"
                                       placeholder="Maintain Title Text"  value="{{old('maintain_title_text', $setting ? $setting->maintain_title_text : "")}}">
                                @error('maintain_title_text')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="maintain_desc_text">Maintain Description Text <span class="required">*</span></label>
                                <input type="text" name="maintain_desc_text" class="form-control" id="maintain_desc_text"
                                       placeholder="Maintain Description Text"  value="{{old('maintain_desc_text', $setting ? $setting->maintain_desc_text : "")}}">
                                @error('maintain_desc_text')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="min_ratings">Minimum Ratings <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="min_ratings"
                                    class="form-control"
                                    id="min_ratings"
                                    placeholder="Maintain Description Text"
                                    value="{{ old('min_ratings', $setting ? $setting->min_ratings : "") }}"
                                >
                                @error('min_ratings')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="order_success_mgs_1">Order Success Message 1 <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="order_success_mgs_1"
                                    class="form-control"
                                    id="order_success_mgs_1"
                                    placeholder="Order Success Message 1"
                                    value="{{ old('order_success_mgs_1', $setting ? $setting->order_success_mgs_1 : "") }}"
                                >
                                @error('order_success_mgs_1')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="order_success_mgs_2">Order Success Message 2 <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="order_success_mgs_2"
                                    class="form-control"
                                    id="order_success_mgs_2"
                                    placeholder="Order Success Message 2"
                                    value="{{ old('order_success_mgs_2', $setting ? $setting->order_success_mgs_2 : "") }}"
                                >
                                @error('order_success_mgs_2')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="order_btn_text">Order Button Text <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="order_btn_text"
                                    class="form-control"
                                    id="order_btn_text"
                                    placeholder="Order Button Text"
                                    value="{{ old('order_btn_text', $setting ? $setting->order_btn_text : "") }}"
                                >
                                @error('order_btn_text')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="rating_text">Rating Text <span class="required">*</span></label>
                                <input
                                    type="text"
                                    name="rating_text"
                                    class="form-control"
                                    id="rating_text"
                                    placeholder="Rating Text"
                                    value="{{ old('rating_text', $setting ? $setting->rating_text : "") }}"
                                >
                                @error('rating_text')
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
