@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="dashboard-main-body">
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header">
                    <h3 class="card-title">Edit User</h3>
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                </div>
                <form id="userForm" method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" placeholder="Enter Name" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" placeholder="Enter Email" readonly />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password (Leave blank to keep current password)" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="website_url" name="website_url" value="{{ $user->website_url }}" placeholder="Enter Website URL" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Billing Address</label>
                            <textarea class="form-control" id="billing_address" name="billing_address" placeholder="Enter Billing Address">{{ $user->billing_address }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Zipcode</label>
                            <input type="text" class="form-control" id="zipcode" name="zipcode" value="{{ $user->zipcode }}" placeholder="Enter Zipcode" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ $user->city }}" placeholder="Enter City" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="{{ $user->state }}" placeholder="Enter State" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*" />
                            @if ($user->image_url)
                            <img src="{{ asset('storage/' . $user->image_url) }}" class="img-thumbnail mt-2" width="100" />
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">COD Charges</label>
                            <input type="number" class="form-control" id="cod_charges" value="{{ $user->cod_charges }}" name="cod_charges" />
                            <small class="text-danger" id="cod_charges_error"></small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">COD Percentage</label>
                            <input type="number" class="form-control" id="cod_percentage" value="{{ $user->cod_percentage }}" name="cod_percentage" />
                            <small class="text-danger" id="cod_percentage_error"></small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Chargeable Amount</label>
                            <input type="number" class="form-control" id="chargeable_amount" value="{{ $user->chargeable_amount }}" name="chargeable_amount" />
                            <small class="text-danger" id="chargeable_amount_error"></small>
                        </div>

                        <div class="form-group mt-6">
                            <button type="submit" class="btn btn-primary mr-2">Update</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection