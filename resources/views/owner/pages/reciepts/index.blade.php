@extends('owner.layouts.layout')
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

    <div class="d-flex justify-content-between">
        <div>
            <div class="d-none d-sm-inline-block form-inline mr-auto my-2 my-md-0 mw-100 navbar-search">
                <form action="{{ route('owner.reciept') }}" method="GET">
                    <div class="input-group">
                        <select name="user" id="user" class="form-control bg-dark border-0 text-white">

                            <option value="">Select a user</option>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div>
            <a href="{{route('owner.reciept.add')}}" class="btn btn-success">+ Add New Reciept</a>
        </div>
    </div>

    <div class="row mt-3 overflow-auto">
        <div class="col-md-12">
            <table class="table table-bordered table-dark ">
                <thead>
                    <tr>
                        <th class="align-middle text-center">ID</th>
                        <th class="align-middle text-center">Date</th>
                        <th class="align-middle text-center">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $index => $data)
                        <tr>
                            <td class="align-middle">{{ $index + 1 }}</td>
                            <td class="align-middle text-center">{{ $data['Date'] }}</td>
                            <td class="align-middle text-center">$ {{ $data['Amount'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
