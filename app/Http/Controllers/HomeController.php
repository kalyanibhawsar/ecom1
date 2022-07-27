<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\product;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
        $products = Product::paginate(6);
        return view('home.userpage', compact('products'));
    }

    public function redirect()
    {
        $usertype = Auth::user()->usertype;
        if ($usertype == '1') {
            $total_product = Product::all()->count();
            $total_user = User::all()->count();
            return view('admin.dashboard', compact('total_product', 'total_user'));
        } else {
            $products = Product::paginate(6);
            return view('home.userpage', compact('products'));
        }
    }

    public function product_details($id)
    {
        $product = product::find($id);
        return view('home.product_details', compact('product'));
    }

    public function add_cart($id)
    {
        $product = product::find($id);
        if (session()->has('cart')) {
            $cartdata = session()->get('cart');
            $cartdata[$product->id] = array(
                'product_id' => $product->id,
                'quantity' => 1,
                'product_title' => $product->title,
                'image' => $product->image,
                'price' => $product->price
            );
        } else {
            $cartdata[$product->id] = array(
                'product_id' => $product->id,
                'quantity' => 1,
                'product_title' => $product->title,
                'image' => $product->image,
                'price' => $product->price
            );
        }
        session()->put('cart', $cartdata);
        return redirect()->back()->with('message', 'Product added to cart');
    }


    public function show_cart()
    {
        return view('home.showcart');
    }

    public function update_cart(Request $request)
    {
        if ($request->pid && $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->pid]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('message', 'Cart updated to cart');
        }
    }

    public function remove_cart(Request $request)
    {
        if ($request->pid) {
            $cart = session()->get('cart');

            if (isset($cart[$request->pid])) {
                unset($cart[$request->pid]);
                session()->put('cart', $cart);
            }
            session()->flash('message', 'Product removed from cart');
        }
    }

    public function checkout()
    {
        if (Auth::check()) {
            return view('home.checkout');
        } else {
            return redirect('login');
        }
    }
}
