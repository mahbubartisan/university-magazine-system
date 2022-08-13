@extends('layouts.back_end')

@section('content')

    <div class="card text-left">

        <div class="card-body">

            <h4 class="card-title mb-3">Manage user</h4>
            <form action="{{ route('manage-users') }}" method="post">
                @csrf
                <button class="btn btn-primary float-right" name="action" value="create" style="margin-top: -3%;"><i class="fa fa-plus"></i>Add User</button>
            </form>
            <br>
            <div class="table-responsive">
                <table id="userTable" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gander</th>
                        <th>Role</th>
                        <th>Faculty</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->gander}}</td>
                            <td>{{$user->user_type}}</td>
                            <td>
                                @foreach($user->user_faculty as $user_faculty)
                                    {{$user_faculty->name}}
                                @endforeach
                            </td>
                            <td><span class="badge badge-{{$user->status == 'enabled' ? 'success':'danger'}}">{{$user->status}}</span></td>
                            <td width="120px;" class="text-center">
                                <table>
                                    <form action="{{ route('manage-users') }}" method="post">
                                        @csrf

                                        <input type="hidden" name="user_id" value="{{$user->id}}">

                                        <button type="submit" class="btn btn-success" name="action" value="enable" {{ $user->status == 'enabled' ? 'hidden':''}} {{ $user->user_type == 'Administrator' ? 'hidden':'' }} {{Auth::user()->user_type == 'Marketing Manager' && $user->user_type == 'Marketing Manager' ? 'hidden':''}}><i class="i-Approved-Window"></i></button>

                                        <button type="submit" class="btn btn-warning" name="action" value="disable" {{ $user->status == 'disabled' ? 'hidden':''}} {{ $user->user_type == 'Administrator' ? 'hidden':'' }} {{Auth::user()->user_type == 'Marketing Manager' && $user->user_type == 'Marketing Manager' ? 'hidden':''}}><i class="i-Danger"></i></button>

                                        <button type="submit" class="btn btn-info" name="action" value="edit" {{ $user->user_type == 'Administrator' ? 'hidden':'' }} {{Auth::user()->user_type == 'Marketing Manager' && $user->user_type == 'Marketing Manager' ? 'hidden':''}}><i class="i-Pen-4"></i></button>

                                        <button type="submit" class="btn btn-danger" name="action" value="delete" {{ $user->user_type == 'Administrator' ? 'hidden':'' }} {{Auth::user()->user_type == 'Marketing Manager' && $user->user_type == 'Marketing Manager' ? 'hidden':''}}><i class="i-Remove"></i></button>

                                    </form>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

@endsection
