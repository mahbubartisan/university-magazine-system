@extends('layouts.back_end')

@section('content')

    <div class="card text-left">

        <div class="card-body">
            <h4 class="card-title mb-3">Contribution Setting</h4>
            <form action="{{ route('contribution-settings.contribution_settings_action') }}" method="post">
                @csrf
                <button class="btn btn-primary float-right" name="action" value="create" style="margin-top: -3%;"><i class="fa fa-plus"></i>Add Contribution Setting</button>
            </form>
            <br>

            <div class="table-responsive">
                <table id="contributionSettingTable" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th>Serial</th>
                        <th>Academic year</th>
                        <th>Start Date</th>
                        <th>Closure date</th>
                        <th>Final closure date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contribution_settings as $contribution_setting)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$contribution_setting->academic_year}}</td>
                            <td>{{$contribution_setting->start_date}}</td>
                            <td>{{$contribution_setting->closure_date}}</td>
                            <td>{{$contribution_setting->final_closure_date}}</td>
                            <td>
                                <table>
                                    <form action="{{ route('contribution-settings.contribution_settings_action') }}" method="post">
                                        @csrf

                                        <input type="hidden" name="contribution_setting_id" value="{{$contribution_setting->id}}">

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
