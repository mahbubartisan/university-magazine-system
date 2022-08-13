@extends('layouts.back_end')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <form action="{{ route('faculties.action') }}" method="post">
                @csrf

                <div class="card p-3">

                    <h3 class="tile-title text-center">Update Faculty</h3>

                    <div class="tile-body">

                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input name="faculty_id" class="form-control" type="hidden" value="{{$faculty->id}}">
                            <input name="name" class="form-control" type="text" value="{{$faculty->name}}">
                        </div>

                    </div>

                    <div class="tile-footer text-right">

                        <button class="btn btn-primary" type="submit" name="action" value="update"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="/faculties"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
