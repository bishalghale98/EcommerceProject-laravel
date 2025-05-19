@extends('layouts.app')

@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="shop-checkout container">
            <h2 class="page-title">Cart</h2>
            <div class="checkout-steps">
                <a href="javascript:void(0)" class="checkout-steps__item active">
                    <span class="checkout-steps__item-number">01</span>
                    <span class="checkout-steps__item-title">
                        <span>Shopping Bag</span>
                        <em>Manage Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">02</span>
                    <span class="checkout-steps__item-title">
                        <span>Shipping and Checkout</span>
                        <em>Checkout Your Items List</em>
                    </span>
                </a>
                <a href="javascript:void(0)" class="checkout-steps__item">
                    <span class="checkout-steps__item-number">03</span>
                    <span class="checkout-steps__item-title">
                        <span>Confirmation</span>
                        <em>Review And Submit Your Order</em>
                    </span>
                </a>
            </div>
            <div class="shopping-cart">

                @if ($items->count() > 0)
                    <div class="cart-table__wrapper">
                        <table class="cart-table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th></th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>
                                            <div class="shopping-cart__product-item">
                                                <img loading="lazy"
                                                    src="{{ asset('uploads/products/thumbnails/' . $item->model->image) }}"
                                                    width="120" height="120" alt="{{ $item->name }}" />
                                            </div>
                                        </td>
                                        <td>
                                            <div class="shopping-cart__product-item__detail">
                                                <h4>{{ $item->name }}</h4>
                                                <ul class="shopping-cart__product-item__options">
                                                    <li>Color: Yellow</li>
                                                    <li>Size: L</li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__product-price">${{ $item->price }}</span>
                                        </td>
                                        <td>
                                            <div class="qty-control position-relative">
                                                <input type="number" name="quantity" value="{{ $item->qty }}"
                                                    min="1" class="qty-control__number text-center">

                                                {{-- Decrease --}}
                                                <form action="{{ route('cart.qty.decrease', ['rowId' => $item->rowId]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PATCH') <!-- Changed from PUT to PATCH -->
                                                    <div class="qty-control__reduce">-</div>
                                                </form>

                                                {{-- Increase --}}
                                                <form action="{{ route('cart.qty.increase', ['rowId' => $item->rowId]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PATCH') <!-- Changed from PUT to PATCH -->
                                                    <div class="qty-control__increase">+</div>
                                                </form>

                                            </div>
                                        </td>
                                        <td>
                                            <span class="shopping-cart__subtotal">${{ $item->subtotal }}</span>
                                        </td>
                                        <td>

                                            <form action="{{ route('cart.item.remove', ['rowId' => $item->rowId]) }}"
                                                method="post">
                                                @csrf
                                                @method('DELETE')
                                                <a href="javcascript:void(0)" class="remove-cart">
                                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="#767676"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" />
                                                        <path
                                                            d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" />
                                                    </svg>
                                                </a>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div class="cart-table-footer">
                            <form action="#" class="position-relative bg-body">
                                <input class="form-control" type="text" name="coupon_code" placeholder="Coupon Code">
                                <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit"
                                    value="APPLY COUPON">
                            </form>
                            <form action="{{ route('cart.empty') }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-light" type="submit">CLEAR CART</button>
                            </form>
                        </div>
                    </div>

                    <div class="shopping-cart__totals-wrapper">
                        <div class="sticky-content">
                            <div class="shopping-cart__totals">
                                <h3>Cart Totals</h3>
                                <table class="cart-totals">
                                    <tbody>
                                        <tr>
                                            <th>Subtotal</th>
                                            <td>${{ Cart::instance('cart')->subtotal() }}</td>
                                        </tr>
                                        <tr>
                                            <th>Shipping</th>
                                            <td>
                                                Free Shipping
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>VAT</th>
                                            <td>N/A</td>
                                        </tr>
                                        <tr>
                                            <th>Total</th>
                                            <td>${{ Cart::instance('cart')->subtotal() }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mobile_fixed-btn_wrapper">
                                <div class="button-wrapper container">
                                    <a href="checkout.html" class="btn btn-primary btn-checkout">PROCEED TO CHECKOUT</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center pt-5 bp-5">
                            <h2 class="text-center">You have no items in your cart</h2>
                            <a href="{{ route('shop.index') }}" class="btn btn-primary">Continue Shopping</a>
                        </div>
                    </div>
                @endif



            </div>
        </section>
    </main>
@endsection

@push('scripts')
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- jQuery (required for Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



    <script>
        $(function() {
            // Increase quantity button
            $(".qty-control__increase").on("click", function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        toastr.success('Product quantity increased successfully!');
                        updateCartDisplay(response);

                        // Refresh the page after a 2-second delay
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function() {
                        toastr.error('Failed to increase product quantity. Please try again.');
                    }
                });
            });

            // Decrease quantity button
            $(".qty-control__reduce").on("click", function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        toastr.success('Product quantity decreased successfully!');
                        updateCartDisplay(response);

                        // Refresh the page after a 2-second delay
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function() {
                        toastr.error('Failed to decrease product quantity. Please try again.');
                    }
                });
            });

            // Remove from cart button
            $('.remove-cart').on("click", function(e) {
                e.preventDefault();
                const form = $(this).closest('form');

                $.ajax({
                    url: form.attr('action'),
                    method: form.attr('method'),
                    data: form.serialize(),
                    success: function(response) {
                        toastr.success('Product removed from cart successfully!');
                        updateCartDisplay(response);

                        // Refresh the page after a 2-second delay
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function() {
                        toastr.error('Failed to remove product from cart. Please try again.');
                    }
                });
            });

            // Function to update the cart display without refreshing
            function updateCartDisplay(response) {
                // Optional: update the cart display based on response data
                $('#cartTotalAmount').text(response.totalAmount);
                $('#cartItemCount').text(response.itemCount);

                if (response.updatedItem) {
                    $(`#item-${response.updatedItem.id} .item-quantity`).text(response.updatedItem.quantity);
                    $(`#item-${response.updatedItem.id} .item-total`).text(response.updatedItem.total);
                }

                if (response.removedItemId) {
                    $(`#item-${response.removedItemId}`).remove();
                }
            }
        });
    </script>
@endpush
