@if(Auth::user()->user_type != admin)
    You're not allowed to be here! Return to <a href="/home">Home</a>
@elseif(Auth::user()->user_type == admin)
    @extends('layouts.app')

    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Register') }}</div>

                        <div class="card-body">

                            <div class="form-control form-control-sm
                        {{ $errors->has('name') ? ' is-invalid' : '' }}
                            {{ $errors->has('gander') ? ' is-invalid' : '' }}
                            {{ $errors->has('email') ? ' is-invalid' : '' }}
                            {{ $errors->has('faculty') ? ' is-invalid' : '' }}
                            {{ $errors->has('password') ? ' is-invalid' : '' }}
                                " hidden></div>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback alert alert-danger text-center" role="alert">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                            @if ($errors->has('gander'))
                                <span class="invalid-feedback alert alert-danger text-center" role="alert">
                                <strong>{{ $errors->first('gander') }}</strong>
                            </span>
                            @endif
                            @if ($errors->has('email'))
                                <span class="invalid-feedback alert alert-danger text-center" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                            @if ($errors->has('faculty'))
                                <span class="invalid-feedback alert alert-danger text-center" role="alert">
                                <strong>{{ $errors->first('faculty') }} Please reload the page and try again!</strong>
                            </span>
                            @endif
                            @if ($errors->has('password'))
                                <span class="invalid-feedback alert alert-danger text-center" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif

                            <form method="POST" action="{{ route('manage-users') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="gander" class="col-md-4 col-form-label text-md-right">{{ __('Gander') }}</label>

                                    <div class="col-md-6 f">
                                        <input type="radio" id="male" class="btn" name="gander" value="male" checked>
                                        <label for="male">Male</label>
                                        &nbsp;&nbsp;&nbsp;
                                        <input type="radio" id="female" class="btn" name="gander" value="female">
                                        <label for="female">Female</label>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" onchange="detect_email()" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="" required>


                                    </div>
                                </div>

                                <div class="form-group row" id="faculty">
                                    <label for="gander" class="col-md-4 col-form-label text-md-right">{{ __('Faculty') }}</label>

                                    <div class="col-md-6">
                                        <select name="faculty" class="form-control{{ $errors->has('faculty') ? ' is-invalid' : '' }}">
                                            <option value="">Please select one if you'r a student</option>
                                            @foreach($faculties as $faculty)
                                                <option value="{{$faculty->id}}">{{$faculty->name}}</option>
                                            @endforeach
                                        </select>


                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary" name="action" value="store">
                                            {{ __('Register') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-1.9.1.min.js"></script>
        <script>
            $( document ).ready(function() {
                $("#faculty").hide();
            });

            function detect_email() {
                var email = document.getElementById("email").value;
                // alert(email);
                var explode_email = email.split('@');

                if (explode_email[1]=='gmail.com'){
                    $("#faculty").show();
                }
                else{
                    $("#faculty").hide();
                }
                // x.value = x.value.toUpperCase();
            }
        </script>
    @endsection

@endif
