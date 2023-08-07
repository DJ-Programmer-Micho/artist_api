@extends('owner.layouts.layout')
@section('content')

<div class="container-fluid">      
    <form action="{{route('owner.expire.update')}}" method="POST">
        @csrf
        <div class="d-flex justify-content-between mb-3">
            <div>
                <h3 class="text-white">Update SONG</h3>
            </div>
            <div>
                <a href="{{route('owner.expire')}}" class="btn btn-danger h-8">Cancel</a>
                <button type="submit" class="btn btn-info h-8">+ Update</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-lg-6">
                <h4 class="text-white">Initial Information</h4>
                <div class="p-4 border mb-3" style="background-color: #323334; border-radius: 25px; border-color: #aaa; ">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <img src="https://s3.amazonaws.com/gather.fandalism.com/{{$img}}"  width="350px">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">TITLE:</label>
                            <input type="text" name="title" class="form-control bg-dark text-white" value="{{$title}}">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">UPDATE:</label>
                            <input type="date" name="update" class="form-control bg-dark text-white" placeholder="Release Date">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">COST:</label>
                            <input type="number" name="cost" class="form-control bg-dark text-white" placeholder="Cost">
                        </div>
                        <div class="form-group col-md-12">
                            <label class="text-white">IMAGE LINK:</label>
                            <input type="text" name="image" class="form-control bg-dark text-white" placeholder="Cr5kKd----" value="{{$img}}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="id" value="{{$id}}">
        <input type="hidden" name="g_id" value="{{$g_id}}">
    </form>
    

</div>


@endsection
