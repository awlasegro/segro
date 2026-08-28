@extends('admin.layout.master')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Edit Reference Code</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('reference-codes.index') }}">Reference Codes</a></li>
            <li class="breadcrumb-item active">Edit</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">

        <form action="{{ route('reference-codes.update', $refCode->id) }}" method="post">
            @csrf
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Edit Reference Code</h3>
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
               <!--Reference Code -->
               <div class="form-group">
                <label>Reference Code</label>
                  <div class="input-group">
                      <input type="text" name="reference_code" value="{{ $refCode->code }}" placeholder="Reference Code" class="form-control"/>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Status -->
               <div class="form-group">
                <label>Status</label>
                  <div class="input-group">
                      <select name="status" class="form-control">
                          <option value="active" {{ $refCode->status == 'active' ? 'selected' : '' }}>Active</option>
                          <option value="deactive" {{ $refCode->status == 'deactive' ? 'selected' : '' }}>Deactive</option>
                      </select>
                  </div>
              </div>
              <!-- /.form group -->

              @if ($refCode->used_by_user_id)
                <p class="text-muted">This code was already used by <strong>{{ $refCode->usedBy->name ?? 'a deleted user' }}</strong> on {{ $refCode->used_at?->format('Y-m-d H:i') }}.</p>
              @endif

              <!-- form Buttons -->
              <div class="form-group">
                        <input type="submit" class="btn btn-danger" value="Update Reference Code"/>
                        <a href="{{ route('reference-codes.index') }}" class="btn btn-default">Cancel</a>
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
