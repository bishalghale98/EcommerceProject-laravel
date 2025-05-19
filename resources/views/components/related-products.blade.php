@props(['products'])

<div>
    <section class="products-carousel container">
        <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4">Related <strong>Products</strong></h2>

        <div id="related_products" class="position-relative">
            <div class="swiper-container js-swiper-slider"
                data-settings='{
        "autoplay": false,
        "slidesPerView": 4,
        "slidesPerGroup": 4,
        "effect": "none",
        "loop": true,
        "pagination": {
          "el": "#related_products .products-pagination",
          "type": "bullets",
          "clickable": true
        },
        "navigation": {
          "nextEl": "#related_products .products-carousel__next",
          "prevEl": "#related_products .products-carousel__prev"
        },
        "breakpoints": {
          "320": {
            "slidesPerView": 2,
            "slidesPerGroup": 2,
            "spaceBetween": 14
          },
          "768": {
            "slidesPerView": 3,
            "slidesPerGroup": 3,
            "spaceBetween": 24
          },
          "992": {
            "slidesPerView": 4,
            "slidesPerGroup": 4,
            "spaceBetween": 30
          }
        }
      }'>
                <div class="swiper-wrapper">

                    @foreach ($products as $rproduct)
                        <div class="swiper-slide product-card">
                            <div class="pc__img-wrapper">
                                <a href="{{ route('shop.product.details', $rproduct->slug) }}">
                                    <img loading="lazy" src="{{ asset('uploads/products/' . $rproduct->image) }}"
                                        width="330" height="400" alt="{{ $rproduct->name }}" class="pc__img">


                                    @foreach (explode(',', $rproduct->images) as $gimg)
                                        <img loading="lazy" src="{{ asset('uploads/products/' . $gimg) }}"
                                            width="330" height="400" alt="{{ $rproduct->name }}"
                                            class="pc__img pc__img-second">
                                    @endforeach

                                </a>
                                @if (Cart::instance('cart')->content()->where('id', $rproduct->id)->count() > 0)
                                    <a href="{{ route('cart.index') }}"
                                        class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium ">Go
                                        to Cart</a>
                                @else
                                    <form name="addtocart-form" method="post" action="{{ route('cart.store') }}">
                                        @csrf
                                        <button type="submit"
                                            class="pc__atc btn anim_appear-bottom btn position-absolute border-0 text-uppercase fw-medium "
                                            data-aside="cartDrawer" title="Add To Cart">Add To Cart</button>

                                        <input type="hidden" name="id" value="{{ $rproduct->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <input type="hidden" name="name" value="{{ $rproduct->name }}">
                                        <input type="hidden" name="price"
                                            value="{{ $rproduct->sale_price == null ? $rproduct->regular_price : $rproduct->sale_price }}">
                                    </form>
                                @endif
                            </div>

                            <div class="pc__info position-relative">
                                <p class="pc__category">{{ $rproduct->category->name }}</p>
                                <h6 class="pc__title"><a
                                        href="{{ route('shop.product.details', $rproduct->slug) }}">{{ $rproduct->name }}</a>
                                </h6>
                                <div class="product-card__price d-flex">
                                    <span class="money price">
                                        @if ($rproduct->sale_price)
                                            <b><s>${{ $rproduct->regular_price }}</s></b>
                                            <b>${{ $rproduct->sale_price }}</b>
                                        @else
                                            <b>${{ $rproduct->regular_price }}</b>
                                        @endif
                                    </span>
                                </div>

                                <button
                                    class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0 js-add-wishlist"
                                    title="Add To Wishlist">
                                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <use href="#icon_heart" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach


                </div><!-- /.swiper-wrapper -->
            </div><!-- /.swiper-container js-swiper-slider -->

            <div
                class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_prev_md" />
                </svg>
            </div><!-- /.products-carousel__prev -->
            <div
                class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
                <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_next_md" />
                </svg>
            </div><!-- /.products-carousel__next -->

            <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
            <!-- /.products-pagination -->
        </div><!-- /.position-relative -->

    </section>
</div>
