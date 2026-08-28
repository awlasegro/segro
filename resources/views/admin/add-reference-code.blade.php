@extends('admin.layout.master')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Reference Code</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Add Reference Code</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

        <form action="{{ url('create-reference-code') }}" method="post">
            @csrf
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Reference Code</h3>
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
               <!--Account Title -->
               <div class="form-group">
                <label>Reference Code </label>
                  <div class="input-group date" id="reservationdatetime" data-target-input="nearest">
                      <input type="text" name="reference_code" placeholder="Add Reference Code" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->


              <!-- form Buttons -->
              <div class="form-group">
                        <input type="reset" class="btn btn-default" value="OK"/>
                        <input type="submit" class="btn btn-danger" value="Add Reference Code"/>
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
