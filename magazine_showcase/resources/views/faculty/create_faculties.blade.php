@extends('layouts.back_end')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

                <div class="card p-3">

                    <form action="{{ action('FacultyController@action') }}" method="post">
                        @csrf

                    <h3 class="tile-title text-center">Create Faculty</h3>

                    <div class="tile-body">

                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input name="name" class="form-control" type="text" placeholder="Enter full name">
                        </div>

                    </div>

                    <div class="tile-footer text-right">

                        <button class="btn btn-primary" type="submit" name="action" value="store">Create</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="/faculties"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>

                    </div>

                    </form>

                </div>

        </div>
    </div>
@endsection
