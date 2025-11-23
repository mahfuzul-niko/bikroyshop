@if(!empty($product))
@php
    //$stock_price = $product->single_stock;
    $stock_price = DB::table('product_stocks')->where('product_id', $product->id)->where('variant', '=', null)->where('color', '=', null)->first(['price', 'qty']);
    $sale_text = __('messages.New');

    if($product->discount_type <> 'no') {
        if($product->discount_type == 'flat' && optional($product)->discount_amount>0) {
            $sale_text = '- '.__currency().bnConv('bnComNum',optional($product)->discount_amount);
        }
        else if($product->discount_type == 'percentage' && optional($product)->discount_amount>0) {
            $sale_text = '- '.bnConv('bnComNum',optional($product)->discount_amount)."%";
        }
    }

    if($product->type == 'single' && optional($stock_price)->qty <= 0) {
        $sale_text = __('messages.Out of Stock');
    }
    else {
        $stock_price = DB::table('product_stocks')->where('product_id', $product->id)->first(['price', 'qty']);

        //$variations = $product->variation_stock;
        //$min_price = $variations->min('price');
        //$max_price = $variations->max('price');
    }
@endphp

<div class="col mb-30 py-3 px-0 product_col border">
    <style>
        .product-badge{
            bottom: 0;
            right: 5px;
            position: absolute;
        }
        .background-badge{
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 25%;
            width: 25%;
        }
    </style>
    <div class="product__items">
        <div class="product__items--thumbnail">
            <a class="product__items--link px-2" href="{{ route('single.product', [$product->id, Str::slug($product->title)]) }}">
                <img class="product__items--img product__primary--img product_img border-radius-10" src="{{ asset('images/product/'.$product->thumbnail_image) }}" alt="{{$product->title}}">
            </a>
            <div class="product__badge background-badge text-white right-1" style="background-image: url('{{ asset('frontend/assets/img/product/flash-deal-percentage.png') }}');">
                <span class="fs-6">
                    {{$sale_text}}
                </span>
            </div>
            <div class="product-badge">
                <span class="product__badge--items sale">
                    {{ __currency().bnConv('bnComNum',optional($stock_price)->price) }}
                </span>
            </div>
        </div>

      
    </div>
</div>
@endif
