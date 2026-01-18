@extends('user.layout.master')
@section('content')
    <style>
        .level-container {
            background-image: url('{{ $settings->vip_bg_image ? asset($settings->vip_bg_image) : asset("assets/images/partials/background.png") }}');
        }
    </style>
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="level-container pt-2">
        @if($levels->count() > 0)
            @foreach($levels as $level)
                <a class="text-decoration-none" href="{{ route('user-level-details', ['id' => $level->id]) }}">
                    <div class="position-relative m-2 rounded-2 text-white
                            {{ !$level->bg_image ? 'bg-gradient' : '' }}"
                         style="
                        @if($level->bg_image)
                            background-image: url('{{ asset($level->bg_image) }}');
                            background-size: cover;
                            background-position: center;
                            background-repeat: no-repeat;
                        @endif
                     ">
                        <div class="py-4 px-3">
                            <div>
                                <h2 class="text-center">{{ $level->title ?? '' }}</h2>
                                <p class="text-center">{!! $level->description ?? '' !!}</p>
                            </div>
                        </div>

                        @if($levelId && $level->id === $levelId)
                            <div class="position-absolute top-0 start-0">
                                <h6 class="p-3 bg-success rounded-bottom px-4">My Level</h6>
                            </div>
                        @endif
                    </div>
                </a>
            @endforeach
        @else
            <p class="text-center text-muted">No levels found.</p>
        @endif
    </section>
    <!-- hero section end -->
@endsection
