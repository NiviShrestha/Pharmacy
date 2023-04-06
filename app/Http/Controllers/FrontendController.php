<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Slider;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;
use Auth;
class FrontendController extends Controller
{
    public function index()
    {
        $brand = Brand::where('status', 'active')->get();
        $slider = Slider::all();
        $featured = Product::where('is_featured', 'yes')->get();
        $topselling = Product::where('is_topselling', 'yes')->get();

        return view('front.index', ['brands' => $brand, 'sliders' => $slider, 'featuredProducts' => $featured,'topSellings' => $topselling]);
    }

    public function productDetail($id)
    {
        $product = Product::where('id', $id)->first();

        return view('front.product.detail', ['product' => $product]);
    }

    public function register(Request $request)
  {
    $user = new User();
    $user->email = $request->get('email');
    $user->password =$request->get('password');
    
    $user->save();
    return redirect()->back();
  }


   public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
           return redirect()->back();
        }

        return back()->with([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->back();
    }

    public function addToCart(Request $request){
        $productID = $request->get('product_id');
        $userID = Auth::user()->id;
        $quantity = $request->get('qty');
        
        $cart = new Cart();
        $cart->product_id = $productID;
        $cart->user_id = $userID;
        $cart->quantity= $quantity;
        $cart->save();
        return redirect()->back();
    }

        
}


