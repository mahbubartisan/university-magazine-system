@extends('layouts.back_end')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <form action="{{ route('manage-users') }}" method="post">
                @csrf

                <input name="user_id" class="form-control" type="hidden" value="{{$user->id}}">

                <div class="card p-3">

                    <h3 class="tile-title text-center">Update User</h3>

                    <div class="tile-body">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input name="name" class="form-control" type="text" value="{{$user->name}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">E-mail</label>
                            <input name="email" class="form-control" type="text" value="{{$user->email}}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Gander</label>
                            <input name="gander" class="form-control" type="text" value="{{$user->gander}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Role</label>
                            <select name="user_type" id="user_type" class="form-control">
                                    <option value="Marketing Manager" {{Auth::user()->user_type != 'Administrator' ? 'hidden':''}}>Marketing Manager</option>
                                    <option value="Marketing Coordinator" {{$user->user_type == 'Marketing Coordinator' ? 'selected':''}}>Marketing Coordinator</option>
                                    <option value="Student" {{$user->user_type == 'Student' ? 'selected':''}}>Student</option>
                                    <option value="Guest" {{$user->user_type == 'Guest' ? 'selected':''}}>Guest</option>
                            </select>
                        </div>
                        <div class="form-group" id="faculty">
                            <label class="control-label">Faculty</label>
                            <select name="faculty" class="form-control{{ $errors->has('faculty') ? ' is-invalid' : '' }}">
                                @foreach($faculties as $faculty)
                                    @foreach($user->user_faculty as $u_f)
                                        <option value="{{$faculty->id}}" {{$faculty->name == $u_f->name ? 'selected':''}}>{{$faculty->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="tile-footer text-right">

                        <button class="btn btn-primary" type="submit" name="action" value="update">Update</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="/manage-users"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>

                    </div>

                </div>

            </form>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script>

        //alert("working");
        // $("#faculty").hide();
        $('#user_type').change(function(){
            if($('#user_type').val() == 'Marketing Manager') {
                $('#faculty').hide();
            } else {
                $('#faculty').show();
            }
        });

    </script>

@endsection
