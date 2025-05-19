@props(['product'])

<div>
    <div class="product-card-wrapper">
        <div class="product-card mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
                <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <a href="{{ route('shop.product.details', $product->slug) }}"><img loading="lazy"
                                    src="{{ asset('uploads/products/' . $product->image) }}" width="330"
                                    height="400" alt="{{ $product->name }}" class="pc__img"></a>
                        </div>
                        <div class="swiper-slide">

                            @foreach (explode(',', $product->images) as $gimg)
                                <a href="{{ route('shop.product.details', $product->slug) }}"><img loading="lazy"
                                        src="{{ asset('uploads/products/' . $gimg) }}" width="330" height="400"
                                        alt="{{ $product->name }}" class="pc__img"></a>
                            @endforeach

                        </div>
                    </div>
                    <span class="pc__img-prev"><svg width="7" height="11" viewBox="0 0 7 11"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_prev_sm" />
                        </svg></span>
                    <span class="pc__img-next"><svg width="7" height="11" viewBox="0 0 7 11"
                            xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_next_sm" />
                        </svg></span>
                </div>
                @if (Cart::instance('cart')->content()->where('id', $product->id)->count() > 0)
                    <a href="{{ route('cart.index') }}"
                        class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium ">Go
                        to Cart</a>
                @else
                    <form name="addtocart-form" method="post" action="{{ route('cart.store') }}"
                        class="addtocart-form">
                        @csrf
                        <button type="submit"
                            class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium"
                            data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>

                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="price"
                            value="{{ $product->sale_price == null ? $product->regular_price : $product->sale_price }}">
                    </form>
                @endif
            </div>

            <div class="pc__info position-relative">
                <p class="pc__category">{{ $product->category->name }}</p>
                <h6 class="pc__title"><a
                        href="{{ route('shop.product.details', $product->slug) }}">{{ $product->name }}</a></h6>
                <div class="product-card__price d-flex">
                    <span class="money price">
                        @if ($product->sale_price)
                            <b><s>${{ $product->regular_price }}</s></b> <b>${{ $product->sale_price }}</b>
                        @else
                            <b>${{ $product->regular_price }}</b>
                        @endif
                    </span>
                </div>
                <div class="product-card__review d-flex align-items-center">
                    <div class="reviews-group d-flex">
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_star" />
                        </svg>
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_star" />
                        </svg>
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_star" />
                        </svg>
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_star" />
                        </svg>
                        <svg class="review-star" viewBox="0 0 9 9" xmlns="http://www.w3.org/2000/svg">
                            <use href="#icon_star" />
                        </svg>
                    </div>
                    <span class="reviews-note text-lowercase text-secondary ms-1">8k+ reviews</span>
                </div>

                <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                    title="Add To Wishlist">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <use href="#icon_heart" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector('.addtocart-form').addEventListener("submit", function(e) {
            e.preventDefault(); // Prevent the default form submission

            const form = e.target;

            fetch(form.action, {
                    method: form.method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams(new FormData(form)).toString()
                })
                .then(response => response.json())
                .then(data => {
                    toastr.success('Product added to cart successfully!');

                    // Optional: Update cart item count if returned by server
                    if (data.itemCount !== undefined) {
                        document.getElementById('cartItemCount').innerText = data.itemCount;
                    }

                    // Optional: Delay page reload by 2 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                })
                .catch(error => {
                    console.error("Error:", error);
                    toastr.error('Failed to add product to cart. Please try again.');
                });
        });
    });
</script>
