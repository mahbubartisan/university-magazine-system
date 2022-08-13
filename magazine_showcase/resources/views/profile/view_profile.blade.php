@extends('layouts.back_end')

@section('content')

    <div class="col-md-8 offset-md-2">
        <div class="card p-3">
            <h3 class="tile-title">Profile</h3>
            <div class="tile-body">
                <form>
                    <div class="form-group">
                        <label class="control-label">Name</label>
                        <input class="form-control" name="name" type="text" value="{{Auth::user()->name}}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Email</label>
                        <input class="form-control" name="email" type="text" value="{{Auth::user()->email}}" readonly>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Gender</label>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="gender" checked>{{ucfirst(Auth::user()->gander)}}
                            </label>
                        </div>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input class="form-check-input" type="radio" name="gender">{{Auth::user()->gander == 'male' ? 'Female':'Male'}}
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">User Role</label>
                        <input class="form-control" type="text" name="user_type" value="{{Auth::user()->user_type}}" readonly>
                    </div>
                    @if(Auth::user()->user_type != 'Administrator')
                        @if(Auth::user()->user_type != 'Manager')
                            <div class="form-group">
                                <label class="control-label">User Faculty</label>
                                <input class="form-control" type="text" name="user_type" value="@foreach(Auth::user()->user_faculty as $faculty) {{$faculty->name}} @endforeach" readonly>
                            </div>
                        @endif
                    @endif
                </form>
            </div>
            {{--<div class="tile-footer">
                <button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>
            </div>--}}
        </div>

@endsection
