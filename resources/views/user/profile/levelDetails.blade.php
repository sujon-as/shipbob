@extends('user.layout.master')
@section('content')
    <style>
        .level-container {
            {{--background-image: url('{{ asset("assets/images/partials/background.png") }}');--}}
            background-image: url('{{ $settings->vip_bg_image ? asset($settings->vip_bg_image) : asset("assets/images/partials/background.png") }}');
        }
    </style>
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="level-container p-3">
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

        <div>
            <p class="mb-3">
                <small class="text-white">{!! $level->vipDetails->upgrade_text ?? '' !!}</small>
            </p>
            <div
                class="progress"
                role="progressbar"
                aria-label="Example 1px high"
                aria-valuenow="{{ $level->vipDetails->progress_in_percentage ?? 25 }}"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 5px"
            >
                <div class="progress-bar" style="width: {{ $level->vipDetails->progress_in_percentage ?? 25 }}%"></div>
            </div>
            <div class="d-flex justify-content-end mt-2">
                {!! $level->vipDetails->showing_amount_text ?? '' !!}
            </div>
            <div>
                {!! $level->vipDetails->authority_text ?? '' !!}
            </div>
        </div>

        <!-- <div
          class="position-relative rounded-2 bg-secondary text-white bg-gradient"
        >
          <div class="py-4 px-3">
            <div>
              <h2 class="text-center">VIP 1</h2>
              <p class="text-center">Mitgliedschaft starte</p>
            </div>
          </div>
          <div class="position-absolute top-0 start-0">
            <h6 class="p-3">My Level</h6>
          </div>
        </div>
        <div
          class="position-relative mt-3 rounded-2 bg-secondary text-white bg-gradient"
        >
          <div class="py-4 px-3">
            <div>
              <h2 class="text-center">VIP 1</h2>
              <p class="text-center">Mitgliedschaft starte</p>
            </div>
          </div>
          <div class="position-absolute top-0 start-0">
            <h6 class="p-3">My Level</h6>
          </div>
        </div> -->
    </section>
    <!-- hero section end -->
@endsection
