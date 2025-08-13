@extends('layouts.admin')

@section('title', 'Update Wallet')

@section('content')
<div class="dashboard-main-body">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
        <h6 class="fw-semibold mb-0">Wallet Recharge</h6>
        <ul class="d-flex align-items-center gap-2">
            <li class="fw-medium">
                <a href="" class="d-flex align-items-center gap-1 hover-text-primary">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                    Dashboard
                </a>
            </li>
            <li>-</li>
            <li class="fw-medium">Users List</li>
        </ul>
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update Wallet</h3>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <form method="POST" action="{{ route('users.wallet.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label">Wallet Amount</label>
                                <input type="number" name="wallet_amount" value="{{ $user->wallet_amount ?? 0 }}" class="form-control" placeholder="Enter amount">
                            </div>

                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary">Update Wallet</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
