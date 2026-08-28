@extends('admin.layout.master')

@section('title', 'Set Single Continuous')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Select Orders</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Reset Orders</li>
                </ol>
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
                        <h3 class="card-title">Select Exactly Three Orders</h3>
                    </div>
                    <div class="card-body">
                        <form id="select-orders-form" action="{{ route('save_selected_orders', $user->id) }}" method="POST">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0" style="padding-left: 15px;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <!-- Order After Input -->
                            <div class="form-group">
                                <label>After Order Number:</label>
                                <input type="text" name="order_after" class="form-control" placeholder="Enter order number after which these orders will be received" required>
                            </div>

                            <!-- Alert Display for Selected Orders -->
                            <div id="alert-container" class="mt-3" style="display: none;">
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>Selected Orders:</strong>
                                    <span id="selected-orders-alert"></span>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-warning">Save Selected Orders</button>
                            </div>

                            <!-- Table for Selecting Orders -->
                            <div class="form-group mt-3">
                                <label>Select Orders:</label>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order_list as $item)
                                                <tr>
                                                    <td>{{ $item->title }}</td>
                                                    <td>{{ $item->price }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary select-order-btn"
                                                            data-id="{{ $item->id }}"
                                                            data-title="{{ $item->title }}"
                                                            data-price="{{ $item->price }}">
                                                            Select
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </form>

                        <!-- Display Orders in Table Format (Session Data) -->
                        @if (session('selected_orders'))
                            <div class="table-responsive mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Title</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (session('selected_orders') as $order)
                                            <tr>
                                                <td>{{ $order['id'] }}</td>
                                                <td>{{ $order['title'] }}</td>
                                                <td>{{ $order['price'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const alertContainer = document.getElementById('alert-container');
        const selectedOrdersAlert = document.getElementById('selected-orders-alert');
        let selectedOrders = [];

        // Use event delegation to support DataTable pagination and search clicks
        document.addEventListener('click', function (e) {
            const button = e.target.closest('.select-order-btn');
            if (button) {
                const orderId = button.getAttribute('data-id');
                const orderTitle = button.getAttribute('data-title');
                const orderPrice = button.getAttribute('data-price');

                const existingOrder = selectedOrders.find(order => order.id === orderId);
                if (!existingOrder) {
                    selectedOrders.push({ id: orderId, title: orderTitle, price: orderPrice });
                    
                    // Visual row feedback
                    button.disabled = true;
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                    button.innerText = 'Selected';
                    
                    updateSelectedOrdersDisplay();
                }
            }
        });

        // Global function to remove dynamic selected items
        window.removeSelectedOrder = function(index) {
            const order = selectedOrders[index];
            
            // Find and restore selection button if visible in DOM
            const button = document.querySelector(`.select-order-btn[data-id="${order.id}"]`);
            if (button) {
                button.disabled = false;
                button.classList.remove('btn-success');
                button.classList.add('btn-primary');
                button.innerText = 'Select';
            }
            
            selectedOrders.splice(index, 1);
            updateSelectedOrdersDisplay();
        };

        // Render preview badge list
        function updateSelectedOrdersDisplay() {
            if (selectedOrders.length > 0) {
                alertContainer.style.display = 'block';
                selectedOrdersAlert.innerHTML = selectedOrders.map((order, idx) => 
                    `<span class="badge badge-info p-2 mr-2 mb-2" style="font-size: 13.5px; border-radius: 4px; display: inline-flex; align-items: center; gap: 6px;">
                        <strong>${order.title}</strong> ($${order.price})
                        <a href="javascript:void(0)" onclick="removeSelectedOrder(${idx})" class="text-white" style="font-size: 16px; font-weight: 700; text-decoration: none; line-height: 1;">&times;</a>
                     </span>`
                ).join(' ');
            } else {
                alertContainer.style.display = 'none';
                selectedOrdersAlert.innerHTML = '';
            }
        }

        // Form submission appender
        const selectForm = document.getElementById('select-orders-form');
        if (selectForm) {
            selectForm.addEventListener('submit', function (e) {
                // Remove any old appended inputs first
                this.querySelectorAll('input[name="selected_orders[]"]').forEach(el => el.remove());
                
                selectedOrders.forEach(order => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'selected_orders[]';
                    hiddenInput.value = order.id;
                    this.appendChild(hiddenInput);
                });
            });
        }
    });
</script>
@endsection
