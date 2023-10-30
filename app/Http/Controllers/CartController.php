<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    public function shop()
    {
        $products = Product::get();
        return view('cart.shop',[
            'products'=> $products
        ]);
    }

    public function cart()
    {
        //dd(Cart::content());
        return view('cart.cart');
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        Cart::add(['id' => $product->id, 'name' => $product->name, 'qty' => 1, 'price' =>$product->price, 'weight' => 0, 'options' => ['image' => $product->image]]);

        return redirect()->back()->with('success','Product added into the cart!');
    }

    public function qtyIncrement($id)
    {
        $data=Cart::get($id);
       // dd($data->qty+1);
        Cart::update($id,$data->qty+1);
        return redirect()->back()->with('success','Product Increment successfully');
    }
    public function qtyDecrement($id)
    {
        $data=Cart::get($id);
        $updatedQty = $data->qty -1;
        if ($updatedQty > 0) {
         
            Cart::update($id,$updatedQty);
        }
         return redirect()->back()->with('success','Product decrement successfully');
    }

    public function removeProduct($id)
    {
        Cart::remove($id);

        return redirect()->back()->with('success','Product removed successfully');
    }
}
