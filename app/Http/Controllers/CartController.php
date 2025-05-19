<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function add_to_cart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back()->with('success', 'Product added to cart');
    }

    public function increase_cart_quantity($rowId)
    {
        // Check if the product exists in the cart
        $product = Cart::instance('cart')->get($rowId);

        if ($product) {
            // Increase the quantity by 1
            $qty = $product->qty + 1;
            Cart::instance('cart')->update($rowId, $qty);

            return redirect()->back()->with('success', 'Product quantity increased');
        }

        return redirect()->back()->with('error', 'Product not found in the cart');
    }

    public function decrease_cart_quantity($rowId)
    {
        // Check if the product exists in the cart
        $product = Cart::instance('cart')->get($rowId);

        if ($product) {
            // Decrease the quantity by 1, but ensure it doesn't go below 1
            $qty = $product->qty - 1;

            if ($qty < 1) {
                return redirect()->back()->with('error', 'Quantity cannot be less than 1');
            }

            Cart::instance('cart')->update($rowId, $qty);

            return redirect()->back()->with('success', 'Product quantity decreased');
        }

        return redirect()->back()->with('error', 'Product not found in the cart');
    }


    public function remove_item($rowId){
        Cart::instance('cart')->remove($rowId);
        return redirect()->back()->with('success', 'Product removed from cart');
    }

    public function clear_cart(){
        Cart::instance('cart')->destroy();
        return redirect()->back()->with('success', 'Cart cleared');
    }
}
