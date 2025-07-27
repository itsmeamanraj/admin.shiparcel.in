@extends('layouts.admin')

@section('title', 'User Charges')

@section('content')
<style>
    .modal-header .close {
        margin: -1rem -1rem -1rem auto;
        font-size: 1.5rem;
    }
</style>
<div class="dashboard-main-body">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Update User Charges</h5>
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table basic-border-table mb-0">
                    <thead>
                        <tr>
                            <th>Sno</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Profile Image</th>
                            <th>Chargeable Amount</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="text-primary-600">#{{ $i }}</a>
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->status_label }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $user->image_url) }}" alt="User Image" width="50">
                            </td>
                            <td>@if($user->chargeable_amount)₹{{$user->chargeable_amount.'/-'}}@else{{'-'}}@endif</td>
                            <td>{{ $user->created_at }}</td>
                            <td>
                                <a href="javascript:void(0)" onclick="openStatusModal({{ $user->id }}, {{ $user->chargeable_mount }})"
                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                </a>
                            </td>
                            <!-- Modal -->
                            <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Amount</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                        </div>
                                        <form id="statusForm" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <input type="hidden" name="user_id" id="user_id">
                                                <input type="text" name="chargeable_amount" value="" id="chargeable_amount" class="form-control">
                                                @if ($errors->has('chargeable_amount'))
                                                <span class="text-danger">{{ $errors->first('chargeable_amount') }}</span>
                                                @endif

                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="dt-layout-row">
                    <div class="dt-layout-cell dt-end">
                        <div class="dt-paging paging_full_numbers">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery (must be included before Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap Bundle (includes Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $('.close, .btn-secondary').on('click', function() {
        $('#statusModal').modal('hide');
    });

    function openStatusModal(userId, chargeableAmount) {
        document.getElementById('user_id').value = userId;

        document.getElementById('statusForm').action = `/admin/users/${userId}/update-user-charges`;
        $('#statusModal').modal('show');
    }
</script>
@endsection