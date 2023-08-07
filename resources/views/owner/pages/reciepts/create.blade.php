@extends('owner.layouts.layout')
@section('content')

<div class="container-fluid">      
    <form action="{{route('owner.reciept.add')}}" method="POST">
        @csrf
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h3 class="text-white">ADD Reciept</h3>
            </div>
            <div>
                <a href="{{route('owner.reciept.add')}}" class="btn btn-danger h-8">Cancel</a>
                <button type="submit" class="btn btn-primary h-8">+ Add</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <h4 class="text-white">Initial Information</h4>
                <div class="p-4 border mb-3" style="background-color: #323334; border-radius: 25px; border-color: #aaa; ">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label class="text-white">ARTIST:</label>
                                <select name="user" id="user" class="form-control bg-dark border-0 text-white">
                                    <option value="">Select a user</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">Date:</label>
                            <input type="date" name="date" class="form-control bg-dark text-white" placeholder="Release Date">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">Cost:</label>
                            <input type="number" name="cost" class="form-control bg-dark text-white" placeholder="Cost">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    

</div>


@endsection
