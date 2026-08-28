@extends('admin.layout.master')

@section('title', 'Setup Orders')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Setup Orders</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Setup Orders</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Selected Orders for {{ $user->name }}</h3>
            </div>
            <div class="card-body">
              @if ($selected_order_list->isEmpty())
                <p>No orders selected for this user.</p>
                <a href="{{ route('reset.single', $user->id) }}" class="btn btn-primary">Setup Order</a>
              @else
              @if (session('order_success'))
                    <div class="alert alert-success">
                        {{ session('order_success') }}
                    </div>
                @endif
                @if (session('order_error'))
                    <div class="alert alert-danger">
                        {{ session('order_error') }}
                    </div>
                @endif
                <form action="{{ route('update.orders', $user->id) }}" method="POST">
                  @csrf
                  @method('POST') <!-- This indicates that the form will use the POST method -->
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Select</th>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Order After</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($selected_order_list as $order)
                        <tr>
                          <td>
                            <input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" checked>
                          </td>
                          <td>{{ $order->orderList->title }}</td>
                          <td>{{ $order->orderList->price }}</td>
                          <td>{{ $order->order_after }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <div class="mt-3">
                    <!-- Update button to submit the form -->
                    <button type="submit" class="btn btn-success">Update Orders</button>

                    <!-- Setup Order button to call the route for resetting single order -->
                    <a href="{{ route('reset.single', $user->id) }}" class="btn btn-primary">Setup Order</a>
                  </div>
                </form>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection
