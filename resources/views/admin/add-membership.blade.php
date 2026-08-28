@extends('admin.layout.master')

@section('title', 'Add Membership')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Memberships</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Add Memberships</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

        <form action="{{ url('create-memberships') }}" method="post">
            @csrf
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Memberships</h3>
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
                <label>Memberships </label>
                  <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                      <input type="text" name="membership" placeholder="Add Memberships" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
              <!--Order Limit -->
              <div class="form-group">
                <label>Order Limit </label>
                  <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                      <input type="text" name="order_limit" placeholder="Add Orders Limit" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Short Description -->
               <div class="form-group">
                <label>Commision</label>
                  <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                      <input type="text" name="commision" placeholder="Enter Commision" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->


              <!-- form Buttons -->
              <div class="form-group">
                        <input type="reset" class="btn btn-default" value="OK"/>
                        <input type="submit" class="btn btn-danger" value="Add Memberships"/>
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
