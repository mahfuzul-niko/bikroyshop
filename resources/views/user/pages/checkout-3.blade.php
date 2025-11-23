@extends('user.inc.master')

@section('title')
    {{ __('messages.Checkout') }}
@endsection
@section('description')
    {{ __('messages.Checkout') }}
@endsection
@section('keywords')
    {{ __('messages.Checkout') }}
@endsection

@section('content')
    @php
        $total = Cart::subtotal();
        $discount = 0;
        if (Session::has('coupon_discount')) {
            $discount = Session::get('coupon_discount');
        }

        $total_with_discount = $total - $discount;
    @endphp

    <style>
        /* HIDE RADIO */
        .hiddenradio [type=radio] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        .hiddenradio [type=radio]+img {
            cursor: pointer;
        }

        /* CHECKED STYLES */
        .hiddenradio [type=radio]:checked+img {
            outline: 4px solid #0ABB75;
        }

        .attribute-bg-info {
            font-size: 1.2rem;
            border-radius: 5px;
            background-color: #F6F8FA;
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 2px;
            padding-bottom: 2px;
            margin-bottom: 2px;
        }
    </style>

    <!-- Start of PageContent -->
    <div class="page-content py-5">
        <div class="container-fluid">

            <form class="form checkout-form rounded p-3" action="{{ route('order.create') }}" method="post">
                @csrf
                @if (!Auth::check())
                    {{-- <div class="">
                        <input type="hidden" name="auth_status" id="auth_status" value="0">
                        {{ __('messages.Returning customer') }}? <a href="{{ route('login') }}"
                            style="color: #EE2761; !impotant;"
                            class="show-login font-weight-bold text-uppercase">{{ __('messages.Login') }}</a>
                    </div> --}}
                @else
                    <input type="hidden" name="auth_status" id="auth_status" value="1">
                @endif

                <div class="row">
                    <h4 class="mb-5 mt-3 text-center" style="font-weight: 400">YOUR ORDER INFORMATION</h4>
                    <div class="col-md-12">
                        <div class="cart__table">
                            <table class="cart__table--inner border border-1">
                                <thead class="cart__table--header ">
                                    <tr class="cart__table--header__items">
                                        <th width="45%" class="cart__table--header__list text-center">
                                            {{ __('messages.Product') }}
                                        </th>
                                        <th class="cart__table--header__list">{{ __('messages.Price') }}</th>
                                        <th class="cart__table--header__list">{{ __('messages.Quantity') }}</th>
                                        <th class="cart__table--header__list">{{ __('messages.Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="cart__table--body">
                                    @if (count($carts) > 0)
                                        @foreach ($carts as $cart)
                                            <?php
                                            $product_info = App\Models\Product::find(optional($cart->options)->product_id);
                                            ?>

                                            @if (!is_null($product_info))
                                                <?php
                                                $variation_info = '';
                                                
                                                if ($cart->weight != 0) {
                                                    $stock_info = App\Models\ProductStocks::find($cart->weight);
                                                
                                                    if ($stock_info->color != null) {
                                                        $color_attribute_info = color_info($stock_info->color);
                                                        $color_info = '<b>Color: </b>' . optional($color_attribute_info)->name . ', ';
                                                    } else {
                                                        $color_info = '';
                                                    }
                                                
                                                    if ($stock_info->variant != null) {
                                                        $variant_attribute_info = variation_info($stock_info->variant);
                                                        $attribute_info = '<b>' . optional($variant_attribute_info)->title . ': </b>' . optional($stock_info)->variant_output . '';
                                                    } else {
                                                        $attribute_info = '';
                                                    }
                                                    $variation_info = $color_info . $attribute_info;
                                                } else {
                                                    $stock_info = $product_info->single_stock;
                                                }
                                                
                                                $price_info = '<span class="current__price">' . __currency() . bnConv('bnComNum', optional($cart->options)->single_price) . '</span>';
                                                
                                                if (optional($cart->options)->old_price > 0) {
                                                    $price_info .= ' <span class="old__price">' . __currency() . bnConv('bnComNum', optional($cart->options)->old_price) . '</span>';
                                                }
                                                
                                                ?>
                                                <tr class="cart__table--body__items mb-2">
                                                    <td class="cart__table--body__list">
                                                        <div class="cart__product d-flex align-items-center">
                                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="rowId"
                                                                    value="{{ $cart->rowId }}">
                                                                <button class="cart__remove--btn" aria-label="search button"
                                                                    type="submit">
                                                                    <svg fill="currentColor"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        viewBox="0 0 24 24" width="16px" height="16px">
                                                                        <path
                                                                            d="M 4.7070312 3.2929688 L 3.2929688 4.7070312 L 10.585938 12 L 3.2929688 19.292969 L 4.7070312 20.707031 L 12 13.414062 L 19.292969 20.707031 L 20.707031 19.292969 L 13.414062 12 L 20.707031 4.7070312 L 19.292969 3.2929688 L 12 10.585938 L 4.7070312 3.2929688 z">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                            </form>

                                                            <div class="cart__thumbnail">
                                                                <a
                                                                    href="{{ route('single.product', [$product_info->id, Str::slug($product_info->title)]) }}"><img
                                                                        class="border-radius-5 shadow rounded p-1"
                                                                        src="{{ asset('images/product/' . optional($product_info)->thumbnail_image) }}"
                                                                        alt="cart-product"></a>
                                                            </div>
                                                            <div class="cart__content">
                                                                <h4 class="cart__content--title"><a
                                                                        href="{{ route('single.product', [$product_info->id, Str::slug($product_info->title)]) }}">
                                                                        {{ __translate(Str::limit($product_info->title, 50), Str::limit($product_info->bn_title, 50)) }}
                                                                    </a></h4>
                                                                <span
                                                                    class="cart__content--variant">{!! $variation_info !!}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="cart__table--body__list">
                                                        <span class="cart__price">{!! $price_info !!}</span>
                                                    </td>
                                                    <td class="cart__table--body__list">
                                                        <div class="quantity__box">
                                                            <button type="button"
                                                                class="quantity__value quickview__value--quantity decrease cart_page_qty_decrease"
                                                                onclick="change_cart_qty('down', '{{ $cart->rowId }}', 'cart_page')"
                                                                aria-label="quantity value"
                                                                value="Decrease Value">-</button>
                                                            <label>
                                                                <input type="number"
                                                                    class="quantity__number quickview__value--number cart_page_qty_{{ $cart->rowId }}"
                                                                    readonly min="1" value="{{ $cart->qty }}"
                                                                    data-counter="">
                                                            </label>
                                                            <button type="button"
                                                                class="quantity__value quickview__value--quantity increase cart_page_qty_increase"
                                                                onclick="change_cart_qty('up', '{{ $cart->rowId }}', 'cart_page')"
                                                                aria-label="quantity value"
                                                                value="Increase Value">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="cart__table--body__list">
                                                        <span
                                                            class="cart__price end">{{ __currency() . bnConv('bnComNum', optional($cart->options)->single_price * $cart->qty) }}</span>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">
                                                <div class="minicart__product--items text-center">
                                                    <h3 class="py-5 px-2"><b>
                                                            {{ __('messages.Your cart is empty') }}</b>
                                                        <h3>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            {{-- <div class="continue__shopping d-flex justify-content-between">
                            <a class="continue__shopping--link" href="{{route('products')}}"> 
                                {{ __('messages.Continue shopping') }}
                            </a>
                        </div> --}}
                        </div>
                    </div>
                </div>

                <div class="row my-5">
                    <div class="col-md-6">
                        <div class="card rounded">
                            <div class="card-header p-3" style="background: #ff4500; color:#fff">
                                <h4><i class="fas fa-truck"></i> Delivery Method</h4>
                            </div>
                            <div class="card-body p-3">

                                <p>Please select the preferred shipping method to use on this order.</p>

                                <div class="radioSec">
                                    @foreach ($districts as $district)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="district_id"
                                                id="{{ $district->id }}" value="{{ $district->id }}"
                                                @if ($loop->index == 0) checked @endif>
                                            <label class="form-check-label" for="{{ $district->id }}">
                                                {{ __translate($district->name, $district->bn_name) }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card rounded-sm">
                            <div class="card-header p-3" style="background: #ff4500; color:#fff">
                                <h4><i class="fas fa-money-check"></i></i> Payment Method</h4>
                            </div>
                            <div class="card-body p-3">
                                <p>Please select the preferred payment method to use on this order.</p>

                                <div class="radioSec">

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="cod"
                                            name="payment_type" checked value="cod">
                                        <label class="form-check-label" for="cod">
                                            Cash On Delivery
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            id="curier" value="curier">
                                        <label class="form-check-label" for="curier">
                                            Courier Service
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_type"
                                            id="bkash" value="bkash">
                                        <label class="form-check-label" for="bkash">
                                            bKash
                                        </label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>


                <div class="row my-5">

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header p-3" style="background: #ff4500; color:#fff">
                                <h4><i class="fas fa-pencil-alt"></i> Products Details, Your Personal Details & Address
                                </h4>
                            </div>

                            <div class="card-body">

                                <div class="form-group">
                                    <label>{{ __('messages.Name') }} @required </label>
                                    <input type="text" class="checkout__input--field border-radius-5" name="name"
                                        value="{{ optional(Auth::user())->name . ' ' . optional(Auth::user())->last_name }}"
                                        required>
                                </div>


                                <div class="form-group">
                                    <label>{{ __('messages.Phone') }} @required</label>
                                    <input type="text" class="checkout__input--field border-radius-5" name="phone"
                                        value="{{ optional(Auth::user())->phone }}" required>
                                </div>


                                <div class="form-group">
                                    <label>{{ __('messages.Email') }}({{ __('messages.Optional') }})</label>
                                    <input type="email" class="checkout__input--field border-radius-5" name="email"
                                        value="{{ optional(Auth::user())->email }}">
                                </div>


                                <div class="form-group mb-3">
                                    <label> {{ __('messages.Address') }} @required </label>
                                    <textarea name="shipping_address" id="" cols="30" rows="10" required
                                        placeholder="{{ __('messages.House number and street name') }}"
                                        class="checkout__input--field border-radius-5 mb-2">{{ optional(Auth::user())->address }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label> {{ __('messages.Note') }}({{ __('messages.Optional') }})</label>
                                    <textarea name="note" id="" cols="30" rows="10" placeholder=""
                                        class="checkout__input--field border-radius-5 mb-2"></textarea>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>


                <div class="row my-5">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header p-3" style="background: #ff4500; color:#fff">
                                <h4><i class="fas fa-wallet"></i> </i> Sub Total Amount</h4>
                            </div>

                            <div class="card-body">
                                <table class="cart__summary--total__table">
                                    <tbody>
                                        <tr class="cart__summary--total__list">
                                            <td class="cart__summary--total__title text-left fw-bold">
                                                {{ __('messages.Subtotal') }}</td>
                                            <input type="hidden" name="subtotal" id="subtotal"
                                                value="{{ $total }}" />
                                            <td class="cart__summary--amount text-right">
                                                {{ __currency() . bnConv('bnComNum', $total) }}</td>
                                        </tr>
                                        <tr class="cart__summary--total__list">
                                            <td class="cart__summary--total__title text-left fw-bold">
                                                {{ __('messages.Discount') }}</td>
                                            <input type="hidden" name="discount" id="discount"
                                                value="{{ $discount }}" />
                                            <td class="cart__summary--amount text-right">
                                                {{ __currency() . bnConv('bnComNum', $discount) }}
                                            </td>
                                        </tr>
                                        <tr class="cart__summary--total__list">
                                            <td class="cart__summary--total__title text-left fw-bold">
                                                {{ __('messages.Shipping Charge') }}</td>
                                            <input type="hidden" name="shipping_charge" id="shipping_charge"
                                                value="0" />
                                            <td class="cart__summary--amount text-right"><span
                                                    id="shipping_charge_label">{{ __currency() . bnConv('bnComNum', 0) }}</span>
                                            </td>
                                        </tr>

                                        <tr class="cart__summary--total__list">
                                            <td class="cart__summary--total__title text-left fw-bold">
                                                {{ __('messages.Total') }}</td>
                                            <input type="hidden" name="total" id="total"
                                                value="{{ $total_with_discount }}" />
                                            <td class="cart__summary--amount text-right" id="total_show">
                                                {{ __currency() . bnConv('bnComNum', $total_with_discount) }}
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                    <div class="row my-5">

                        <div class="col-md-9">
                            <div class="form-group mt-2  mb-3">
                                <div class="checkout__checkbox">
                                    <input class="checkout__checkbox--input" id="check2" required type="checkbox">
                                    <span class="checkout__checkbox--checkmark"></span>
                                    <label class="checkout__checkbox--label"
                                        for="check2">{{ __('messages.I agree to the') }}
                                        <a href="#" style="color: var(--logo-color);">
                                            {{ __('messages.Terms and Conditions') }}, </a>
                                        <a href="#" style="color: var(--logo-color);">
                                            {{ __('messages.Return Refund Policy') }} </a> {{ __('messages.&') }}
                                        <a href="#"
                                            style="color: var(--logo-color);">{{ __('messages.Privacy Policy') }}</a>
    
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 text-right">
                            <div class="checkout__content--step__footer d-flex align-items-center justify-content-end">
                                <button class="continue__shipping--btn primary__btn border-radius-5" style="background: #ff4500; color:#fff" type="submit">
                                    {{ __('messages.Confirm Order') }}</button>
                            </div>
                        </div>

                    </div>
            </form>

        </div>
    </div>
    <!-- End of PageContent -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <script>
        $('#district_id').change(function() {
            var district_id = $(this).val();
            if (district_id == '') {
                district_id = -1;
            }
            var option = "<option value=''>{{ __('messages.Please Chose Your Shipping Area') }}</option>";
            var url = "{{ url('/') }}";

            $.get(url + "/get-area/" + district_id, function(data) {
                data = JSON.parse(data);
                data.forEach(function(element) {
                    option += "<option value='" + element.id + "'>" + element.name + "</option>";
                });
                //console.log(option);
                $('#areas').html(option);
            });

            var subtotal = $('#subtotal').val();
            let discount = $('#discount').val();
            let total = 0;
            let wallet_amount = 0;
            let auth_status = $('#auth_status').val();

            $.ajax({
                url: url + "/get-shipping-charge",
                type: "get",
                data: {
                    district_id: district_id,
                    subtotal: subtotal,
                    discount: discount,
                },
                success: function(response) {
                    total = (parseInt(subtotal) - parseInt(discount)) + parseInt(response
                        .shipping_charge_amount);
                    wallet_amount = response.wallet_amount;

                    if (auth_status == 1 && parseFloat(wallet_amount) >= parseFloat(total)) {
                        $('#wallet_select_body').show();
                    }

                    $('#shipping_charge_label').html(response.shipping_charge);

                    $('#total_show').html(response.total_show);
                    $('#total').val(response.total_amount);
                    $('#shipping_charge').val(response.shipping_charge_amount);
                }
            });

        });

        function calculate_total() {

        }

        function payment_setup(type) {
            if (type == 'cod') {
                $('#online_mfs_main_div').hide();
                $('#online_payment_mfs').prop('required', false);
                $('#online_mfs_paymnet_number').prop('required', false);
            } else if (type == 'online_mfs') {
                $('#online_mfs_main_div').show();
                $('#online_payment_mfs').prop('required', true);
                $('#online_mfs_paymnet_number').prop('required', true);
            }
        }
    </script>

@endsection
