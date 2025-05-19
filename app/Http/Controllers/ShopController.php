<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('shop',  compact('products'));
    }

    public function product_details($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        // Step 1: Fetch initial related products
        $rproducts = Product::where('slug', '<>', $product_slug)->limit(8)->get()->unique('slug');

        // Step 2: Get the IDs of the already fetched products
        $excludedIds = $rproducts->pluck('id'); // Alternatively, use 'slug' if needed

        // Step 3: Fetch more products, excluding the already selected ones
        $moreProducts = Product::whereNotIn('id', $excludedIds)
            ->where('slug', '<>', $product_slug)
            ->limit(8)
            ->get();

        // Step 4: Combine the collections (optional)
        $allProducts = $rproducts->merge($moreProducts);

        // return $allProducts;


        return view('shopDetails', compact('product', 'allProducts'));
    }
}
