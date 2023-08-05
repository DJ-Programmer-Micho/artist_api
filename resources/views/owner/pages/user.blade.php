@extends('owner.layouts.layout')
@section('content')

<div class="container-fluid">                                
    <form action="{{ route('owner.artists')}}" method="GET"
        class="d-none d-sm-inline-block form-inline mr-auto my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-dark border-0 text-white" style="height: 32px;"
                placeholder="Search by store or title" aria-label="Search" aria-describedby="basic-addon2" />
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="row mt-3 overflow-auto">
        <div class="col-md-12">
            <table class="table table-bordered table-dark ">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <span class="{{ $user->status == 1 ? 'text-success' : 'text-danger' }}">
                                <b>{{ $user->status == 1 ? __('Active') : __('Non-Active') }}</b>
                            </span>
                        </td>
                        <td>
                            <button type="button" data-toggle="modal" data-target="#updateStudentModal"
                                onclick="editStudent({{ $user->id }})" class="btn btn-primary m-1">
                                <i class="far fa-edit"></i>
                            </button>
                            <button type="button" data-toggle="modal" data-target="#deleteStudentModal"
                                onclick="deleteStudent({{ $user->id }})" class="btn btn-danger m-1">
                                <i class="far fa-trash-alt"></i>
                            </button>
                            <button type="button"
                                onclick="updateStatus({{ $user->id }})"
                                class="btn {{ $user->status == 1 ? 'btn-danger' : 'btn-success' }} m-1">
                                <i class="far {{ $user->status == 1 ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination links -->
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="Page navigation ">
                <ul class="pagination justify-content-center pagination-sm d-flex">
                    {{-- Pagination Elements --}}
                    @php
                    $currentPage = $users->currentPage();
                    $lastPage = $users->lastPage();
                    $shift = 4; // Set a fixed shift value
                    $start = max(1, $currentPage - $shift);
                    $end = min($lastPage, $currentPage + $shift);

                    if ($end - $start + 1 < 2 * $shift + 1) { $start=max(1, $end - 2 * $shift); } if ($end - $start + 1
                        < 2 * $shift + 1) { $end=min($lastPage, $start + 2 * $shift); } @endphp
                        {{-- Previous Page Link --}} @if ($users->onFirstPage())
                        <li class="page-item disabled  bg-dark text-white">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @endif

                        @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $users->url(1) }}">1</a>
                        </li>
                        @if ($start > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                        @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++) @if ($i==$currentPage) <li class="page-item active"
                            aria-current="page">
                            <span class="page-link">{{ $i }}</span>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $users->url($i) }}">{{ $i }}</a>
                            </li>
                            @endif
                            @endfor

                            @if ($end < $lastPage) @if ($end < $lastPage - 1) <li class="page-item disabled">
                                <span class="page-link">...</span>
                                </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->url($lastPage) }}">{{ $lastPage }}</a>
                                </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                                @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&raquo;</span>
                                </li>
                                @endif
                </ul>
            </nav>
        </div>
    </div>
</div>
</div>
</div>


@endsection
