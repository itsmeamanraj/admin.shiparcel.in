@extends('layouts.admin')

@section('title', 'Create Warehouse')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="dashboard-main-body">
     <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Upload Pincode</h6>
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
            <div class="card-body">
                <div class="row">
                    {{-- Left: Upload Form --}}
                    <div class="col-md-6">
                        <form method="POST" id="form-builder" action="{{ route('upload.pincode') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card h-100 shadow-sm">
                                 <div class="card-header border-bottom bg-base py-3 px-4">
                                    <span class="fw-semibold">Upload Pincode CSV</span>
                                </div>
                                <div class="card-header border-bottom bg-base py-3 px-4 d-flex justify-content-between">
                                   
                                    <a href="{{ asset('assets/upload_pincode_sample.csv') }}" class="btn btn-sm btn-outline-secondary">
                                        Download Sample
                                    </a>
                                </div>
                                <div class="card-body p-4">
                                    <label for="file-upload-name" class="form-label fw-medium mb-3">
                                        Upload CSV File
                                    </label>

                                    <label class="border border-neutral-600 fw-medium text-secondary-light px-3 py-2 rounded d-inline-flex align-items-center gap-2 bg-hover-neutral-200 cursor-pointer">
                                        <iconify-icon icon="solar:upload-linear" class="text-xl"></iconify-icon>
                                        Click to upload
                                        <input type="file" name="multiple_shipment" accept=".csv" class="form-control d-none" id="file-upload-name">
                                    </label>

                                    <ul id="uploaded-img-names" class="mt-3"></ul>

                                    <div class="d-flex justify-content-right mt-4">
                                        <button class="btn btn-primary px-4 py-2" type="submit">Upload</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                 {{-- Right: Courier List --}}
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header border-bottom bg-base py-3 px-4">
                                <span class="fw-semibold">Available Couriers</span>
                            </div>
                            <div class="card-body p-4">
                                @if(isset($couriers) && count($couriers))
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Courier Name</th>
                                                    <th>Courier ID</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($couriers as $courier)
                                                    <tr>
                                                        <td>{{ $courier->name }}</td>
                                                        <td>{{ $courier->id }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">No couriers found.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

            </div> <!-- end card-body -->
        </div>
    </div>
</div>

@endsection
