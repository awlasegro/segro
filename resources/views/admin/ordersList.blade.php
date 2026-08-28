@extends('admin.layout.master')

@section('title', 'Order List')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>All Orders List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Orders</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        @if (session('order_success'))
            <div class="alert alert-success">
                {{ session('order_success') }}
            </div>
        @endif
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
                <a href="/add-order-list" class="btn btn-primary btn-flat">New Order</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Title</th>
                  <th>Price</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Image</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($orderList as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->title }}</td>
                  <td>{{ $item->price }}</td>
                  <td>{{ $item->description }}</td>
                  <td>{{ $item->status }}</td>
                  <td><img src="{{ asset('OrderImages/' . $item->image) }}" alt="Order Image" width="150" height="150" style="object-fit: cover; border-radius: 8px;"></td>
                  <td>
                      <a href="{{ route('order-list.edit', $item->id) }}" class="btn btn-primary btn-flat">Edit</a>
                      <form action="{{ route('order-list.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this order?');">
                          @csrf
                          <button type="submit" class="btn btn-danger btn-flat">Delete</button>
                      </form>
                  </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->
  @endsection
