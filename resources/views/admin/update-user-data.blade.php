@extends('admin.layout.master')

@section('title', 'Update Users Data')

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
                        <li class="breadcrumb-item active">Update Member</li>
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
                            <div class="alert alert-danger" style="margin: 15px;">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ URL('update-members', $user->id) }}" method="post">
                            @csrf
                            <div class="card-body">
                                <!-- Username -->
                                <div class="form-group">
                                    <label>Username</label>
                                    <div class="input-group" id="reservationdate">
                                        <input type="text" name="username" value="{{ $user->name }}"
                                            class="form-control" />

                                    </div>
                                </div>
                                <!-- Parent account for admin hierarchy -->
                                <div class="form-group">
                                    <label>Parent Account</label>
                                    <div class="input-group">
                                        <select name="parentUser" class="form-control select2bs4" style="width: 100%;">
                                            <option value="">None</option>
                                            @foreach ($users as $existingUser)
                                                <option value="{{ $existingUser->id }}" {{ $user->parent_id == $existingUser->id ? 'selected' : '' }}>
                                                    {{ $existingUser->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--Credibility -->
                                <div class="form-group">
                                    <label>Credibility</label>
                                    <div class="input-group" id="credibility">
                                        <input type="text" name="credibility" value="{{ $user->credibility }}"
                                            class="form-control" />
                                    </div>
                                </div>
                                <!-- /.form group -->
                                <!-- Password -->
                                <div class="form-group">
                                    <label>Password</label>
                                    <div class="input-group" id="password">
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Leave blank to keep current password" />
                                    </div>
                                </div>

                                <!-- Wallet Password -->
                                <div class="form-group">
                                    <label>Wallet Password</label>
                                    <div class="input-group" id="wallet-password">
                                        <input type="password" name="wallet_password" class="form-control"
                                            placeholder="Leave blank to keep current wallet password" />
                                    </div>
                                </div>

                                <!-- Minimum Withdrawal -->
                                <div class="form-group">
                                    <label>Minimum Withdrawal</label>
                                    <div class="input-group" id="min-withdrawal">
                                        <input type="number" name="min_withdrawal" value="{{ $user->min_withdraw }}"
                                            class="form-control" />
                                    </div>
                                </div>

                                <!-- Maximum Withdrawal -->
                                <div class="form-group">
                                    <label>Maximum Withdrawal</label>
                                    <div class="input-group" id="max-withdrawal">
                                        <input type="number" name="max_withdrawal" value="{{ $user->max_withdraw }}"
                                            class="form-control" />
                                    </div>
                                </div>
                                <!-- Users Status -->
                                <div class="form-group">
                                    <label>User Status</label>
                                    <div class="input-group" id="user-status">
                                        <select name="user_status" class="form-control">
                                            <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active
                                            </option>
                                            <option value="deactive" {{ $user->status == 'deactive' ? 'selected' : '' }}>
                                                Deactivate</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Wallet Status -->
                                <div class="form-group">
                                    <label>Wallet Status</label>
                                    <div class="input-group" id="wallet-status">
                                        <select name="wallet_status" class="form-control">
                                            <option value="active" {{ $user->wallet_status == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="deactive"
                                                {{ $user->wallet_status == 'deactive' ? 'selected' : '' }}>Deactivate
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <!--Membership Level -->
                                <div class="form-group">
                                    <label>Membership Level</label>
                                    <div class="input-group" id="credibility">
                                        <select name="memLevel" class="form-control" required>
                                            @foreach ($memberships as $membership)
                                                <option value="{{ $membership->id }}"
                                                    {{ $user->membership_level_id == $membership->id ? 'selected' : '' }}>
                                                    {{ $membership->level_name }}
                                                </option>
                                            @endforeach
                                            @foreach ($memberships as $item)
                                                <option value="{{ $item->id }}">{{ $item->level_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!-- /.form group -->

                                <!-- form Buttons -->
                                <div class="form-group">
                                    <input type="submit" class="btn btn-danger btn-flat" value="Update Member Data" />
                                    <input type="reset" class="btn btn-default btn-flat" value="Reset" />
                                </div>
                                <!-- /.form buttonss -->
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
