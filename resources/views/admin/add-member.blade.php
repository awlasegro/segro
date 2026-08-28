@extends('admin.layout.master')

@section('title', 'Add User')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Member</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Add Member</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Member Data</h3>
            </div>
            @if ($errors->any())
                <div class="form-group text-center">
                    @foreach ($errors->all() as $error)
                        <p style="color: red;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ URL('add-member') }}" method="post">
                @csrf
            <div class="card-body">
              <!-- Username -->
              <div class="form-group">
                <label>Username</label>
                  <div class="input-group">
                      <input type="text" name="username" placeholder="Enter Username" class="form-control"/>

                  </div>
              </div>
               <!--Password -->
               <div class="form-group">
                <label>Password</label>
                  <div class="input-group">
                      <input type="password" name="password" placeholder="*********" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Re-Enter Password -->
               <div class="form-group">
                <label>Wallet Password</label>
                  <div class="input-group">
                      <input type="password" name="vallet_password" placeholder="*********" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
              @if(Auth::user()->user_type == 1)
              <!--User Type -->
              <div class="form-group">
                <label>User Type</label>
                  <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                      <select name="userType" id="" class="form-control">
                          <option value="">Select User Type</option>
                          <option value="0">User</option>
                          <option value="2">Moderator</option>
                          <option value="1">Admin</option>
                      </select>
                  </div>
              </div>
              <!-- /.form group -->
              @endif

              <!-- form Buttons -->
              <div class="form-group">
                        <input type="submit" class="btn btn-danger btn-flat" value="Save Member Data"/>
                        <input type="reset" class="btn btn-default btn-flat" value="Reset"/>
                </div>
                 <!-- /.form buttonss -->



            </div>
        </form>
          <!-- /.card -->

        </div>
        <!-- /.col (right) -->
      </div>
      <!-- /.row -->

    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->

@endsection
