@if ($product_with_categories->isNotEmpty())
    <style>
        .slick-prev:before {
            display: none !important;
        }

        .slick-next:before {
            display: none !important;
        }

        .slick-prev,
        .slick-next {
            background-color: #fff;
            border: none;
            color: #333;
            font-size: 20px;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            z-index: 9999;
        }

        .slick-prev:hover,
        .slick-next:hover {
            background-color: #333;
            color: #fff;
        }

        .slick-prev {
            left: 0px;
        }

        .slick-next {
            right: 0px;
        }

        .btn-bg {
            background-color: #FF4500;
            border: #FF4500
        }

        .custom-font-size {
            font-size: 11px;
        }


        /* custom css */
        .product__items--action__btn.buy__now--cart {
            margin-left: 1px !important;
            width: 100% !important;
            border-radius: 0px !important;
        }


        .product__items--action__btn.add__to--cart {
            width: 100% !important;
            border-radius: 0px !important;
        }
    </style>
    @foreach ($product_with_categories as $pwc)
        @php
            // Fetch the product ids for the category
            $product_with_categories_ids = App\Models\ProductWithCategory::where('category_id', $pwc->category_id)
                ->select('product_id')
                ->get();

            // Get products based on the category ids
            $products = App\Models\Product::whereIn('id', $product_with_categories_ids)
                ->limit(12) // Limit the number of products to 8
                ->get();
        @endphp

        @if ($products->isNotEmpty())
            @php
                // Fetch the category for each product_with_category
                $category = App\Models\Category::where([
                    'id' => $pwc->category_id,
                    'parent_id' => 0,
                    'is_featured' => 1,
                    'is_active' => 1,
                ])->first();
            @endphp

            @if ($category)
                <!-- Section Start --> 
                <section class="product__section section--padding pt-0" style="padding-bottom: 4rem !important;">
                    <div class="container-fluid">
                        <div class="product__section--inner my-4">
                            <div
                                class="row px-4 row-cols-xl-6 row-cols-lg-3 row-cols-md-2 row-cols-2 mb--n30">
                                @foreach ($products as $product)
                                    @include('user.partials.single_product')
                                @endforeach
                            </div>
                        </div>

                    </div>


                </section>
                <!-- Section End -->
            @endif
        @endif
    @endforeach
@endif

{{-- <script>        
    $('.responsiveSlider2').slick({
    infinite: false,
    speed: 300,
    slidesToShow: 6,
    arrows: true,
    prevArrow: '<button class="slick-prev" type="button"><i class="fa fa-chevron-left"></i></button>',
    nextArrow: '<button class="slick-next" type="button"><i class="fa fa-chevron-right"></i></button>',
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
                slidesToShow: 2,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }
    ]
});
</script> --}}
