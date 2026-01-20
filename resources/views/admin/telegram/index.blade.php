@extends('admin_master')
@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">All Telegram Numbers</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item active">All Telegram Numbers</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All Telegram Numbers</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <a href="{{ route('telegram.create') }}" class="btn btn-primary add-new mb-2">Check Telegram Numbers</a>
                <a href="{{ route('telegram.export') }}" class="btn btn-success add-new mb-2 mx-2">Export</a>
                <div class="fetch-data table-responsive">
                    <table id="product-table" class="table table-bordered table-striped data-table">
                        <thead>
                            <tr>
                                <th>Phone</th>
                                <th>Telegram Exist</th>
                            </tr>
                        </thead>
                        <tbody class="conts">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')

{{--    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>--}}
    <script src="{{asset('custom/pusher.min.js')}}"></script>

    <script>
  	$(document).ready(function(){
  		let id;
  		var productTable = $('#product-table').DataTable({
		        // searching: true,
		        processing: true,
		        serverSide: true,
		        ordering: false,
		        responsive: true,
		        stateSave: true,
		        ajax: {
		          url: "{{ url('/telegram-check') }}",
		        },

		        columns: [
		            {data: 'phone', name: 'phone'},
                    {data: 'has_telegram', name: 'has_telegram'},
		        ],
        });

        // 🔥 Auto refresh every 5 seconds
        // setInterval(function(){
        //     productTable.ajax.reload(null, false);
        // }, 2000);

        // ======= PUSHER REALTIME =======
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            encrypted: true
        });

        var channel = pusher.subscribe("telegram");

        channel.bind("telegram.updated", function(data) {
            productTable.ajax.reload(null, false);

        });

  	});
  </script>

@endpush
