@extends('owner.layouts.layout')
@section('content')

<div class="container-fluid">      
    <form action="{{url('/user101/profits/edit/'.$user->id)}}" method="POST">
        @csrf
        <div class="d-flex justify-content-between mb-3">
            <h3 class="text-white">UPDATE ARTIST</h3>
            <button type="submit" class="btn btn-primary h-8">Update</button>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <h4 class="text-white">Initial Information</h4>
                <div class="p-4 border mb-3" style="background-color: #323334; border-radius: 25px; border-color: #aaa; ">
                    <div class="form-group">
                        <label class="text-white">NAME:</label>
                        <input type="text" name="name" class="form-control bg-dark text-white" placeholder="Artist Name" pattern="^[^\s]+$" title="No spaces allowed" value="{{ $user->name }}">
                        <small class="text-info">Lowercase and unique</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="text-white">EMAIL:</label>
                            <input type="email" name="email" class="form-control bg-dark text-white" placeholder="Email Address" value="{{ $user->email }}">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">PASSWORD:</label>
                            <input type="password" name="password" class="form-control bg-dark text-white" placeholder="********">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">PROFIT: (0.1 - 0.9):</label>
                            <input type="text" name="profit" class="form-control bg-dark text-white" placeholder="0.6" value="{{ $user->profile->profit }}">
                            <small class="text-info">0.6 = 60%</small>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">User Status</label><br>
                            <input type="checkbox" name="status" value="1" {{ $user->status ? 'checked' : '' }}>
                            <p class="text-white d-inline">Active or Non-Active</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <h4 class="text-white">Server Information</h4>
                <div class="p-4 border mb-3" style="background-color: #323334; border-radius: 25px; border-color: #aaa;">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="text-white">Google Sheet ID:</label>
                            <input type="text" name="g_id" class="form-control bg-dark text-white" placeholder="wRfIRT_DpL" value="{{ $user->profile->g_id }}">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">Sheets:</label>
                            <input type="number" name="s_id" class="form-control bg-dark text-white" placeholder="1-99" value="{{ count(json_decode($user->profile->s_id)) }}">
                            <small class="text-info">add only number (1 - 99)</small>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">Youtube Channel:</label>
                            <input type="text" name="c_id" class="form-control bg-dark text-white" placeholder="UCZ4O7Ccuu" value="{{ $user->profile->c_id }}">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">PASSPORT:</label>
                            <input type="text" name="passport" class="form-control bg-dark text-white" placeholder="A101----" value="{{ $user->profile->passport }}">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">PHONE NUMBER:</label>
                            <input type="text" name="phone" class="form-control bg-dark text-white" placeholder="+9647500000000" value="{{ $user->profile->passport }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
