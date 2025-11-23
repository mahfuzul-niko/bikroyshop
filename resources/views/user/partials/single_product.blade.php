@if (!empty($product))
@php
    //$stock_price = $product->single_stock;
    $stock_price = DB::table('product_stocks')
        ->where('product_id', $product->id)
        ->where('variant', '=', null)
        ->where('color', '=', null)
        ->first(['price', 'qty']);
    $sale_text = __('messages.New');

    if ($product->discount_type != 'no') {
        if (
            $product->discount_type == 'flat' &&
            optional($product)->discount_amount > 0
        ) {
            $sale_text =
                '- ' .
                __currency() .
                bnConv('bnComNum', optional($product)->discount_amount);
        } elseif (
            $product->discount_type == 'percentage' &&
            optional($product)->discount_amount > 0
        ) {
            $sale_text =
                '- ' .
                bnConv('bnComNum', optional($product)->discount_amount) .
                '%';
        }
    }

    if ($product->type == 'single' && optional($stock_price)->qty <= 0) {
        $sale_text = __('messages.Out of Stock');
    } else {
        $stock_price = DB::table('product_stocks')
            ->where('product_id', $product->id)
            ->first(['price', 'qty']);

        //$variations = $product->variation_stock;
        //$min_price = $variations->min('price');
        //$max_price = $variations->max('price');
    }
@endphp

<div class="col py-2 rounded product_col border">
    <div class="product__items" style="">
        <div class="product__items--thumbnail">
            <a class="product__items--link px-2"
                href="{{ route('single.product', [$product->id, Str::slug($product->title)]) }}">
                <img class="product__items--img product__primary--img product_img border-radius-10"
                    src="{{ asset('images/product/' . $product->thumbnail_image) }}"
                    alt="{{ $product->title }}">
            </a>
            <div class="product__badge">
                <span
                    class="product__badge--items sale">{{ $sale_text }}</span>
            </div>
        </div>

        <div class="product__items--content text-center">
            <h5 class="product__items--content__title px-2"><a
                    href="{{ route('single.product', [$product->id, Str::slug($product->title)]) }}">
                    {{ __translate(Str::limit($product->title, 18), Str::limit($product->bn_title, 18)) }}
                </a></h5>
            <div class="product__items--price">

                @if ($product->discount_type != 'no')
                <?php
                if ($product->discount_type == 'flat') {
                    $old_price = optional($stock_price)->price + optional($product)->discount_amount;
                } elseif ($product->discount_type == 'percentage') {
                    $discount_amount_tk = (optional($product)->discount_amount * optional($stock_price)->price) / 100;
                    $old_price = optional($stock_price)->price + $discount_amount_tk;
                }
                
                ?>                                                        
                <span
                    class="old__price">{{ __currency() . bnConv('bnComNum', $old_price) }}</span>

                <span class="price__divided"></span>
            @endif


                <span class="current__price">
                    {{ __currency() . bnConv('bnComNum', optional($stock_price)->price) }}
                </span>

            </div>


            <ul class="product__items--action">
                <li class="product__items--action__list text-center d-flex align-items-center justify-content-center">
                    @if ($product->type == 'single')
                        @if (optional($stock_price)->qty > 0)
                            {{-- Add to cart --}}
                            <button
                                class="product__items--action__btn add__to--cart  bg-add-to-cart bg-success border-success btn-lg"
                                onclick="addToCart({{ $product->id }}, 'only', 'cart')" type="button">
                                <span class="text-white custom-font-size">{{ __('messages.Add To Cart') }}</span>
                            </button>
                            {{-- Buy Now --}}
                            <button
                                class="product__items--action__btn buy__now--cart bg-buy-now btn-bg"
                                onclick="addToCart({{ $product->id }}, 'only', 'checkout')"
                                type="button">
                                <span class="custom-font-size">{{ __('Buy Now') }}</span>
                            </button>
                        @else
                            {{-- Out of Stock --}}
                            <a class="product__items--action__btn add__to--cart d-flex align-items-center justify-content-center"
                                style="background-color: var(--logo-color);color:white"
                                href="javascript:void(0)">
                                <span
                                    class="add__to--cart__text custom-font-size">{{ __('messages.Out of Stock') }}</span>
                            </a>
                        @endif
                    @else
                        {{-- Select Product --}}
                        <a class="product__items--action__btn add__to--cart"
                            style="background-color: var(--logo-color);color:white"
                            {{-- onclick="quick_view({{ $product->id }})" --}}
                            href="{{ route('single.product', [$product->id, Str::slug($product->title)]) }}">
                            <span
                                class="add__to--cart__text">{{ __('messages.Order Now') }}</span>
                        </a>
                    @endif
                </li>
            </ul>
        </div>
    </div>
</div>
@endif
{{-- single product end --}}