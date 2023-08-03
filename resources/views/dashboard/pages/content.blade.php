@extends('dashboard.layouts.layout')
@section('content')

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
            <div class="text-secondary text-white">{{$customUrl}} - {{$country}}</div>

            <div class="text-secondary text-white">{{$description}}</div>
        </div>
    </div>
    <!-- Table to display data -->
                                     
    <form action="{{ route('artist.content', ['artist' => $artist]) }}" method="GET"
        class="d-none d-sm-inline-block form-inline mr-auto my-2 my-md-0 mw-100 navbar-search">
        <div class="input-group">
            <input type="text" name="search" class="form-control bg-dark border-0 text-white" style="height: 32px;"
                placeholder="Search by store or title" aria-label="Search" aria-describedby="basic-addon2" />
            <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                    <i class="fas fa-search fa-sm"></i>
                </button>
            </div>
        </div>
    </form>

    <div class="row mt-3">
        <div class="col-md-12">
            <table class="table table-bordered table-dark">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Sale Date</th>
                        <th>Store</th>
                        <th>Title</th>
                        <th>Quantity</th>
                        <th>Earnings (USD)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paginator as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['Sale Month'] }}</td>
                        <td>{{ $row['Store'] }}</td>
                        <td>{{ $row['Title'] }}</td>
                        <td>{{ $row['Quantity'] }}</td>
                        <td>{{'$'.$row['Earnings (USD)'] }}</td>
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
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                    $shift = 4; // Set a fixed shift value
                    $start = max(1, $currentPage - $shift);
                    $end = min($lastPage, $currentPage + $shift);

                    if ($end - $start + 1 < 2 * $shift + 1) { $start=max(1, $end - 2 * $shift); } if ($end - $start + 1
                        < 2 * $shift + 1) { $end=min($lastPage, $start + 2 * $shift); } @endphp
                        {{-- Previous Page Link --}} @if ($paginator->onFirstPage())
                        <li class="page-item disabled  bg-dark text-white">
                            <span class="page-link" aria-hidden="true">&laquo;</span>
                        </li>
                        @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        @endif

                        @if ($start > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
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
                                <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                            </li>
                            @endif
                            @endfor

                            @if ($end < $lastPage) @if ($end < $lastPage - 1) <li class="page-item disabled">
                                <span class="page-link">...</span>
                                </li>
                                @endif
                                <li class="page-item">
                                    <a class="page-link" href="{{ $paginator->url($lastPage) }}">{{ $lastPage }}</a>
                                </li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($paginator->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="Next">
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
