@extends('layouts.back_end')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <form action="{{ route('contribution-settings.contribution_settings_action') }}" method="post">
                @csrf

                <input name="contribution_setting_id" class="form-control" type="hidden" value="{{$contribution_setting->id}}">

                <div class="card p-3">

                    <h3 class="tile-title text-center">Update Contribution Setting</h3>

                    <div class="tile-body">
                        <div class="form-group">
                            <label class="control-label">Academic year</label>
                            <input name="academic_year" class="form-control" type="number" value="{{$contribution_setting->academic_year}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Start date</label>
                            <input name="start_date" class="form-control" type="date" value="{{$contribution_setting->start_date}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Closure date</label>
                            <input name="closure_date" class="form-control" type="date" value="{{$contribution_setting->closure_date}}">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Final closure date</label>
                            <input name="final_closure_date" class="form-control" type="date" value="{{$contribution_setting->final_closure_date}}">
                        </div>
                    </div>

                    <div class="tile-footer text-right">

                        <button class="btn btn-primary" type="submit" name="action" value="update">Update</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="/faculties"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
