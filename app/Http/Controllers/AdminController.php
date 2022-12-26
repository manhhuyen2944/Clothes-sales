<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use DB;
use Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::select(\DB::raw("COUNT(*) as count"), 
                            \DB::raw("DAYNAME(created_at) as day_name"), 
                            \DB::raw("DAY(created_at) as day"))
                            ->where('created_at', '>', Carbon::now()->subDay(6))
                            ->groupBy('day_name','day')
                            ->orderBy('day')->get();
         


         $orders = DB::table('orders')->where('created_at', '>', Carbon::now()->subDay(6) )->where('status','=','delivered')->sum('total_amount');
         $totalOrder = DB::table('orders')->count();   
         $oderscancel = DB::table('orders')->where('status','=','cancel')->count();       
         $odersProcess = DB::table('orders')->where('status','=','process')->count();   
         $oderDelivery = DB::table('orders')->where('status','=','delivered')->count();  
         $odernew = DB::table('orders')->where('status','=','new')->count();  
         $order30day = DB::table('orders')->where('created_at', '>', Carbon::now()->subDay(29))->where('status','=','delivered')->sum('total_amount');


        return view('backend.index',compact('orders','oderscancel','odersProcess','oderDelivery','totalOrder','orders','order30day','odernew'));
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