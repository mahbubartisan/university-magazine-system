@extends('layouts.back_end')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <form action="{{ route('contributions.action') }}" method="post" enctype="multipart/form-data">
                @csrf

                <input name="student_contribution_id" class="form-control" type="hidden" value="{{$contribution->id}}">
                <input name="user_id" class="form-control" type="hidden" value="{{Auth::user()->id}}">

                <div class="card p-3">

                    <h3 class="tile-title text-center">Update Contribution</h3>

                    <div class="tile-body">

                        <div class="form-group">
                            <label class="control-label">Title</label>
                            <input name="title" class="form-control" type="text" value="{{$contribution->title}}">
                        </div>

                        <div class="form-group">
                            <label class="control-label">File</label>
                            <input name="file" class="form-control" type="file" value=""><small>Current file - {{$contribution->file}}</small>
                        </div>

                    </div>

                    <div class="tile-footer text-right">

                        <button class="btn btn-primary" type="submit" name="action" value="update"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="/contributions"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
