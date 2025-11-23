@extends('user.inc.master')
@section('title'){{$slug}} @endsection
@section('description'){{$slug}} @endsection
@section('keywords'){{$slug}} @endsection
@section('content')

<section class="product__section section--padding pt-3" style="padding-bottom: 4rem !important;">
    <div class="container-fluid px-5">
        <div class="section__heading mb-3 mt-4 d-flex">
            <h2 class="section__heading-- style2 flex-grow-1">
                {{-- @if (isset($slug) && !empty($slug))
                    @if ($slug=='featured') New Collection 
                    @elseif ($slug=='traending-now') Trending Now
                    @elseif ($slug=='best-selling') Best Selling
                    @endif
                @endif  --}}
               {{ $shopPageHeading }}
            </h2>
        </div>
        <div class="product__section--inner mt-4">
            <div class="row px-4 row-cols-xxl-6 row-cols-xl-6 row-cols-lg-6 row-cols-md-3 row-cols-2">
                @foreach($products as $product)
                    @include('user.partials.product')
                @endforeach
            </div>
            
            
            <div class="pagination__area bg__gray--color text-center mt-1">
                <nav class="pagination justify-content-center">
                    {{ $products->links('user.partials.pagination') }}
                </nav>
                <div style="margin-top: 10px !important;">
                    <span class="fw-bold">Showing {{$products->firstItem()}} to {{$products->lastItem()}} of {{$products->total()}} Products</span>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection