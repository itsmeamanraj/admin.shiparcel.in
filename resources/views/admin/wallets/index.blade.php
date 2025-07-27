@extends('layouts.admin')

@section('title', 'Dashboard')

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
            <h5 class="card-title mb-0">Users Wallet Transactions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table basic-border-table mb-0">
                    <thead>
                        <tr>
                            <th>Sno</th>
                            <th>User Id</th>
                            <th>User Name</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = ($wallets->currentPage() - 1) * $wallets->perPage() + 1; @endphp
                        @foreach($wallets as $user)
                        <tr>
                            <td>
                                <a href="javascript:void(0)" class="text-primary-600">#{{ $i }}</a>
                            </td>
                            <td>{{ $user->user->id }}</td>
                            <td>{{ $user->user->name }}</td>
                            <td>{{ $user->amount }}</td>
                            <td>{{ $user->status_label }}</td>

                            <td>{{ $user->created_at }}</td>
                            <td>
                                @if($user->status!=101)
                                <a href="javascript:void(0)" onclick="openStatusModal({{ $user->id }}, {{ $user->status }})"
                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                </a>
                                @endif
                            </td>
                            <!-- Modal -->
                            <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Transaction Status</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                        </div>
                                        <form id="statusForm" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <input type="hidden" name="wallet_id" id="wallet_id">
                                                <div class="form-group">
                                                    <label for="status">Select Status:</label>
                                                    <select name="status" id="status" class="form-control">
                                                        @foreach(\App\Models\WalletTransaction::getStatusOptions() as $key => $value)
                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
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
                            {{ $wallets->links() }}
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

    function openStatusModal(walletId, currentStatus) {
        document.getElementById('wallet_id').value = walletId;
        document.getElementById('status').value = currentStatus;
        document.getElementById('statusForm').action = `/admin/wallets/${walletId}/update-status`;
        $('#statusModal').modal('show');
    }
</script>
@endsection