@extends('layouts.admin')

@section('title', 'User Courier Rate')

@section('content')
<form id="courierRateForm">
    @csrf
    <input type="hidden" name="user_id" value="{{ request()->user_id }}">
    <input type="hidden" name="courier_company_id" value="{{ request()->company_id }}">
    <div class="dashboard-main-body">
        @foreach($formattedSlabs as $index => $slab)
        @php
        $surfaceTabId = "pills-forward-" . $index;
        $airTabId = "pills-rto-" . $index;
        $surfaceTabContentId = "pills-forward-content-" . $index;
        $airTabContentId = "pills-rto-content-" . $index;
        @endphp

        <div class="card mb-3">
            <div class="card-header">
                <h6>AIR</h6>
                <h5 class="card-title">
                    {{ $slab['company_name'] }} - <span>{{ $slab['weight_slab'].' KG' }}</span>
                </h5>
            </div>
            <input type="hidden" name="weight_slab[{{ $index }}]" value="{{ $slab['weight_slab_id'] }}">

            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab-{{ $index }}" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" data-bs-toggle="pill"
                            data-bs-target="#{{ $surfaceTabContentId }}">Forward</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" data-bs-toggle="pill"
                            data-bs-target="#{{ $airTabContentId }}">RTO</button>
                    </li>
                </ul>

                <div class="tab-content">
                    @foreach(['Forward', 'RTO'] as $mode)
                    @php
                    $tabContentId = ($mode == 'Forward') ? $surfaceTabContentId : $airTabContentId;
                    @endphp

                    <div class="tab-pane fade {{ $mode == 'Forward' ? 'active show' : '' }}" id="{{ $tabContentId }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Shiparcel Zone</th>
                                    <th>Forward - (FWD)</th>
                                    <th>Additional - (FWD)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['A', 'B', 'C', 'D', 'E'] as $zone)
                                @php
                                // Create the key for lookup
                                $lookupKey = "{$slab['weight_slab_id']}_{$mode}_{$zone}";

                                // Get existing rate values if they exist
                                $forwardFwd = $existingRates[$lookupKey]->forward_fwd ?? '';
                                $additionalFwd = $existingRates[$lookupKey]->additional_fwd ?? '';
                                @endphp

                                <tr>
                                    <td>{{ $zone }}</td>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="rates[{{ $index }}][{{ $mode }}][{{ $zone }}][forward_fwd]"
                                            value="{{ $forwardFwd }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="rates[{{ $index }}][{{ $mode }}][{{ $zone }}][additional_fwd]"
                                            value="{{ $additionalFwd }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>


                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <button type="submit" id="submit_form" class="btn btn-primary">Save Rates</button>
</form>

<!-- jQuery (must be included before Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    jQuery(document).ready(function($) {
        $('#courierRateForm').submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();

            $.ajax({
                url: "{{ route('save.courier.air.rates') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}" // CSRF Token
                },
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>
@endsection