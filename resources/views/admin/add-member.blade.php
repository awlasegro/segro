@extends('admin.layout.master')

@section('title', 'Add User')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Member</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Add Member</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- /.col (left) -->
                <div class="col-md-8 offset-2">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Member Data</h3>
                        </div>
                        @if ($errors->any())
                            <div class="form-group text-center">
                                @foreach ($errors->all() as $error)
                                    <p style="color: red;">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                        <form action="{{ URL('add-member') }}" method="post">
                            @csrf
                            <div class="card-body">
                                <div class="form-group"><label>Username</label><input type="text" name="username"
                                        value="{{ old('username') }}" placeholder="Enter Username" class="form-control"
                                        required pattern="^[a-zA-Z0-9._]+$" /></div>
                                <div class="form-group"><label>Parent ID</label><input type="number" name="parentUser"
                                        value="{{ old('parentUser') }}" placeholder="Enter Parent ID" class="form-control"
                                        required /></div>
                                <div class="form-group"><label>Phone Number</label><input type="text" name="phone"
                                        value="{{ old('phone') }}" placeholder="Enter Phone Number" class="form-control"
                                        required /></div>
                                <div class="form-group"><label>Email Address</label><input type="email" name="email"
                                        value="{{ old('email') }}" placeholder="Enter Email Address" class="form-control"
                                        required /></div>
                                <div class="form-group"><label>Password</label><input type="password" name="password"
                                        placeholder="*********" class="form-control" required /></div>
                                <div class="form-group"><label>Confirm Password</label><input type="password"
                                        name="password_confirmation" placeholder="*********" class="form-control"
                                        required /></div>
                                <div class="form-group"><label>Wallet Password</label><input type="password"
                                        name="vallet_password" placeholder="*********" class="form-control" required />
                                </div>
                                <div class="form-group"><label>Credibility</label><input type="number" name="credibility"
                                        value="{{ old('credibility', 100) }}" placeholder="Enter Credibility"
                                        class="form-control" min="0" max="100" required /></div>
                                <div class="form-group"><label>Opening Balance</label><input type="number"
                                        name="op_balance" value="{{ old('op_balance', 15) }}"
                                        placeholder="Enter Opening Balance" class="form-control" min="0"
                                        step="0.01" required /></div>
                                <div class="form-group"><label>Minimum Withdraw</label><input type="number"
                                        name="min_withdraw" value="{{ old('min_withdraw', 50) }}"
                                        placeholder="Set Minimum Withdraw Amount" class="form-control" min="0"
                                        step="0.01" required /></div>
                                <div class="form-group"><label>Maximum Withdraw</label><input type="number"
                                        name="max_withdraw" value="{{ old('max_withdraw', 3000) }}"
                                        placeholder="Set Maximum Withdraw Amount" class="form-control" min="0"
                                        step="0.01" required /></div>
                                @if (Auth::user()->user_type == 1)
                                    <div class="form-group"><label>User Type</label><select name="userType"
                                            class="form-control" required>
                                            <option value="">Select User Type</option>
                                            <option value="0" {{ old('userType') == '0' ? 'selected' : '' }}>User
                                            </option>
                                            <option value="2" {{ old('userType') == '2' ? 'selected' : '' }}>Moderator
                                            </option>
                                            <option value="1" {{ old('userType') == '1' ? 'selected' : '' }}>Admin
                                            </option>
                                        </select></div>
                                @else
                                    <input type="hidden" name="userType" value="0">
                                @endif
                                <div class="form-group"><label>Membership Level</label><select name="memLevel"
                                        class="form-control" required>
                                        <option value="">Select Membership Level</option>
                                        @foreach ($memberships as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('memLevel') == $item->id ? 'selected' : '' }}>
                                                {{ $item->level_name }}</option>
                                        @endforeach
                                    </select></div>
                                <div class="form-group"><input type="submit" class="btn btn-danger btn-flat"
                                        value="Save Member Data" /><input type="reset" class="btn btn-default btn-flat"
                                        value="Reset" /></div>
                            </div>
                        </form>
                        <!-- /.card -->

                    </div>
                    <!-- /.col (right) -->
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection
