@extends('admin.layout.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>All Reference Codes List</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Reference Codes</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      @if (session('ref_code_success'))
        <div class="alert alert-success">{{ session('ref_code_success') }}</div>
      @endif

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
            </br><button type="button" class="btn btn-primary btn-flat" onclick="generateReferenceCode()">Generate Reference Code</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>Registration Code</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($refCodes as $item)
                <tr>
                  <td>{{ $item->id }}</td>
                  <td>{{ $item->code }}</td>
                  <td>
                    @if ($item->status === 'deactive')
                        <span class="badge badge-danger">Deactivated</span>
                    @endif
                    @if ($item->used_by_user_id)
                        <span class="badge badge-secondary">Used by {{ $item->usedBy->name ?? 'deleted user' }} on {{ $item->used_at?->format('Y-m-d') }}</span>
                    @elseif ($item->status !== 'deactive')
                        <span class="badge badge-success">Available</span>
                    @endif
                  </td>
                  <td>
                    <a href="{{ route('reference-codes.edit', $item->id) }}" class="btn btn-primary btn-flat btn-sm">Edit</a>
                    <form action="{{ route('reference-codes.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this reference code?');">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-flat btn-sm">Delete</button>
                    </form>
                  </td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>ID</th>
                  <th>Registration Code</th>
                  <th>Status</th>
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

  <!-- Generated Code Modal -->
  <div class="modal fade" id="generated-code-modal" tabindex="-1" role="dialog" aria-hidden="true"
       data-generate-url="{{ route('reference-codes.generate') }}" data-csrf-token="{{ csrf_token() }}">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reference Code Generated</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <p>Share this code with the client so they can register:</p>
          <div class="input-group">
            <input type="text" id="generated-code-value" class="form-control text-center" style="font-size: 20px; font-weight: 700;" readonly>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" onclick="copyGeneratedValue('generated-code-value', 'generated-code-copied-msg')">Copy</button>
            </div>
          </div>
          <p id="generated-code-copied-msg" class="text-success mt-2" style="display: none;">Code copied to clipboard.</p>

          <hr>

          <p>Or send this registration link — the code is filled in automatically when the client opens it:</p>
          <div class="input-group">
            <input type="text" id="generated-code-url" class="form-control" style="font-size: 13px;" readonly>
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" onclick="copyGeneratedValue('generated-code-url', 'generated-url-copied-msg')">Copy</button>
            </div>
          </div>
          <p id="generated-url-copied-msg" class="text-success mt-2" style="display: none;">Link copied to clipboard.</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/admin-reference-codes.js') }}"></script>
  @endsection
