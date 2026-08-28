@extends('admin.layout.master')

@section('title', 'Deposit Requests')

@section('content')
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Deposit Requests</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Pending Deposits</h3>
            </div>
            <div class="card-body">
              @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
              @endif
              
              <table class="table table-bordered table-striped" id="example1">
                <thead>
                  <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Amount (USDT)</th>
                    <th>Screenshot</th>
                    <th>Request Date</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($requests as $req)
                    <tr>
                      <td>{{ $req->user->id }}</td>
                      <td>{{ $req->user->name }}</td>
                      <td>{{ number_format($req->amount, 2) }}</td>
                      <td>
                        @if($req->image)
                          <a href="{{ asset($req->image) }}" target="_blank">
                            <img src="{{ asset($req->image) }}" alt="Receipt" style="max-height: 80px; border-radius: 4px; border: 1px solid #ddd;">
                          </a>
                        @else
                          <span class="text-muted">No Receipt</span>
                        @endif
                      </td>
                      <td>{{ $req->created_at->format('Y-m-d H:i:s') }}</td>
                      <td>
                        <div class="d-flex" style="gap: 8px;">
                          <form action="{{ route('admin.funds.approve', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this deposit?');">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                          </form>
                          <form action="{{ route('admin.funds.reject', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this deposit?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                  @if($requests->isEmpty())
                    <tr>
                      <td colspan="6" class="text-center text-muted">No pending deposit requests.</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
