@extends('layouts.admin')

@section('title', 'User Charges')

@section('content')

@push('styles')
<!-- Select2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />
@endpush

<div class="dashboard-main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Edit Courier Weight Slab</h5>
        </div>
        <style>
            /* General Switch Styles */
            .switch {
                display: inline-block;
                position: relative;
                width: 50px;
                height: 24px;
            }

            .switch input {
                display: none;
                /* Hide default checkbox */
            }

            .switch label {
                display: block;
                width: 100%;
                height: 100%;
                background: #ccc;
                border-radius: 50px;
                position: relative;
                cursor: pointer;
                transition: background 0.3s;
            }

            .switch label::after {
                content: "";
                width: 20px;
                height: 20px;
                background: white;
                position: absolute;
                top: 2px;
                left: 2px;
                border-radius: 50%;
                transition: all 0.3s;
            }

            .switch input:checked+label {
                background: #007bff;
            }

            .switch input:checked+label::after {
                left: calc(100% - 22px);
            }
        </style>
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Courier Partner</th>
                        <th>Status</th>
                        <th>Express Type</th>
                        <th>Weight Slab</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <tr>
                        @foreach ($courierCompanies as $index => $company)
                        <form action="{{ route('save-weight-slabs') }}" method="post">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $userId }}">
                            <input type="hidden" name="company_id" value="{{ $company->id }}">



                            <th scope="row">{{ $index + 1 }}</th>
                            <td>
                                <input type="hidden" name="company_id" value="{{ $company->id }}">


                                {{ $company->name }}
                            </td>
                            <td>

                                <span class="switch">
                                    <input id="switch-{{ $company->id }}" type="checkbox" class="toggle-switch"
                                        name="courier_status"
                                        data-company-id="{{ $company->id }}"
                                        {{ $company->isChecked ? 'checked' : '' }}>
                                    <label for="switch-{{ $company->id }}"></label>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <input type="checkbox" class="btn-check express-type"
                                        id="air-{{ $company->id }}" name="express_type_air"
                                        value="1" data-company-id="{{ $company->id }}"
                                        {{ $company->expressTypeAir ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary-600 px-3 py-1"
                                        for="air-{{ $company->id }}">Air</label>

                                    <input type="checkbox" class="btn-check express-type"
                                        id="surface-{{ $company->id }}" name="express_type_surface"
                                        value="1" data-company-id="{{ $company->id }}"
                                        {{ $company->expressTypeSurface ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary-600 px-3 py-1"
                                        for="surface-{{ $company->id }}">Surface</label>
                                </div>
                            </td>
                            <td>
                                <div id="weight-slab-container-{{ $company->id }}" class="hidden-section">
                                    <!-- Air Weight Slab -->
                                    <div id="air-weight-slab-{{ $company->id }}" class="hidden-section">
                                        <label>Choose Air Weight Slab:</label>
                                        <select class="form-select weight-slab-select" name="air_weight_slab_ids[]" multiple>
                                            @foreach ($weightSlabs as $slab)
                                            <option value="{{ $slab->id }}"
                                                {{ in_array($slab->id, $company->selectedAirSlabs ?? []) ? 'selected' : '' }}>
                                                {{ $slab->weight }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Surface Weight Slab -->
                                    <div id="surface-weight-slab-{{ $company->id }}" class="hidden-section">
                                        <label>Choose Surface Weight Slab:</label>
                                        <select class="form-select weight-slab-select" name="surface_weight_slab_ids[]" multiple>
                                            @foreach ($weightSlabs as $slab)
                                            <option value="{{ $slab->id }}"
                                                {{ in_array($slab->id, $company->selectedSurfaceSlabs ?? []) ? 'selected' : '' }}>
                                                {{ $slab->weight }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="hidden-section action-buttons" id="buttons-{{ $company->id }}">
                                    <button type="submit" class="btn btn-primary-600 radius-8 px-14 py-6 text-sm">Save</button>
                                    <a class="btn btn-success-600 radius-8 px-14 py-6 text-sm"
                                        href="{{ route('air-courier-rate-slab', ['company_id' => $company->id, 'user_id' => request()->user_id]) }}">
                                        AIR Priority
                                    </a>
                                    <a class="btn btn-warning-600 radius-8 px-14 py-6 text-sm"
                                        href="{{ route('surface-courier-rate-slab', ['company_id' => $company->id, 'user_id' => request()->user_id]) }}">
                                        Surface Priority
                                    </a>
                                </div>
                            </td>
                        </form>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')

<!-- select2 js -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.0/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />




<script>
    $(document).ready(function() {
        $(".weight-slab-select").select2({
            theme: "bootstrap-5",
            width: '100%',
            placeholder: "Choose weight slab",
            closeOnSelect: false,
        });

        $(".toggle-switch").change(function() {
            let companyId = $(this).data("company-id");
            $("#buttons-" + companyId).toggle(this.checked);
        }).each(function() {
            let companyId = $(this).data("company-id");
            $("#buttons-" + companyId).toggle(this.checked);
        });

        $(".express-type").change(function() {
            let companyId = $(this).data("company-id");
            console.log(companyId);

            toggleWeightSlab(companyId);
        }).each(function() {
            let companyId = $(this).data("company-id");
            toggleWeightSlab(companyId);
        });

        function toggleWeightSlab(companyId) {
            let airChecked = $(`#air-${companyId}`).is(":checked");
            let surfaceChecked = $(`#surface-${companyId}`).is(":checked");
            $("#weight-slab-container-" + companyId).toggle(airChecked || surfaceChecked);
            $("#air-weight-slab-" + companyId).toggle(airChecked);
            $("#surface-weight-slab-" + companyId).toggle(surfaceChecked);
        }
    });
</script>
@endsection