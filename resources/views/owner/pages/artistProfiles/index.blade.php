@extends('owner.layouts.layout')
@section('content')

<div class="container-fluid">                                
    <form action="{{ route('owner.profits')}}" method="GET"
        class="d-none d-sm-inline-block form-inline mr-auto my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-dark border-0 text-white"
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
                        <th class="align-middle">ID</th>
                        <th class="align-middle">Name</th>
                        <th class="align-middle">Status</th>
                        <th class="align-middle">Profit</th>
                        <th class="align-middle">%</th>
                        <th class="align-middle">Wallet</th>
                        <th class="align-middle">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($users))
                    @forelse ($users as $index => $user)
                    <tr>
                        <td class="align-middle">{{ $index + 1 }}</td>
                        <td class="align-middle">{{ $user->name }}</td>
                        <td class="align-middle">
                            <span class="{{ $user->status == 1 ? 'text-success' : 'text-danger' }}">
                                <b>{{ $user->status == 1 ? __('Active') : __('Non-Active') }}</b>
                            </span>
                        </td>
                        <td class="align-middle text-success"><b>${{ $totalEarningsArray[$index] }}</b></td>
                        <td class="align-middle text-warning"><b>{{ $user->profile->profit * 100}}%</b></td>
                        <td class="align-middle text-{{ $wallet[$index] > 0 ? 'success' : 'danger' }}"><b>${{ $wallet[$index] }}</b></td>
                        <td class="align-middle">
                            <a href="{{ url('/user101/profits/'.$user->id) }}" class="btn btn-info m-1">
                                <i class="far fa-eye"></i>
                            </a>
                            <a href="{{ url('/user101/profits/edit/'.$user->id) }}" class="btn btn-primary m-1">
                                <i class="far fa-edit"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td>No Data</td>
                    </tr>
                    @endforelse
                    @endif
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
