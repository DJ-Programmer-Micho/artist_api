@extends('dashboard.layouts.layout')
@section('content')
<style>
    table {
    width: 100%;
    border-collapse: collapse;
    }

    td.center {
        text-align: center;
    }
</style>
<div class="container-fluid">
    <div class="card hovercard mb-3">
        <div class="cardheader"
            style="background: url('{{ $bannerImageUrl }}') no-repeat center center; background-size: cover;">
        </div>
        <div class="avatar">
            <img alt="" src="{{$thumbnails}}">
        </div>



        <div class="info">
            <div class="title">
                <h2 class="text-white">{{$channelName}}</h2>
            </div>
            <div class="text-white">{{$customUrl}} - {{$country}}</div>

            <div class="text-white">{{$description}}</div>
        </div>
    </div>
    <!-- Table to display data -->

    <form action="{{ route('artist.payment', ['artist' => $artist]) }}" method="GET"
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
                        <th class="align-middle text-center">ID</th>
                        <th class="align-middle text-center">Image</th>
                        <th class="align-middle text-center">Title</th>
                        <th class="align-middle text-center">Auto Renew</th>
                        <th class="align-middle text-center">Status</th>
                        <th class="align-middle text-center">Days Remaining</th>
                        <th class="align-middle text-center">Last Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($songs as $index => $song)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle text-center"><img src="https://lh3.googleusercontent.com/{{ $song['image'] }}" alt="https://lh3.googleusercontent.com/{{ $song['image'] }}" width="50"></td>
                            <td class="align-middle text-center">{{ $song['title'] }}</td>
                            <td class="align-middle text-center text-info">Auto</td>
                            <td class="align-middle text-center">
                                @if ($song['status'] === 'expiring_soon')
                                    Expiring Soon
                                @elseif ($song['status'] === 'expired')
                                    <span class="text-danger">Expired</span>
                                @elseif ($song['status'] === 'active')
                                    <span class="text-success">Active</span>
                                @endif
                            </td>
                            <td class="align-middle text-center">{{ $song['daysDifference'] }} days</td>
                            <td class="align-middle text-center">{{ $song['cost'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Pagination links -->
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="Page navigation ">
                <ul class="pagination justify-content-center pagination-sm d-flex">
                    {{-- Pagination Elements --}}
                    @php
                    $currentPage = $songs->currentPage();
                    $lastPage = $songs->lastPage();
                    $shift = 4; // Set a fixed shift value
                    $start = max(1, $currentPage - $shift);
                    $end = min($lastPage, $currentPage + $shift);

                    if ($end - $start + 1 < 2 * $shift + 1) { $start=max(1, $end - 2 * $shift); } if ($end - $start + 1
                        < 2 * $shift + 1) { $end=min($lastPage, $start + 2 * $shift); } @endphp
                        {{-- Previous Page Link --}} @if ($songs->onFirstPage())
                        <li class="page-item disabled  bg-dark text-white">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $songs->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @endif

                        @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $songs->url(1) }}">1</a>
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
                                <a class="page-link" href="{{ $songs->url($i) }}">{{ $i }}</a>
                            </li>
                            @endif
                            @endfor

                            @if ($end < $lastPage) @if ($end < $lastPage - 1) <li class="page-item disabled">
                                <span class="page-link">...</span>
                                </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $songs->url($lastPage) }}">{{ $lastPage }}</a>
                                </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($songs->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $songs->nextPageUrl() }}" aria-label="Next">
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
@endsection
