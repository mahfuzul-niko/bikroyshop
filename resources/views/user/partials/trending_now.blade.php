@if ($trending_products->count() > 0)
<style>
    .slick-track, .slick-list {
        width: 100%;
    }
    .slick-prev, .slick-next {
        z-index: 5 !important;
    }

    /* Fire animation styles */
    .deal-container {
    text-align: left;
    font-family: Arial, sans-serif;
    position: relative;
    padding: 10px 0;
}

.deal-text {
    font-size: 2.5rem;
    font-weight: bold;
    text-transform: uppercase;
    color: #FF4500;
    animation: blink 1.2s infinite alternate;
    position: relative;
    z-index: 2;
}

@keyframes blink {
    0% {
        color: #FF4500;
    }
    33% {
        color: #FFD700;
    }
    66% {
        color: #FF2400;
    }
    100% {
        color: #FEDA60;
    }
}

</style>

<section class="product__section section--padding pt-0 container-fluid" style="padding-bottom: 3rem !important;">
    <div class="py-5">
        <div class="inner">
            <div class="section__heading mb-2 d-flex px-lg-1 px-md-4 px-4">
                <h2 class="section__heading--style2 flex-grow-1 fs-3">
                    <div class="deal-container">
                        <div class="fire-bg"></div>
                        <div class="deal-text">Hot Deals</div>
                    </div>
                </h2>
                <div class="btn_custom mb-2">
                    <a class="rounded shop_more_btn fs-5" href="{{ route('products.individual.group', ['slug' => 'trending-now']) }}">
                        {{ __('messages.Shop More') }}
                        <svg class="primary__btn--arrow__icon" xmlns="http://www.w3.org/2000/svg" width="20.2" height="12.2" viewBox="0 0 6.2 6.2">
                            <path d="M7.1,4l-.546.546L8.716,6.713H4v.775H8.716L6.554,9.654,7.1,10.2,9.233,8.067,10.2,7.1Z" transform="translate(-4 -4)" fill="currentColor"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Trending Products Section -->
            <div class="product__section--inner">
                <div class="row px-4 row-cols-xxl-6 row-cols-xl-6 row-cols-lg-3 row-cols-md-2 row-cols-2 mb--n30 responsiveSlider1">
                    @foreach($trending_products->slice(0, 7) as $product)
                        @include('user.partials.product')
                    @endforeach
                </div>
            </div>

            <div class="product__section--inner mt-5 mb-5">
                <div class="row px-4 row-cols-xxl-6 row-cols-xl-6 row-cols-lg-3 row-cols-md-2 row-cols-2 mb--n30 responsiveSlider1">
                    @foreach($trending_products->slice(7, 7) as $product) {{-- Fixed the starting index --}}
                        @include('user.partials.product')
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('.responsiveSlider1').slick({
            infinite: true,
            arrows: false,
            speed: 300,
            slidesToShow: 6,
            autoplay: true,
            autoplaySpeed: 2000,
            prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-chevron-left"></i></button>',
            nextArrow: '<button type="button" class="slick-next"><i class="fa fa-chevron-right"></i></button>',
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 6,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    });
</script>
@endif
