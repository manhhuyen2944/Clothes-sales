<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable=['name','description','cat_id','price','status','photo','size','stock','condition'];

    public function rel_prods(){
        return $this->hasMany('App\Models\Product','cat_id','cat_id')->where('status','active')->orderBy('id','DESC')->limit(8);
    }
    
    public function cat_info(){
        return $this->hasOne('App\Models\Category','id','cat_id');
    }
    
    public static function getAllProduct(){
        return Product::with(['cat_info'])->orderBy('id','desc')->paginate(10);
    }

    public static function getProductById($id){
        return Product::with(['cat_info'])->where('id',$id)->first();
    }

    public function carts(){
        return $this->hasMany(Cart::class)->whereNotNull('order_id');
    }

}