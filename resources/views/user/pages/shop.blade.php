
@extends('user.inc.master')
@section('title')Shop @endsection
@section('description')Shop, all-products, discount-products, offer-products, offer, new-year-offer  @endsection
@section('keywords')Shop, all-products, discount-products, offer-products, offer, new-year-offer @endsection
@section('content')

<form action="javascript:void(0)" id="filter_form">
    @csrf
    <input type="hidden" id="brand_array" name="brand_array">
    <input type="hidden" id="lastID" name="lastID" value="1200">
    <input type="hidden" id="is_discount" name="is_discount" value="0">
    <input type="hidden" id="new_arrival" name="new_arrival" value="0">
    <input type="hidden" id="category_id" name="category_id" value="{{!is_null($request_category)? $request_category : 0}}">
    <input type="hidden" id="load_more" name="" value="0">
</form>

    <!-- Start shop section -->
    <section class="shop__section py-3">
        <div class="container-fluid">

            <div class="row">
             
                {{-- Load More --}}
                <div class="col-xl-12 col-lg-12">
                    <h3 class="my-2">Home/ {{ $categoryName->title ?? '' }}</h3>
                    <div class="shop__product--wrapper">
                        <div class="tab_content">
                            <div id="product_grid" class="tab_pane active show">
                                <div class="product__section--inner product__grid--inner">
                                    <div class="row row-cols-xxl-6 row-cols-xl-6 row-cols-lg-3 row-cols-md-2 row-cols-2 mb--n30" id="product_body">
                                    </div>
                                    <div class="row mt-3" id="loading_div"></div>
                                    <div class="row mb-5 text-center mt-3" id="load_more_div" style="display: none;">
                                        <div class="cart-action mb-6 pt-3 pb-3">
                                            <a href="javascript:void(0)" type="button" onclick="load_more()" class="continue__shipping--btn primary__btn border-radius-5"><i class="w-icon-long-arrow-left"></i>Load More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End shop section -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    $(document).ready(function() {
        let window_width = $(document).width();
        if(window_width > 991) {
            $('#mobile_filter').html('');
        }
        else {
            $('#desktop_filter').html('');
        }
        order_ready();
    });

    $(".brands").change(function() {
        $('#load_more').val(0);
        $('#lastID').val(1200);
        order_ready();
    });

  function selected_brands() {
    var brands = new Array();
    $('.brands:checked').each(function() {
      brands.push($(this).val());
    });
    if(brands.length > 0) {
      $('#brand_array').val(brands);
    }
    else {
      $('#brand_array').val(0);
    }
  }

  function order_ready() {
    selected_brands();
    order_confirm();
  }

  function load_more() {
    $('#load_more').val(1);
    order_ready();
  }


  function order_confirm() {

    // e.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{route('shop.products.data')}}",
            method: 'post',
            data: $('#filter_form').serialize(),
            beforeSend: function() {
                $('#loading_div').html('<div class="col-md-12" style="width: 100% !important;"><div class="text-center p-10"><h2><b>Loading....</b></h2></div></div>');
            },
            success: function(response){
                if(response.noMorePSts == 'no') {
                    $('#loading_div').html('');
                    $('#lastID').val(response.upLastID);
                    if($('#load_more').val() == 1) {
                        $('#product_body').append(response.output);
                    }
                    else {
                        $('#product_body').html(response.output);
                    }

                    $('#load_more_div').show();
                }
                else {
                    $('#product_body').append(response.output);
                    $('#load_more_div').hide();
                    $('#loading_div').html('');
                }
            }
        });
       
  }

</script>

@endsection