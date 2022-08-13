@extends('layouts.back_end')

@section('content')

    <div class="card text-left">

        <div class="card-body">
            <h4 class="card-title mb-3">All Faculties</h4>
            <form action="{{ route('faculties.action') }}" method="post">
                @csrf
                <button class="btn btn-primary float-right" name="action" value="create" style="margin-top: -3%;"><i class="fa fa-plus"></i>Add Faculties</button>
            </form>
            <br>
            <div class="table-responsive">
                <table id="facultyTable" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Serial</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faculties as $faculty)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$faculty->name}}</td>
                                <td>
                                    <table style="font-size: 14pt !important;">
                                        <form action="{{ route('faculties.action') }}" method="post">
                                            @csrf

                                            <input type="hidden" name="faculty_id" value="{{$faculty->id}}">

                                            <button type="submit" class="btn btn-info" name="action" value="edit"><i class="i-Pen-4"></i></button>

                                            <button type="submit" class="btn btn-danger" name="action" value="delete"><i class="i-Remove"></i></button>

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
