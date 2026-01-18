@extends('user.layout.master')
@section('content')
    <section class="py-5 mb-5">
        <div class="px-4 px-lg-5">
            @if(count($products) > 0)
                @foreach($products as $product)
                    @php
                        $isOrdered = in_array($product->id, $orderedProductIds);
                    @endphp

                    <div class="p-4 bg-secondary-subtle rounded-2 mb-3">
                        <h6 class="fw-semibold">{{ $product->name ?? '' }}</h6>
                        <hr />
                        <div>
                            <img src="{{ $product->file ? asset($product->file) : '' }}" alt="{{ $product->name ?? '' }}" class="product_img" />
                            <p class="mt-2"><small>Price: ৳ {{ $product->price ?? '' }}</small></p>
                            <p><small>Commission: ৳ {{ $product->commission ?? '' }}</small></p>
                            @if($settings->min_ratings > 0)
                                <!-- ⭐ Rating Input -->
                                <div class="rating mb-3">
                                    <label class="d-block fw-bold mb-1">{{ $settings->rating_text ?? 'Product Rating' }}:</label>

                                    <div class="stars">
                                        <i class="fa fa-star star" data-value="1"></i>
                                        <i class="fa fa-star star" data-value="2"></i>
                                        <i class="fa fa-star star" data-value="3"></i>
                                        <i class="fa fa-star star" data-value="4"></i>
                                        <i class="fa fa-star star" data-value="5"></i>
                                    </div>

                                    <input type="hidden" name="rating" id="ratingInput">
                                    <small class="text-danger d-none" id="ratingError">Please give a rating.</small>
                                </div>
                            @endif

                            <p><small>Time: {{ now()->toDayDateTimeString() }}</small></p>
                        </div>

                        <form method="POST" action="{{ route('order.product') }}" class="orderForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="is_trial_task" value="{{ $is_trial_task }}">
                            <input type="hidden" name="task_id" value="{{ $task_id }}">
                            <input type="hidden" name="ratingCount" id="ratingCountInput">

                            <button
                                type="submit"
                                class="btn w-100 btn-dark submit-btn" {{ $isOrdered ? 'disabled' : '' }}
                            >
                                {{ $isOrdered ? 'Already Ordered' : ($settings->order_btn_text ?? 'Place Order') }}
                            </button>

                            <!-- 1st time hidden this button  -->
                            <button type="button" class="btn w-100 btn-secondary waiting-btn d-none" disabled>
                                Please wait...
                                <span class="spinner-border spinner-border-sm"></span>
                                <span class="countdown"></span>
                            </button>
                        </form>
                    </div>
                @endforeach
            @else
                <div class="p-4 bg-secondary-subtle rounded-2 mb-3">
                    <p>Nothing found</p>
                </div>
            @endif
        </div>
    </section>

    <style>
        .product_img {
            max-height: 50% !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* Rating Section */
        .rating {
            margin-top: 10px;
        }

        .rating .stars {
            display: flex;
            flex-direction: row;
            direction: ltr;
            gap: 5px;
        }

        .rating .star {
            font-size: 28px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s, transform 0.2s;
        }

        /* Yellow filled star (only for selected/clicked) */
        .rating .star.filled {
            color: #f39c12 !important;
        }

        /* Hover effect - fill current and previous stars */
        .rating .star:hover {
            color: #f39c12 !important;
            transform: scale(1.2);
        }

        /* Fill all previous stars on hover */
        .rating .stars:hover .star {
            color: #ccc;
        }

        .rating .stars .star:hover ~ .star {
            color: #ccc;
        }

        .rating .stars .star:hover,
        .rating .stars .star.hover-fill {
            color: #f39c12 !important;
        }

        /* Validation message */
        #ratingError {
            font-size: 14px;
        }
    </style>
@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $("#ratingCountInput").val(null);
            // ⭐ Star click
            $(".star").on("click", function () {
                let rating = $(this).data("value");

                // fill stars
                $(".star").removeClass("text-warning");
                $(this).prevAll().addClass("text-warning");
                $(this).addClass("text-warning");

                $("#ratingInput").val(rating);
                $("#ratingError").addClass("d-none");
            });

            $('.orderForm').on('submit', function (e) {
                e.preventDefault();
                @if($settings->min_ratings > 0)
                    const min_rating = {{ $settings->min_ratings ?? 1 }};
                    let rating = $("#ratingInput").val();

                    if (!rating) {
                        e.preventDefault();
                        $("#ratingError").removeClass("d-none");
                        return false;
                    }

                    if (rating && rating < min_rating) {
                        e.preventDefault();
                        alert(`Minimum rating of ${min_rating} stars is required to place an order.`);
                        return false;
                    }
                $("#ratingCountInput").val(rating);
                @endif

                const $form = $(this);
                const $submitBtn = $form.find('.submit-btn');
                const $waitingBtn = $form.find('.waiting-btn');
                const $countdown = $waitingBtn.find('.countdown');

                // prevent multiple clicks
                if ($submitBtn.data('clicked')) return false;
                $submitBtn.data('clicked', true);

                // hide submit, show waiting
                $submitBtn.addClass('d-none');
                $waitingBtn.removeClass('d-none');

                let countdown = 2;
                $countdown.text(`(${countdown}s)`);

                const timer = setInterval(() => {
                    countdown--;
                    $countdown.text(`(${countdown}s)`);

                    if (countdown <= 0) {
                        clearInterval(timer);
                        $countdown.text('');
                        // finally submit
                        $form.off('submit').submit();
                    }
                }, 1000);
            });
        });
    </script>

@endpush
