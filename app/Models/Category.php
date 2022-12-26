<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable=['name','status'];
    
    public function products(){
        return $this->hasMany('App\Models\Product','cat_id','id')->where('status','active');
    }
    
    public static function getAllCategory(){
        return Category::orderBy('id','DESC')->paginate(10);
    }

    public static function getProductByCat($id){
        return Category::with('products')->where('id',$id)->first();
    }

}