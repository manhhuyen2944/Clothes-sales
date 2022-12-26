<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable=['user_id','order_number','sub_total','quantity','status','total_amount','first_name','last_name','address','phone','email','payment_method','payment_status','shipping_id'];

    public function cart_info(){
        return $this->hasMany('App\Models\Cart','order_id','id');
    }
    
    public static function getAllOrder($id){
        return Order::with('cart_info')->find($id);
    }

    public static function countActiveOrder(){
        $data=Order::count();
        if($data){
            return $data;
        }
        return 0;
    }
    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function shipping(){
        return $this->belongsTo(Shipping::class, 'shipping_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}