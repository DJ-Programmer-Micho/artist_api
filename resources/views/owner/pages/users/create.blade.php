@extends('owner.layouts.layout')
@section('content')

<div class="container-fluid">      
    <form action="{{route('owner.artists.add')}}" method="POST">
        @csrf
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h3 class="text-white">ADD ARTIST</h3>
            </div>
            <div>
                <a href="{{route('owner.artists')}}" class="btn btn-danger h-8">Cancel</a>
                <button type="submit" class="btn btn-primary h-8">+ Add</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <h4 class="text-white">Initial Information</h4>
                <div class="p-4 border mb-3" style="background-color: #323334; border-radius: 25px; border-color: #aaa; ">
                    <div class="form-group">
                        <label class="text-white">NAME:</label>
                        <input type="text" name="name" class="form-control bg-dark text-white" placeholder="Artist Name" pattern="^[^\s]+$" title="No spaces allowed">
                        <small class="text-info">Lowercase and unique</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="text-white">EMAIL:</label>
                            <input type="email" name="email" class="form-control bg-dark text-white" placeholder="Email Address">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">PASSWORD:</label>
                            <input type="password" name="password" class="form-control bg-dark text-white" placeholder="********">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">PROFIT: (0.1 - 0.9):</label>
                            <input type="text" name="profit" class="form-control bg-dark text-white" placeholder="0.6">
                            <small class="text-info">0.6 = 60%</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <h4 class="text-white">Server Information</h4>
                <div class="p-4 border mb-3" style="background-color: #323334; border-radius: 25px; border-color: #aaa; ">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="text-white">Google Sheet ID:</label>
                            <input type="text" name="g_id" class="form-control bg-dark text-white" placeholder="wRfIRT_DpL">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">Sheets:</label>
                            <input type="number" name="s_id" class="form-control bg-dark text-white" placeholder="1-99">
                            <small class="text-info">add only number (1 - 99)</small>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">Youtube Channel:</label>
                            <input type="text" name="c_id" class="form-control bg-dark text-white" placeholder="UCZ4O7Ccuu">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">PASSPORT:</label>
                            <input type="text" name="passport" class="form-control bg-dark text-white" placeholder="A101----">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">PHONE NUMBER:</label>
                            <input type="text" name="phone" class="form-control bg-dark text-white" placeholder="+9647500000000">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    

</div>


@endsection
