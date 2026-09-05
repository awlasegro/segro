@extends('admin.layout.master')

@section('title', 'Add User')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Add Member</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/administration">Home</a></li>
                    <li class="breadcrumb-item active">Add Member</li>
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
                        <h3 class="card-title">Member Data</h3>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger text-center">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ URL('add-member') }}" method="post">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Enter Username"
                                    class="form-control"
                                    pattern="^[a-zA-Z0-9._]+$"
                                    title="Username can only contain letters, numbers, underscores, and dots. No spaces allowed." />
                            </div>
                            <div class="form-group">
                                <label>Complete Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Complete Name" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>ParentID</label>
                                <input type="text" name="parentUser" value="{{ old('parentUser') }}" placeholder="Enter ParentID" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Enter Phone Number" class="form-control" />
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="text" name="email" value="{{ old('email') }}" placeholder="Enter Email Address" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" placeholder="*********" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Wallet Password</label>
                                <input type="password" name="vallet_password" placeholder="*********" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Credibility</label>
                                <input type="text" name="credibility" value="{{ old('credibility') }}" placeholder="Enter Credibility" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Opening Balance</label>
                                <input type="text" name="op_balance" value="{{ old('op_balance') }}" placeholder="Enter Opening Balance" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Minimum Withdraw</label>
                                <input type="text" name="min_withdraw" value="{{ old('min_withdraw') }}" placeholder="Set Minimum Withdraw Amount" class="form-control" />
                            </div>

                            <div class="form-group">
                                <label>Maximum Withdraw</label>
                                <input type="text" name="max_withdraw" value="{{ old('max_withdraw') }}" placeholder="Set Maximum Withdraw Amount" class="form-control" />
                            </div>

                            @if(Auth::user()->user_type == 1)
                                <div class="form-group">
                                    <label>User Type</label>
                                    <select name="userType" class="form-control">
                                        <option value="">Select User Type</option>
                                        <option value="0" {{ old('userType') == "0" ? 'selected' : '' }}>User</option>
                                        <option value="2" {{ old('userType') == "2" ? 'selected' : '' }}>Moderator</option>
                                        <option value="1" {{ old('userType') == "1" ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                            @endif

                            <div class="form-group">
                                <label>Membership Level</label>
                                <select name="memLevel" class="form-control">
                                    <option value="">Select Membership Level</option>
                                    @foreach ($memberships as $item)
                                        <option value="{{ $item->id }}" {{ old('memLevel') == $item->id ? 'selected' : '' }}>{{ $item->level_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-danger btn-flat" value="Save Member Data" />
                                <input type="reset" class="btn btn-default btn-flat" value="Reset" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('username').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^a-zA-Z0-9._]/g, '');
});
</script>

@endsection
