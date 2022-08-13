@extends('layouts.back_end')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">

                <div class="card p-3">

                    <form action="{{ route('contributions.action') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <h3 class="tile-title text-center">Submit Contribution</h3>

                        <div class="tile-body">

                            <div class="form-group">
                                <label class="control-label">Title</label>
                                <input name="title" class="form-control" type="text" placeholder="Enter Title">
                            </div>

                            <div class="form-group">
                                <label class="control-label">File</label>
                                <input name="file" class="form-control" type="file">
                            </div>

                        </div>

                        <div class="tile-footer text-right">

                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#myModal" data-backdrop="static" data-keyboard="false"><i class="fa fa-fw fa-lg fa-upload"></i>Upload</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="/contributions"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>

                            <!-- The Modal -->
                            <div class="modal fade" id="myModal">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title text-center">Terms and Conditions of submission</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body text-left">
                                            <strong>
                                                Please read carefully:
                                            </strong>
                                            <br>
                                            <p>
                                                Before submitting your contribution you need to agree to the following:
                                                <br>
                                                <ul>
                                                <li>The contribution is your alone and no one deserves it's credits but you.</li>
                                                <li>If we find any proof of you cheating in your contribution. We will suspend your contribution.</li>
                                                <li>No copyright materials should be in your contribution or We will suspend your contribution.</li>
                                                <li>What ever decision we make regarding your contribution, you have to abide by our decision.</li>
                                                </ul>
                                            </p>
                                        </div>

                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" type="submit" name="action" value="store"><i class="fa fa-fw fa-lg fa-check-circle"></i>I agree</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </form>

                </div>

        </div>
    </div>
@endsection
