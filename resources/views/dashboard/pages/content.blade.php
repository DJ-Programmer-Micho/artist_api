@extends('dashboard.layouts.layout')
@section('content')



                <!-- Main Content -->
                
                  <div class="m-4">
                    <!-- Table to display data -->
                    <div class="row">
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
                                            <td>{{ $row['Earnings (USD)'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                
                    <!-- Pagination links -->
<!-- Pagination links -->
<!-- Pagination links -->
<!-- Pagination links -->
<div class="row">
    <div class="col-md-12">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center pagination-sm d-flex">
                {{-- Pagination Elements --}}
                @php
                    $currentPage = $paginator->currentPage();
                    $lastPage = $paginator->lastPage();
                    $shift = min(4, max(1, 4 - ($currentPage - 1)), max(1, $lastPage - 4 - $currentPage));
                    $start = $currentPage - $shift;
                    $end = $currentPage + $shift;
                @endphp

                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
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

                @for ($i = $start; $i <= $end; $i++)
                    @if ($i == $currentPage)
                        <li class="page-item active" aria-current="page">
                            <span class="page-link">{{ $i }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                        </li>
                    @endif
                @endfor

                @if ($end < $lastPage)
                    @if ($end < $lastPage - 1)
                        <li class="page-item disabled">
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
                
                 
@endsection