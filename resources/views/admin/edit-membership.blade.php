@extends('admin.layout.master')

@section('title', 'Edit Membership')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Membership Level</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="/memberships">Memberships</a></li>
            <li class="breadcrumb-item active">Edit Membership</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

        <form action="{{ route('memberships.update', $membership->id) }}" method="post">
            @csrf
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Edit Membership Level</h3>
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
                <label>Membership Level Name</label>
                  <div class="input-group">
                      <input type="text" name="membership" value="{{ $membership->level_name }}" placeholder="Add Memberships" class="form-control" required/>
                  </div>
              </div>
              <!-- /.form group -->
              <!--Order Limit -->
              <div class="form-group">
                <label>Order Limit </label>
                  <div class="input-group">
                      <input type="number" name="order_limit" value="{{ $membership->order_limit }}" placeholder="Add Orders Limit" class="form-control" required/>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Commission -->
               <div class="form-group">
                <label>Commission (%)</label>
                  <div class="input-group">
                      <input type="text" name="commission" value="{{ $membership->commission }}" placeholder="Enter Commission" class="form-control" required/>
                  </div>
              </div>
              <!-- /.form group -->

              <!-- form Buttons -->
              <div class="form-group">
                        <input type="submit" class="btn btn-danger" value="Update Membership"/>
                        <a href="/memberships" class="btn btn-default">Cancel</a>
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
