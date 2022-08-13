@extends('layouts.back_end')

@section('content')

    <div class="card text-left">

        <div class="card-body">

            @foreach($student_contribution as $output)

                @if(Auth::user()->user_type == 'Administrator' || Auth::user()->user_type == 'Marketing Manager')

                    <h4 class="card-title mb-3">Title: {{$output->title}}</h4>

                    <div class="row pl-3">
                        File: {{$output->file}}
                        <form action="{{ route('comment.action', '$id') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="download_id" value="{{$output->file}}">
                            <button type="submit" class="btn btn-primary float-right ml-2" name="action" value="download_file" style="margin-top: -7px;"><i class="i-Download"></i></button>
                        </form>
                    </div>

                    <br>

                    @foreach($comments as $comment)
                        <div class="row">
                            <div class="col-md-10">
                                <div class="alert alert-secondary">
                                    {{$comment->comment}}
                                </div>
                            </div>
                            <div class="col-md-2">
                                {{\Illuminate\Support\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans()}}
                            </div>
                        </div>
                    @endforeach

                @elseif($output->faculty_id == $faculty_id[0] and Auth::user()->user_type == 'Marketing Coordinator')

                    <h4 class="card-title mb-3">Title: {{$output->title}}</h4>

                    <div class="row pl-3">
                        File: {{$output->file}}
                        <form action="{{ route('comment.action', '$id') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="download_id" value="{{$output->file}}">
                            <button type="submit" class="btn btn-primary float-right ml-2" name="action" value="download_file" style="margin-top: -7px;"><i class="i-Download"></i></button>
                        </form>
                    </div>

                    <br>

                    @foreach($comments as $comment)
                        <div class="row">
                            <div class="col-md-10">
                                <div class="alert alert-secondary">
                                    {{$comment->comment}}
                                </div>
                            </div>
                            <div class="col-md-2">
                                {{\Illuminate\Support\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans()}}
                            </div>
                        </div>
                    @endforeach

                    <form action="{{ route('comment.action','$id') }}" method="post" enctype="form-data">
                        @csrf

                        <input type="hidden" name="contribution_id" value="{{$output->id}}">

                        <div class="row row-xs">
                            <div class="col-md-10 mt-3 mt-md-0">
                                <textarea class="form-control" placeholder="Enter your comment" name="comment"></textarea>
                            </div>
                            <div class="col-md-2 mt-3 mt-md-0">
                                <button type="submit" name="action" value="store" class="btn btn-primary btn-block" {{$output->created_at < \Carbon\Carbon::now()->subDays(14) ? 'disabled':''}}>Submit</button>
                            </div>
                        </div>

                    </form>

                @elseif($output->faculty_id == $faculty_id[0] and Auth::user()->user_type == 'Student')

                    <h4 class="card-title mb-3">Title: {{$output->title}}</h4>

                    <div class="row pl-3">
                        File: {{$output->file}}
                        <form action="{{ route('comment.action', '$id') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="download_id" value="{{$output->file}}">
                            <button type="submit" class="btn btn-primary float-right ml-2" name="action" value="download_file" style="margin-top: -7px;"><i class="i-Download"></i></button>
                        </form>
                    </div>

                    <br>

                    @foreach($comments as $comment)
                        <div class="row">
                            <div class="col-md-10">
                                <div class="alert alert-secondary">
                                    {{$comment->comment}}
                                </div>
                            </div>
                            <div class="col-md-2">
                                {{\Illuminate\Support\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans()}}
                            </div>
                        </div>
                    @endforeach

                @else

                        <h1 class="text-center alert alert-warning">Nothing to show!</h1>

                @endif

            @endforeach

        </div>

    </div>

@endsection
