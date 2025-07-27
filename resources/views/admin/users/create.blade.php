@extends('layouts.admin')

@section('title', 'Create User')

@section('content')
<div class="dashboard-main-body">
    <div class="card">
        <div class="row">
            <div class="col-md-12">
                <div class="card-header">
                    <h3 class="card-title">Create User</h3>
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                </div>
                <form id="userForm" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" />
                            <small class="text-danger" id="name_error"></small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" />
                            <small class="text-danger" id="email_error"></small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" />
                            <small class="text-danger" id="password_error"></small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="website_url" name="website_url" placeholder="Enter Website URL" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Billing Address</label>
                            <textarea class="form-control" id="billing_address" name="billing_address" placeholder="Enter Billing Address"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Zipcode</label>
                            <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Enter Zipcode" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" placeholder="Enter State" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Profile Image</label>
                            <input type="file" class="form-control" id="image_url" name="image_url" accept="image/*" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">COD Charges</label>
                            <input type="number" class="form-control" id="cod_charges" name="cod_charges" />
                            <small class="text-danger" id="cod_charges_error"></small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">COD Percentage</label>
                            <input type="number" class="form-control" id="cod_percentage" name="cod_percentage" />
                            <small class="text-danger" id="cod_percentage_error"></small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Chargeable Amount</label>
                            <input type="number" class="form-control" id="chargeable_amount" name="chargeable_amount" />
                            <small class="text-danger" id="chargeable_amount_error"></small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="form-group mt-6">
                            <button type="submit" id="submitButton" class="btn btn-primary mr-2">Submit</button>
                            <button type="reset" class="btn btn-secondary">Cancel</button>
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
<script>
    document.getElementById("userForm").addEventListener("submit", function(event) {
        let isValid = true;

        // Name validation
        const name = document.getElementById("name").value.trim();
        if (name === "") {
            document.getElementById("name_error").textContent = "Name is required.";
            isValid = false;
        } else {
            document.getElementById("name_error").textContent = "";
        }

        // Email validation
        const email = document.getElementById("email").value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === "") {
            document.getElementById("email_error").textContent = "Email is required.";
            isValid = false;
        } else if (!emailPattern.test(email)) {
            document.getElementById("email_error").textContent = "Enter a valid email address.";
            isValid = false;
        } else {
            document.getElementById("email_error").textContent = "";
        }

        // Password validation
        const password = document.getElementById("password").value.trim();
        if (password === "") {
            document.getElementById("password_error").textContent = "Password is required.";
            isValid = false;
        } else if (password.length < 6) {
            document.getElementById("password_error").textContent = "Password must be at least 6 characters.";
            isValid = false;
        } else {
            document.getElementById("password_error").textContent = "";
        }


        // cod_charges validation
        const codCharges = document.getElementById("cod_charges").value.trim();
        if (codCharges === "") {
            document.getElementById("cod_charges_error").textContent = "cod_charges is required.";
            isValid = false;
        } else {
            document.getElementById("cod_charges_error").textContent = "";
        }



        // cod_percentage validation
        const codPercentage = document.getElementById("cod_percentage").value.trim();
        if (codPercentage === "") {
            document.getElementById("cod_percentage_error").textContent = "cod_percentage is required.";
            isValid = false;
        } else {
            document.getElementById("cod_percentage_error").textContent = "";
        }

        if (!isValid) {
            event.preventDefault();
        }
    });
</script>
@endsection
