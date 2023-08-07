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
                            <td class="align-middle text-center">{{ $data['0'] }}</td>
                            <td class="align-middle text-center">$ {{ $data['1'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
