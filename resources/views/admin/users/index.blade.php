@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-main-body">

    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">Users List</h6>
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
        <div class="card mb-12">
            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                <form method="GET" action="{{ route('users.index') }}" class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex flex-wrap align-items-center gap-3">

                        <div class="icon-field">
                            <input type="text" name="search" class="form-control form-control-sm w-auto" placeholder="Search" value="{{ request('search') }}">
                            <span class="icon">
                                <iconify-icon icon="ion:search-outline"></iconify-icon>
                            </span>
                        </div>

                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-3">

                        <button title="search" type="submit" class="btn rounded-pill btn-success-100 text-success-600 radius-8 px-20 py-11">
                            <iconify-icon icon="ion:search-outline"></iconify-icon>
                        </button>

                        <a title="reset" href="{{ route('users.index') }}" class="btn rounded-pill btn-neutral-100 text-primary-light radius-8 px-20 py-11">
                            <iconify-icon icon="mdi:refresh"></iconify-icon>
                        </a>

                    </div>
                </form>
                <form style="margin-left: -435px;" method="GET" action="{{ route('users.index') }}" class="d-flex flex-wrap align-items-center gap-3">

                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <select name="status" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="">Status</option>
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>

                        </select>

                    </div>
                </form>

                <div class="d-flex flex-wrap align-items-center gap-3">

                    <a href="{{route('users.create')}}" class="btn btn-sm btn-primary-600"><i class="ri-add-line"></i> Create User</a>
                </div>
            </div>
        </div>
        <div class="card">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="card-body">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <table class="table bordered-table mb-0">
                    <thead>
                        <tr>
                            <th scope="col">
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" value="" id="checkAll">
                                    <label class="form-check-label" for="checkAll">
                                        S.L
                                    </label>
                                </div>
                            </th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = ($users->currentPage() - 1) * $users->perPage() + 1; @endphp
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="form-check style-check d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" value="" id="check1">
                                    <label class="form-check-label" for="check1">
                                        {{ $i }}
                                    </label>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img height="50px" width="50px" src="{{ asset('storage/' . $user->image_url) }}" alt="" class="flex-shrink-0 me-12 radius-8">
                                    <h6 class="text-md mb-0 fw-medium flex-grow-1">{{ $user->name }}</h6>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->status ==1)<span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">{{'Active'}}</span>@else<span class="bg-warning-focus text-warning-main px-24 py-4 rounded-pill fw-medium text-sm">{{'InActive'}}</span>@endif

                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="lucide:edit"></iconify-icon>
                                </a>

                                <a href="javascript:void(0)" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                </a>

                                <a title="Courier Rates" href="{{route('user-weight-slab',$user->id)}}" class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                    <iconify-icon icon="ic:round-settings" width="20" height="20"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>

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
@endsection