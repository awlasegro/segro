@extends('admin.layout.master')

@section('title', 'Redemption Requests')

@section('content')
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Redemption Requests</h1>
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
              <h3 class="card-title">Pending Withdrawals</h3>
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
                    <th>Amount ($)</th>
                    <th>Wallet Type</th>
                    <th>Wallet Address</th>
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
                      <td>{{ $req->wallet ? strtoupper($req->wallet->type) : 'N/A' }}</td>
                      <td style="font-family: monospace;">{{ $req->wallet ? $req->wallet->vallet_address : 'N/A' }}</td>
                      <td>{{ $req->created_at->format('Y-m-d H:i:s') }}</td>
                      <td>
                        <div class="d-flex" style="gap: 8px;">
                          <form action="{{ route('admin.funds.approve', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to approve this withdrawal?');">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                          </form>
                          <form action="{{ route('admin.funds.reject', $req->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this withdrawal?');">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                  @if($requests->isEmpty())
                    <tr>
                      <td colspan="8" class="text-center text-muted">No pending redemption requests.</td>
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
