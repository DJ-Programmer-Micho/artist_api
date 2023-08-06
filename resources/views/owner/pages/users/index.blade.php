@extends('owner.layouts.layout')
@section('content')

<div class="container-fluid">        
    <div class="d-flex justify-content-between">
        <div>

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
    </div>
    <div>
       <a href="{{route('owner.artists.add')}}" class="btn btn-success">+ Add New Artist</a>
    </div>
    
</div>                        
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
                            <a href="{{ url('/user101/artists/edit/'.$user->id) }}" class="btn btn-primary m-1">
                                <i class="far fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-danger m-1" data-toggle="modal" data-target="#confirmDeleteModal{{ $user->id }}">
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Bootstrap modal -->

                    <div class="modal fade" id="confirmDeleteModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel{{ $user->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="confirmDeleteModalLabel{{ $user->id }}">Confirm Deletion</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="user{{ $user->id }}">Enter the user's name ({{ $user->name }}) to confirm:</label>
                                        <input type="text" class="form-control" id="user{{ $user->id }}" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-danger" onclick="checkAndDelete('{{ $user->name }}',{{ $user->id }})">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a id="deleteLink{{ $user->id }}" href="{{ url('/user101/artists/'.$user->id)  }}" style="display: hidden;"></a>

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

<script>
    function checkAndDelete(name, userId) {
        const enteredName = document.getElementById('user' + userId).value;
        const userName = name;

        if (enteredName.toLowerCase() === userName.toLowerCase()) {
            const deleteLink = document.getElementById('deleteLink' + userId);
            deleteLink.click();
        } else {
            alert('Incorrect user name. Please enter the correct name to confirm deletion.');
        }
    }
</script>
@endsection
