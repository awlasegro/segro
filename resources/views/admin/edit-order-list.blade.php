@extends('admin.layout.master')

@section('title', 'Edit Order List')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Orders List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/order-list">Orders</a></li>
            <li class="breadcrumb-item active">Edit Orders List</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

        <form action="{{ route('order-list.update', $order->id) }}" method="post" enctype="multipart/form-data">
            @csrf
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Edit Orders List</h3>
            </div>
            @if ($errors->any())
              <div class="alert alert-danger" style="margin: 15px;">
                <ul class="mb-0">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <div class="card-body">
               <!--Order Title -->
               <div class="form-group">
                <label>Order Title</label>
                  <div class="input-group">
                      <input type="text" name="title" value="{{ $order->title }}" placeholder="Enter Title" class="form-control" required/>
                  </div>
              </div>
              <!-- /.form group -->
              <!--Price -->
              <div class="form-group">
                <label>Price</label>
                  <div class="input-group">
                      <input type="text" name="price" value="{{ $order->price }}" placeholder="Add Price" class="form-control" required/>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Description -->
               <div class="form-group">
                <label>Description</label>
                  <div class="input-group">
                      <input type="text" name="description" value="{{ $order->description }}" placeholder="Enter Short Description" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Image -->
               <div class="form-group">
                <label>Image</label>
                  <div style="margin-bottom: 10px;">
                      <img src="/OrderImages/{{ $order->image }}" alt="Current Order Image" style="max-width: 150px; border-radius: 8px; border: 1px solid var(--border-color);"/>
                  </div>
                  <div class="input-group">
                      <input type="file" name="image" class="form-control"/>
                  </div>
                  <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Leave empty to keep the current image.</p>
              </div>
              <!-- /.form group -->

              <!-- form Buttons -->
              <div class="form-group">
                        <input type="submit" class="btn btn-danger" value="Update Order"/>
                        <a href="/order-list" class="btn btn-default">Cancel</a>
                </div>
                 <!-- /.form buttonss -->

            </div>

          <!-- /.card -->

        </div>
        <!-- /.col (right) -->
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container-fluid -->
</form>
  </section>
  <!-- /.content -->

@endsection
