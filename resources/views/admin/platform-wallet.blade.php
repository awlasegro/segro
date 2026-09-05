@extends('admin.layout.master')

@section('title', 'Platform Wallet Settings')

@section('content')
 <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Platform Wallet Settings</h1>
        </div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-8 offset-2">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Configure Deposit Address</h3>
            </div>
            
            <form action="{{ route('admin.platform.wallet.update') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                @if (session('success'))
                  <div class="alert alert-success">
                    {{ session('success') }}
                  </div>
                @endif
                
                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                      @endforeach
                    </ul>
                  </div>
                @endif

                <div class="form-group">
                  <label for="wallet_type">Wallet Network Type</label>
                  <input type="text" name="wallet_type" class="form-control wallet-input" id="wallet_type" value="{{ old('wallet_type', $wallet->wallet_type) }}" placeholder="e.g. USDT TRC20" required readonly>
                </div>

                <div class="form-group">
                  <label for="wallet_address">Wallet Address</label>
                  <input type="text" name="wallet_address" class="form-control wallet-input" id="wallet_address" value="{{ old('wallet_address', $wallet->wallet_address) }}" placeholder="USDT Wallet Address" required readonly>
                </div>

                <div class="form-group">
                  <label for="qr_code">QR Code Image</label>
                  <div class="mb-3">
                    <img src="{{ asset($wallet->qr_code) }}" alt="Current QR Code" style="max-height: 150px; border: 1px solid #ddd; border-radius: 6px; padding: 5px;">
                  </div>
                  <div class="input-group" id="qr-input-container" style="display: none;">
                    <div class="custom-file">
                      <input type="file" name="qr_code" class="custom-file-input wallet-input" id="qr_code" accept="image/*" disabled>
                      <label class="custom-file-label" for="qr_code">Choose new QR image</label>
                    </div>
                  </div>
                  <small class="text-muted" id="qr-hint" style="display: none;">Leave empty if you don't want to change the QR code.</small>
                </div>
              </div>

              <div class="card-footer d-flex" style="gap: 10px;">
                <button type="button" id="edit-btn" onclick="enableEditing()" class="btn btn-warning">Edit Settings</button>
                <button type="submit" id="save-btn" class="btn btn-primary" style="display: none;">Save Settings</button>
                <button type="button" id="cancel-btn" onclick="cancelEditing()" class="btn btn-secondary" style="display: none;">Cancel</button>
              </div>
            </form>
          </div>

          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Order Settings</h3>
            </div>

            <form action="{{ route('admin.order.settings.update') }}" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="selected_order_commission_rate">Selected Order Commission Rate (%)</label>
                  <input type="number" step="0.01" min="0" max="100" name="selected_order_commission_rate" class="form-control" id="selected_order_commission_rate" value="{{ old('selected_order_commission_rate', $orderSettings->selected_order_commission_rate) }}" required>
                  <small class="text-muted">Applied to orders matched to an admin-curated "selected order" slot (order-queue picks), instead of the user's membership commission rate.</small>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save Order Settings</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('scripts')
<script>
  function enableEditing() {
    // Enable inputs
    document.querySelectorAll('.wallet-input').forEach(function(el) {
      el.removeAttribute('readonly');
      el.removeAttribute('disabled');
    });
    
    // Show file upload input
    document.getElementById('qr-input-container').style.display = 'flex';
    document.getElementById('qr-hint').style.display = 'block';
    
    // Toggle action buttons
    document.getElementById('edit-btn').style.display = 'none';
    document.getElementById('save-btn').style.display = 'inline-block';
    document.getElementById('cancel-btn').style.display = 'inline-block';
  }

  function cancelEditing() {
    // Simply reload the page to discard any typing changes and relock
    window.location.reload();
  }

  // Display selected file name inside Bootstrap custom-file input field
  document.getElementById('qr_code').addEventListener('change', function(e){
    if (e.target.files.length > 0) {
      var fileName = e.target.files[0].name;
      var nextSibling = e.target.nextElementSibling;
      nextSibling.innerText = fileName;
    }
  });
</script>
@endsection
