@extends('layouts.admin')

@section('content-header', 'Dashboard')

@section('content')
<div class="container-fluid">
  <div class="row">

    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-purple">
        <div class="inner">
          <h3>{{$products_count}}</h3>
          <p>Total Products</p>
        </div>
        <div class="icon">
          <i class="fas fa-dolly-flatbed"></i>
        </div>
        <a href="{{route('products.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-primary">
        <div class="inner">
          <h3>{{$orders_count}}</h3>
          <p>Orders Count</p>
        </div>
        <div class="icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <a href="{{route('orders.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3>{{config('settings.currency_symbol')}} {{number_format($income, 2)}}</h3>
          <p>Total Sales</p>
        </div>
        <div class="icon">
          <i class="fas fa-dollar-sign"></i>
        </div>
        <a href="{{route('orders.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->


  </div>

  <div class="row">
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-teal">
        <div class="inner">
          <h3>{{config('settings.currency_symbol')}} {{number_format($income_today, 2)}}</h3>

          <p>Today's Sales</p>
        </div>
        <div class="icon">
          <i class="fas fa-money-check-alt"></i>
        </div>
        <a href="{{route('orders.index')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>{{$customers_count}}</h3>

          <p>Total Customers</p>
        </div>
        <div class="icon">
          <i class="fas fa-users"></i>
        </div>
        <a href="{{ route('customers.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>

  <div class="row">
    <!-- Total Profits -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-orange">
        <div class="inner">
          <h3>{{config('settings.currency_symbol')}} {{number_format($profit, 2)}}</h3>

          <p>Total Profits</p>
        </div>
        <div class="icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <a href="{{ route('reports.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-orange">
        <div class="inner">
          <h3>{{config('settings.currency_symbol')}} {{number_format($profit_today, 2)}}</h3>

          <p>Today's Profit</p>
        </div>
        <div class="icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <a href="{{ route('reports.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>

  <div class="row">
    <!-- Total Discount -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-orange">
        <div class="inner">
          <h3>{{config('settings.currency_symbol')}} {{number_format($discount, 2)}}</h3>

          <p>Total Discount</p>
        </div>
        <div class="icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <a href="{{ route('reports.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-orange">
        <div class="inner">
          <h3>{{config('settings.currency_symbol')}} {{number_format($discount_today, 2)}}</h3>

          <p>Today's Discount</p>
        </div>
        <div class="icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <a href="{{ route('reports.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
</div>
@endsection