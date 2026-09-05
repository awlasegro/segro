@extends('admin.layout.master')

@section('title', 'Memberships')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>All Membership Level List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Membership Level</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
             <a href="{{ url('add-memberships') }}" class="btn btn-primary btn-flat">New Membership Level</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Membership Level</th>
                  <th>Order Limit</th>
                  <th>Commission</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($memberships as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->level_name }}</td>
                  <td>{{ $item->order_limit }}</td>
                  <td>{{ $item->commission }}%</td>
                  <td>
                      <a href="{{ route('memberships.edit', $item->id) }}" class="btn btn-primary btn-flat">Edit</a>
                      <form action="{{ route('memberships.destroy', $item->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this membership level?');">
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
                  <th>Membership Level</th>
                  <th>Order Limit</th>
                  <th>Commission</th>
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
