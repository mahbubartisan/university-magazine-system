@extends('layouts.back_end')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <form action="{{ route('contribution-settings.contribution_settings_action') }}" method="post">
                @csrf

                <div class="card p-3">

                    <h3 class="tile-title text-center">Create Contribution</h3>

                    <div class="tile-body">
                        <div class="form-group">
                            <label class="control-label">Academic year</label>
                            <input name="academic_year" id="academic_year" class="form-control form-control-sm{{ $errors->has('academic_year') ? ' is-invalid' : '' }}" type="number" placeholder="Enter Academic year" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Start date</label>
                            <input name="start_date" id="start_date" class="form-control form-control-sm{{ $errors->has('start_date') ? ' is-invalid' : '' }}" type="date" placeholder="Enter Start date" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Closure date</label>
                            <input name="closure_date" id="closure_date" class="form-control form-control-sm{{ $errors->has('closure_date') ? ' is-invalid' : '' }}" type="date" placeholder="Enter Closure date" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Final closure date</label>
                            <input name="final_closure_date" id="final_closure_date" class="form-control form-control-sm{{ $errors->has('final_closure_date') ? ' is-invalid' : '' }}" type="date" placeholder="Enter Final closure date" required>
                        </div>

                    </div>

                    <div class="tile-footer text-right">

                        <button class="btn btn-primary" type="submit" name="action" value="store">Create</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="/contribution-settings"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>

                    </div>

                </div>

            </form>

        </div>
    </div>
@endsection
