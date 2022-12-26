<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::getAllProduct();        
        return view('backend.product.index')->with('products',$products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::get();
        return view('backend.product.create')->with('categories',$category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'sku' => 'string',
            'name'=>'string|required',
            'description'=>'string|nullable',
            'photo' => 'required|mimes:jpeg,png,jpg,gif,svg',
            'size'=>'nullable',
            'stock'=>"required|numeric",
            'cat_id'=>'required|exists:categories,id',
            'condition'=>'required|in:default,new,hot',
            'status'=>'required|in:active,inactive',
            'price'=>'required|numeric',
        ]);

        $data = $request->all();

        if($request->hasfile('photo'))
        {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time().".".$extension;
            $file->move('images/products/', $filename);
            $data['photo'] = $filename;
        }

        $sku = $request->string('sku');
        $permitted_chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';   
        if($sku){
            $data['sku'] = substr(str_shuffle($permitted_chars), 0, 10);
        }
        
        $size = $request->input('size');
        if($size){
            $data['size'] = implode(',',$size);
        }
        else{
            $data['size'] = '';
        }
        
        $status = Product::create($data);
        if($status){
            request()->session()->flash('success','Product Successfully added');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('product.index');
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
        $product=Product::findOrFail($id);
        $category=Category::get();
        $items=Product::where('id',$id)->get();
        
        // return $items;
        return view('backend.product.edit')
                    ->with('product',$product)
                    ->with('categories',$category)
                    ->with('items',$items);
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
        $product = Product::findOrFail($id);
        
        $this->validate($request,[
            'name'=>'string|required',
            'description'=>'string|nullable',
            'photo'=>'mimes:jpeg,png,jpg,gif,svg',
            'size'=>'nullable',
            'stock'=>"required|numeric",
            'cat_id'=>'required|exists:categories,id',
            'status'=>'required|in:active,inactive',
            'condition'=>'required|in:default,new,hot',
            'price'=>'required|numeric',
        ]);

        $data = $request->all();
       
        if($request->hasfile('photo'))
        {
            $destination = 'images/photo/'.$data['photo'];
            if(File::exists($destination))
            {
                File::delete($destination);
            }
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $filename = time().".".$extension;
            $file->move('images/products/', $filename);
            $data['photo'] = $filename;
        }
        
        
        $size = $request->input('size');
        if($size){
            $data['size'] = implode(',',$size);
        }
        else{
            $data['size'] = '';
        }
        // return $data;
        $status = $product->fill($data)->update();
        if($status){
            request()->session()->flash('success','Product Successfully updated');
        }
        else{
            request()->session()->flash('error','Please try again!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $status = $product->delete();
        
        if($status){
            request()->session()->flash('success','Product successfully deleted');
        }
        else{
            request()->session()->flash('error','Error while deleting product');
        }
        return redirect()->route('product.index');
    }
}