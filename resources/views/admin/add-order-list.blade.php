@extends('admin.layout.master')

@section('title', 'Add Order List')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Orders List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Add Orders List</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

        <form action="{{ url('create-orders-list') }}" method="post" enctype="multipart/form-data">
            @csrf
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Orders List</h3>
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
               <!--Membership Level -->
               <div class="form-group">
                <label>Orders List </label>
                  <div class="input-group" id="reservationdatetime" data-target-input="nearest">
                      <input type="text" name="title" placeholder="Enter Title" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
              <!--Order Limit -->
              <div class="form-group">
                <label>Price</label>
                  <div class="input-group" id="reservationdatetime" data-target-input="nearest">
                      <input type="text" name="price" placeholder="Add Price" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Short Description -->
               <div class="form-group">
                <label>Description</label>
                  <div class="input-group" id="reservationdatetime" data-target-input="nearest">
                      <input type="text" name="description" placeholder="Enter Short Description" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Short Description -->
               <div class="form-group">
                <label>Image</label>
                  <div class="input-group" id="reservationdatetime" data-target-input="nearest">
                      <input type="file" name="image" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->



              <!-- form Buttons -->
              <div class="form-group">
                        <input type="reset" class="btn btn-default" value="OK"/>
                        <input type="submit" class="btn btn-danger" value="Add Orders List"/>
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
