@extends('layouts.back_end')

@section('content')

    <div class="card text-left">

        <form action="{{ route('contributions.action') }}" method="post" enctype="multipart/form-data">

            <div class="card-body">
                <h4 class="card-title mb-3">Contributions</h4>

                @csrf

                <span>
                    <span class="col-md-6 float-right text-right p-0" style="margin-top: -2%;">
                        <button type="submit" class="btn btn-primary {{Auth::user()->user_type == 'Student'? '':'hidden'}}" name="action" value="downloadSelectedZip" style="margin-top: -3%;"><i class="i-Download"></i> Download Selected</button>
                        @foreach($times as $time)
                            <button class="btn btn-primary" name="action" value="create" {{Auth::user()->user_type == 'Student'? '':'hidden'}} {{ $time->start_date < \Carbon\Carbon::now()/*->addDay(1)*/ ? '':'hidden'}} {{ $time->closure_date >= \Carbon\Carbon::now()->subDay(1) ? '':'disabled'}} {{ $time->final_closure_date <= \Carbon\Carbon::now()->subDay(1)/*date(now())*/ ? 'hidden':''}} style="margin-top: -3%;"><i class="i-Add"></i>Add Contributions</button>
                        @endforeach
                    </span>
                </span>
                <br>

                <div class="table-responsive">
                    <table id="contributionTable" class="display table table-striped table-bordered" style="width:100%">

                        @if(Auth::user()->user_type == 'Marketing Manager' or Auth::user()->user_type == 'Administrator')
                            <thead>
                            <tr>
                                <th>
                                    <label class="checkbox checkbox-primary">
                                        <input type="checkbox" id="checkAll">
                                        <span>All</span>
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th>Serial</th>
                                <th>Name</th>
                                <th>Faculty</th>
                                <th>Title</th>
                                <th>File</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Comments</th>
                                <th>Submitted on</th>
                                <th>Modified on</th>
{{--                                <th>Action</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($contributions as $contribution)
                                <tr>
                                    <td class="text-center">
                                        <label class="checkbox checkbox-primary">
                                            <input name="ids[]" type="checkbox" value="{{$contribution->file}}">
                                            <span></span>
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$contribution->file_user->name}}</td>
                                    <td>{{$contribution->faculty_name}}</td>
                                    <td><a href="/contribution/{{$contribution->id}}">{{$contribution->title}}</a></td>
                                    <td>{{$contribution->file}}</td>
                                    <td>{{$contribution->type}}</td>
                                    <td>{{$contribution->status}}</td>
                                    <td class="text-center"><span class="badge badge-{{count($contribution->contribution_comment) < 1 ? 'danger':'success'}}">{{count($contribution->contribution_comment)}}</span></td>
                                    <td>{{$contribution->created_at->toFormattedDateString()}}</td>
                                    <td>{{$contribution->updated_at->toFormattedDateString()}}</td>
                                    {{--<td>
                                        <table>

                                            <input type="hidden" name="contribution_id" value="{{$contribution->id}}">

                                            <button type="submit" class="btn btn-info" name="action" value="edit" disabled><i class="i-Pen-2"></i></button>

                                            <button type="submit" class="btn btn-danger" name="action" value="delete" disabled><i class="i-Remove"></i></button>

                                        </table>
                                    </td>--}}
                                </tr>
                            @endforeach

                        @elseif(Auth::user()->user_type == 'Marketing Coordinator')

                            <thead>
                            <tr>
                                <th class="text-center">
                                    <label class="checkbox checkbox-primary">
                                        <input type="checkbox" id="checkAll">
                                        <span>All</span>
                                        <span class="checkmark"></span>
                                    </label>
                                </th>
                                <th>Serial</th>
                                <th>Name</th>
                                <th>Title</th>
                                <th>File</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Comments</th>
                                <th>Submitted on</th>
                                <th>Modified on</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($notifications as $notification)
                                <tr>
                                    <td class="text-center">
                                        <label class="checkbox checkbox-primary">
                                            <input name="ids[]" type="checkbox" value="{{$notification->file}}">
                                            <span>All</span>
                                            <span class="checkmark"></span>
                                        </label>
                                    </td>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$notification->file_user->name}}</td>
                                    <td><a href="/contribution/{{$notification->id}}">{{$notification->title}}</a></td>
                                    <td>{{$notification->file}}</td>
                                    <td>{{$notification->type}}</td>
                                    <td class="text-center">
                                        <p class="p-2 badge badge-{{$notification->status == 'Pending'?'warning':'' }}{{ $notification->status == 'Approved'?'success':''}}{{ $notification->status == 'Disapproved'?'danger':''}}">
                                            {{$notification->status}}
                                        </p>
                                    </td>
                                    <td class="text-center"><span class="badge badge-{{count($notification->contribution_comment) < 1 ? 'danger':'success'}}">{{count($notification->contribution_comment)}}</span></td>
                                    <td>{{$notification->created_at->toFormattedDateString()}}</td>
                                    <td>{{$notification->updated_at->toFormattedDateString()}}</td>
                                    <td>
                                        <table>

                                            {{--<input type="text" name="contribution_id[]" value="{{$notification->id}}">--}}

                                            <button type="button" class="btn btn-success" name="action" onclick="change_id('{{$notification->id}}','Approved')" value="Approved" {{$notification->status == 'Approved' ? 'hidden':'' }}><i class="i-Approved-Window"></i></button>

                                            <button type="button" class="btn btn-danger" name="action" onclick="change_id('{{$notification->id}}','Disapproved')" value="Disapproved" {{$notification->status == 'Disapproved' ? 'hidden':'' }}><i class="i-Danger"></i></button>

                                            <button type="button" class="btn btn-warning" name="action" onclick="change_id('{{$notification->id}}','Pending')" value="Pending" {{$notification->status == 'Pending' ? 'hidden':'' }}><i class="i-Loading"></i></button>

                                        </table>
                                    </td>
                                </tr>
                                <script>
                                    function change_id(id,status) {
                                        // alert(route);
                                        var route = window.location.origin+'/contributions-status/'+id+'/'+status;
                                        // var route = 'http://127.0.0.1:8000/contributions-status/'+id+'/'+status;
                                        // alert(route);
                                        window.location.href = route;
                                    }
                                </script>
                            @endforeach

                        @elseif (Auth::user()->user_type == 'Student')

                            <thead>
                                <tr>
                                    <th class="text-center">
                                        <label class="checkbox checkbox-primary">
                                            <input type="checkbox" id="checkAll">
                                            <span>All</span>
                                            <span class="checkmark"></span>
                                        </label>
                                    </th>
                                    <th>Serial</th>
                                    <th>Title</th>
                                    <th>File</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Comments</th>
                                    <th>Submitted on</th>
                                    <th>Modified on</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($student_contributions as $student_contribution)
                                    <tr>
                                        <td class="text-center">
                                            <label class="checkbox checkbox-primary">
                                                <input name="ids[]" type="checkbox" value="{{$student_contribution->file}}">
                                                <span>All</span>
                                                <span class="checkmark"></span>
                                            </label>
                                        </td>
                                        <td>{{$loop->iteration}}</td>
                                        <td><a href="/contribution/{{$student_contribution->id}}">{{$student_contribution->title}}</a></td>
                                        <td>{{$student_contribution->file}}</td>
                                        <td>{{$student_contribution->type}}</td>
                                        <td>
                                            <span class="p-2 badge badge-{{$student_contribution->status == 'Pending'?'warning':'' }}{{ $student_contribution->status == 'Approved'?'success':''}}{{ $student_contribution->status == 'Disapproved'?'danger':''}}">
                                                {{$student_contribution->status}}
                                            </span>
                                        </td>
                                        <td class="text-center"><span class="badge badge-{{count($student_contribution->contribution_comment) < 1 ? 'danger':'success'}}">{{count($student_contribution->contribution_comment)}}</span></td>
                                        <td>{{$student_contribution->created_at->toFormattedDateString()}}</td>
                                        <td>{{$student_contribution->updated_at->toFormattedDateString()}}</td>
                                        <td>
                                            <table>

                                                <input type="hidden" name="stud_cont_id" value="{{$student_contribution->id}}">

                                                @foreach($times as $time)
                                                    <button type="button" class="btn btn-info" onclick="edit_id({{$student_contribution->id}})" name="action" value="edit" {{ $time->start_date < \Carbon\Carbon::now()/*->addDay(1)*/ ? '':'hidden'}} {{ $time->final_closure_date > \Carbon\Carbon::now()->subDay(1) ? '':'disabled'}}><i class="i-Pen-4"></i></button>
                                                    <button type="button" class="btn btn-danger" onclick="delete_id({{$student_contribution->id}})" name="action" value="delete" {{ $time->start_date < \Carbon\Carbon::now()/*->addDay(1)*/ ? '':'hidden'}} {{ $time->final_closure_date >= \Carbon\Carbon::now()->subDay(1) ? '':'disabled'}}><i class="i-Remove"></i></button>
                                                @endforeach

                                            </table>
                                        </td>
                                    </tr>
                                    <script>
                                        function edit_id(id) {
                                            // alert(route);
                                            var route = window.location.origin+'/contributions-edit/'+id;
                                            // var route = 'http://127.0.0.1:8000/contributions-edit/'+id;
                                            // alert(route);
                                            window.location.href = route;
                                        }
                                    </script>
                                    <script>
                                        function delete_id(id) {
                                            // alert(route);
                                            var route = window.location.origin+'/contributions-delete/'+id;
                                            // var route = 'http://127.0.0.1:8000/contributions-delete/'+id;
                                            // alert(route);
                                            window.location.href = route;
                                        }
                                    </script>
                                @endforeach

                            </tbody>

                        @endif

                    </table>
                </div>

            </div>

        </form>

    </div>

@endsection
