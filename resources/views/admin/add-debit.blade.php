@extends('admin.layout.master')

@section('title', 'Add Debit')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Add Debit</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Add Debit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
<form action="{{ URL('add-debit') }}" method="post">
@csrf
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Add Debit</h3>
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
                <label>Account Title </label>
                  <div class="input-group ">
                        <label for="Name" name="name" class="form-control">{{ $user->name }}</label>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Balance -->
               <div class="form-group">
                <label>Add Amount</label>
                  <div class="input-group">
                    <input type="number" step="0.01" name="amount" class="form-control" id="input-number" value="0" required/>
                  </div>
              </div>
              <!-- /.form group -->
              <!--Short Deail -->
              <div class="form-group">
                <label>Transaction Type</label>
                  <div class="input-group">
                        <select name="type" id="" class="form-control" required>
                            <option value="">Select Transaction Type</option>
                            <option value="deposit">Deposit</option>
                            <option value="withdrawal">Withdrawl</option>
                        </select>
                  </div>
              </div>
              <!-- /.form group -->

              <input type="hidden" name="id" value="{{ $user->id }}">
              <!-- form Buttons -->
              <div class="form-group">
                        <input type="reset" class="btn btn-default" value="Reset"/>
                        <input type="submit" class="btn btn-danger" value="Add Debit"/>
                </div>
                 <!-- /.form buttonss -->



            </div>

          <!-- /.card -->

        </div>
        <!-- /.col (right) -->
      </div>
      <!-- /.row -->
      </div>
    </form>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content -->

@endsection
