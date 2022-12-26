@extends('backend.layouts.master')

@section('title','E-SHOP || DASHBOARD')

@section('main-content')
<div class="container-fluid">
    @include('backend.layouts.notification')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">


        <!-- Order -->
            <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1"> Total Order</div>
                <div class="row no-gutters align-items-center">
                  <div class="col-auto">
                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$totalOrder}}</div>
                  </div>
                  
                </div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
                                         <!-- new -->
                                         <div class="col-xl-3 col-md-6 mb-4">
                              <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">New</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{$odernew}}</div>
                                    </div>
                                    <div class="col-auto">
                                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>







      <!-- cancel -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Process</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$odersProcess}}</div>
              </div>
              <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Delivery -->
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Delivery</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{$oderDelivery}}</div>
              </div>
           <div class="col-auto">
                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>

     
   

    
                                  <!-- cancel -->
                            <div class="col-xl-3 col-md-6 mb-4">
                              <div class="card border-left-danger shadow h-100 py-2">
                                <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Cancel</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{$oderscancel}}</div>
                                    </div>
                                    <div class="col-auto">
                                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>


              
      
                     





                        
    </div>


    <h1 class="h3 mb-0 text-gray-800">Tổng tiền</h1>
    <div class="row">
           <!-- Tổng tiền trong 7 ngày -->
           <div class="col-xl-3 col-md-6 mb-4">
                              <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng số tiền trong 7 ngày</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{$orders}}</div>
                                    </div>
                                <div class="col-auto">
                                <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                               
                                  </div>
                                  </div>
                                </div>
                              </div>
                            </div>
         <!-- Tổng tiền trong 30 ngày -->
         <div class="col-xl-3 col-md-6 mb-4">
                              <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                  <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tổng tiền trong 30 ngày</div>
                                      <div class="h5 mb-0 font-weight-bold text-gray-800">{{$order30day}}</div>
                                    </div>
                                <div class="col-auto">
                                <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                                  
                                  </div>
                                  </div>
                                </div>
                              </div>
                            </div>


     </div>
    <div class="row">
    </div>
  </div>
@endsection




