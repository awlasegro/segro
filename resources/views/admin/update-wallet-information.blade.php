@extends('admin.layout.master')

@section('title', 'Update Wallet Information')

@section('content')
 <!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Vallet Information</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active">Vallet Information</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
<form action="{{ route('update.wallet.info') }}" method="POST">
@csrf
      <div class="row">
        <!-- /.col (left) -->
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Vallet Information</h3>
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
                  <div class="input-group">
                        <label for="Name" name="name" class="form-control">{{ $user->name }}</label>
                  </div>
              </div>
              <!-- /.form group -->
               <!--Vallet Information -->
               <div class="form-group">
                <label>Vallet Address</label>
                  <div class="input-group">
                    <input type="text" name="vallet_address" class="form-control" value="{{ $walletInformation->vallet_address }}">
                  </div>
              </div>
              <!-- /.form group -->
              <!--Vallet Type -->
              <div class="form-group">
                <label>Vallet Type</label>
                  <div class="input-group">
                    <select name="wallet_type" id="wallet_type" class="form-control" required>
                        <option value="{{ $walletInformation->type }}">{{ $walletInformation->type }}</option>
                        <option value="TRC20">TRC20</option>
                        <option value="ERC20">ERC20</option>
                        <option value="ETH">ETH</option>
                        <option value="BTC">BTC</option>
                    </select>
                  </div>
              </div>
              <!-- /.form group -->

              <!--Blockchain Option -->
              <div class="form-group">
                <label>Blockchain</label>
                <div class="input-group">
                  <select name="blockchain" class="form-control" required>
                    <option value="{{ $walletInformation->blockchain ?? 'TRON' }}">{{ $walletInformation->blockchain ?? 'TRON' }}</option>
                    <option value="TRON">TRON</option>
                    <option value="Ethereum">Ethereum</option>
                    <option value="Bitcoin">Bitcoin</option>
                    <option value="BSC">BSC</option>
                  </select>
                </div>
              </div>

              <!-- /.form group -->

              <input type="hidden" name="id" value="{{ $walletInformation->id }}">
              <!-- form Buttons -->
              <div class="form-group">
                        <input type="reset" class="btn btn-dark" value="Back to Users"/>
                        <input type="submit" class="btn btn-danger" value="Update Information">
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
