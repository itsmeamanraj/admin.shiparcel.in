@extends('layouts.admin')

@section('title', 'Create Warehouse')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="dashboard-main-body">
    <div class="container">
        <div class="card"> 
             <div class="card-body">
                 <h6 class="mb-4 text-xl">Upload Pincode</h6>
                  <p class="text-neutral-500"> Please use the sample CSV format to upload valid pincode and courier ID data.</p>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form method="POST" id="form-builder" action="{{ route ('upload.pincode')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="card h-100">
                                <div class="card-header border-bottom bg-base py-3 px-4">
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

                                    <button class="btn btn-primary mt-4" type="submit">Upload</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
             </div>
        </div>   
    </div>
</div>
@endsection
