<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;

use Auth;
use Session;
use DB;
use Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect()->route($request->user()->role);
    }
    
    public function home()
    {
        $products = Product::where('status', 'active')
                            ->orderBy('id','desc')->limit(8)->get();
        
        $category = Category::where('status', 'active')
                            ->orderBy('name','asc')->get();
        
        return view('frontend.index')
                ->with('product_lists', $products)
                ->with('category_lists', $category);
    }

    // Login
    public function login()
    {
        return view('frontend.pages.login');
    }
    public function loginSubmit(Request $request){
        $data= $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'],'status'=>'active'])){
            Session::put('user',$data['email']);
            request()->session()->flash('success','Successfully login');
            return redirect()->route('home');
        }
        else{
            request()->session()->flash('error','Invalid email and password pleas try again!');
            return redirect()->back();
        }
    }

    // Register
    public function register()
    {
        return view('frontend.pages.register');
    }
    public function registerSubmit(Request $request){
        $this->validate($request,[
            'name'=>'string|required|min:2',
            'email'=>'string|required|unique:users,email',
            'password'=>'required|min:6|confirmed',
        ]);
        $data=$request->all();
        $check=$this->create($data);
        Session::put('user',$data['email']);
        if($check){
            request()->session()->flash('success','Successfully registered');
            return redirect()->route('home');
        }
        else{
            request()->session()->flash('error','Please try again!');
            return back();
        }
    }
    
    public function create(array $data)
    {
        return User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'status'=>'active'
        ]);
    }

    // Logout
    public function logout(){
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success','Logout successfully');
        return back();
    }
    

    // Product Grids
    public function productGrids(){
        $products = Product::query();

        if(!empty($_GET['category']))
        {
            $cat_ids = Category::select('id')->pluck('id')->toArray();
            $products->whereIn('cat_id',$cat_ids);
        }
        
        if(!empty($_GET['sortBy']))
        {
            if($_GET['sortBy']=='name'){
                $products = $products->where('status','active')->orderBy('name','ASC');
            }
            if($_GET['sortBy']=='price'){
                $products = $products->orderBy('price','ASC');
            }
        }

        if(!empty($_GET['price'])){
            $price = explode('-',$_GET['price']);
            $products->whereBetween('price',$price);
        }

        $recent_products = Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        // Sort by number
        if(!empty($_GET['show'])){
            $products = $products->where('status','active')->paginate($_GET['show']);
        }
        else{
            $products = $products->where('status','active')->paginate(9);
        }
        return view('frontend.pages.product-grids')
                    ->with('products',$products)
                    ->with('recent_products',$recent_products);
    }

    // Product Grids
    public function productLists(){
        
        $products = Product::query();

        if(!empty($_GET['category']))
        {
            $cat_ids = Category::select('id')->pluck('id')->toArray();
            $products->whereIn('cat_id',$cat_ids)->paginate;
        }
        
        if(!empty($_GET['sortBy']))
        {
            if($_GET['sortBy']=='name'){
                $products = $products->where('status','active')->orderBy('name','ASC');
            }
            if($_GET['sortBy']=='price'){
                $products = $products->orderBy('price','ASC');
            }
        }

        if(!empty($_GET['price'])){
            $price = explode('-',$_GET['price']);
            $products->whereBetween('price',$price);
        }

        $recent_products = Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        // Sort by number
        if(!empty($_GET['show'])){
            $products = $products->where('status','active')->paginate($_GET['show']);
        }
        else{
            $products = $products->where('status','active')->paginate(6);
        }
        return view('frontend.pages.product-lists')
                    ->with('products',$products)
                    ->with('recent_products',$recent_products);
    }

    public function productCat(Request $request){
        $products = Category :: getProductByCat($request->id);

        $recent_products = Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();

        // if(request()->is('e-shop.loc/product-grids')){
        //     return view('frontend.pages.product-grids')
        //                 ->with('products',$products->products)
        //                 ->with('recent_products',$recent_products);
        // }
        return view('frontend.pages.product-grids')
                        ->with('products',$products->products)
                        ->with('recent_products',$recent_products);
    }

    public function productFilter(Request $request){
        $data= $request->all();
        // return $data;
        $showURL="";
        if(!empty($data['show'])){
            $showURL .='&show='.$data['show'];
        }

        $sortByURL='';
        if(!empty($data['sortBy'])){
            $sortByURL .='&sortBy='.$data['sortBy'];
        }

        $catURL="";
        if(!empty($data['category'])){
            foreach($data['category'] as $category){
                if(empty($catURL)){
                    $catURL .='&category='.$category;
                }
                else{
                    $catURL .=','.$category;
                }
            }
        }


        $priceRangeURL="";
        if(!empty($data['price_range'])){
            $priceRangeURL .='&price='.$data['price_range'];
        }
        if(request()->is('e-shop.loc/product-grids')){
            return redirect()->route('product-grids',$catURL.$priceRangeURL.$showURL.$sortByURL);
        }
        else{
            return redirect()->route('product-lists',$catURL.$priceRangeURL.$showURL.$sortByURL);
        }
}

    public function productDetail($id){
        $product_detail = Product::getProductById($id);
        // dd($product_detail);
        return view('frontend.pages.product-detail')->with('product_detail', $product_detail);
    }


    public function productSearch(Request $request){
        $recent_products = Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $products=Product::orwhere('name','like','%'.$request->search.'%')
                            ->orderBy('id','DESC')
                            ->paginate('9');
        return view('frontend.pages.product-grids')
                ->with('products',$products)
                ->with('recent_products',$recent_products);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}