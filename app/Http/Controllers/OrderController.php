<?php
namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use App\Notifications\StatusNotification;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Shipping;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = DB::table('orders')->get();
        return view('backend.order.index',compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'first_name'=>'string|required',
            'last_name'=>'string|required',
            'address'=>'string|required',
            'phone'=>'numeric|required',
            'email'=>'string|required'
        ]);
        // return $request->all();

        if(empty(Cart::where('user_id',auth()->user()->id)->where('order_id',null)->first())){
            request()->session()->flash('error','Cart is Empty !');
            return back();
        }

        $order=new Order();
        $order_data=$request->all();
        $order_data['order_number']='ORD-'.strtoupper(Str::random(10));
        $order_data['user_id']=$request->user()->id;
        $order_data['shipping_id']=$request->shipping;
        $shipping = Shipping::where('id',$order_data['shipping_id'])->pluck('price');
        
        // return session('coupon')['value'];
        $order_data['sub_total']=Helper::totalCartPrice();
        $order_data['quantity']=Helper::cartCount();

        if($request->shipping){
        
            $order_data['total_amount']=Helper::totalCartPrice()+$shipping[0];
        }
        
        else{
            
            $order_data['total_amount'] = Helper::totalCartPrice();
        
        }
        // return $order_data['total_amount'];
        
        $order_data['status']="new";
        if(request('payment_method')=='cod'){
            $order_data['payment_method']='cod';
            $order_data['payment_status']='Unpaid';
        }
        
        $order->fill($order_data);
        $status = $order->save();
        
        if($order)
        // dd($order->id);
        $users  = User::where('role','admin')->first();
        $details=[
            'title'=>'New order created',
            'actionURL'=>route('order.show',$order->id),
            'fas'=>'fa-file-alt'
        ];
        
        session()->forget('cart');
    
        Cart::where('user_id', auth()->user()->id)->where('order_id', null)->update(['order_id' => $order->id]);

        // dd($users);        
        request()->session()->flash('success','Your product successfully placed in order');
        return redirect()->route('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order=Order::find($id);   
        foreach($order as $item  )
        {
            

        }   
        return view('backend.order.show')->with('order',$order);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order=DB::table('orders')->find($id);

        return view('backend.order.edit')->with('order',$order);
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
        $orders= Order::findOrFail($id);
        if($request->status=='delivered'){
            foreach($orders->cart as $cart){
                $product=$cart->product;
                $product->stock -=$cart->quantity;
                $product->save();
            }
        }
        $orders->update([
            'status'=>$request->status
        ]);  
        return redirect()->route('Order');
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order=Order::find($id);
        if($order){
            $status=$order->delete();
            if($status){
                request()->session()->flash('success','Order Successfully deleted');
            }
            else{
                request()->session()->flash('error','Order can not deleted');
            }
            return redirect()->route('Order');
        }
        else{
            request()->session()->flash('error','Order can not found');
            return redirect()->back();
        }
    }
}