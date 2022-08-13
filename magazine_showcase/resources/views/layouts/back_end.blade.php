<!DOCTYPE html>
<html lang="en">


<!-- Mirrored from demos.ui-lib.com/gull-html/blank.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 15 Jan 2019 05:36:30 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Magazine Showcase</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assist/back-end/styles/css/themes/lite-purple.min.css')}}">
    <link rel="stylesheet" href="{{asset('assist/back-end/styles/vendor/perfect-scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('assist/back-end/styles/vendor/datatables.min.css')}}">
{{--    <script src="{{asset('assist/back-end/js/vendor/echarts.min.js')}}"></script>
    <script src="{{asset('assist/back-end/js/es5/echart.options.min.js')}}'"></script>--}}
    <script src="{{asset('assist/back-end/google_chart_loader.js')}}"></script>
</head>

<body>
<div class="app-admin-wrap">
    <div class="main-header">
        <div class="logo">
            <img src="{{asset('assist/back-end/images/logo.png')}}" alt="">
        </div>

        {{--<div class="menu-toggle">
            <div></div>
            <div></div>
            <div></div>
        </div>--}}

        <div class="text-center mt-2 ml-5">

                <h3>{{Auth::user()->user_type}} Dashboard <br>
                    @foreach(Auth::user()->user_faculty as $user_faculty)
                        <small style="font-size: 10pt;">{{Auth::user()->user_type == 'Administrator' || Auth::user()->user_type == 'Marketing Manager' ? '':'Faculty ('.$user_faculty->name.')'}}</small>
                    @endforeach
                </h3>
        </div>

        <div class="d-flex align-items-center">
        </div>

        <div style="margin: auto"></div>

        <div class="header-part-right">
            <!-- Full screen toggle -->
            <i class="i-Full-Screen header-icon d-none d-sm-inline-block" data-fullscreen></i>

        @if(Auth::user()->user_type == 'Marketing Coordinator' && Auth::user()->status == "enabled")

            <!-- Notificaiton -->
            <div class="dropdown" id="notify">

                <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if(count($unread_notifications) > 0)
                        <span class="badge badge-primary">{{count($unread_notifications)}}</span>
                    @endif
                    <i class="i-Bell text-muted header-icon"></i>
                </div>

                <!-- Notification dropdown -->

                <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">

                    @foreach($notifications as $notification)
                        @if($notification->m_coordinator_notify == 'unread')

                            <div class="dropdown-item d-flex">
                                <div class="notification-icon">
                                    <form action="{{ route('contributions.action') }}" method="post">
                                        @csrf

                                        <input type="hidden" name="notification_id" value="{{$notification->id}}">
                                        <button type="submit" name="action" value="notify-read">
                                            <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                                        </button>

                                    </form>
                                </div>
                                <a href="/contribution/{{$notification->id}}" id="refresh_notification">
                                    <div class="notification-details flex-grow-1">
                                        <p class="m-0 d-flex align-items-center">
                                            <span>New notification</span>
                                            <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                                            <span class="flex-grow-1"></span>
                                            <span class="text-small text-muted ml-auto">{{\Illuminate\Support\Carbon::createFromTimeStamp(strtotime($notification->updated_at))->diffForHumans()}}</span>
                                        </p>
                                        @if($notification->created_at == $notification->updated_at)
                                            <p class="text-small text-muted m-0">{{$notification->file_user->name}} Contributed!</p>
                                        @else
                                            <p class="text-small text-muted m-0">{{$notification->file_user->name}} updated {{$notification->file_user->gander == 'male' ? 'his':'her'}} Contribution!</p>
                                        @endif
                                    </div>
                                </a>
                            </div>

                        @elseif($notification->m_coordinator_notify == 'read')

                            <div class="dropdown-item d-flex">
                                <div class="notification-icon">
                                    <form action="{{ route('contributions.action') }}" method="post">
                                        @csrf

                                        <input type="hidden" name="notification_id" value="{{$notification->id}}">
                                        <button type="submit" name="action" value="notify-unread">
                                            <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                                        </button>

                                    </form>
                                </div>
                                <a href="/contribution/{{$notification->id}}" id="refresh_notification">
                                    <div class="notification-details flex-grow-1">
                                        <p class="m-0 d-flex align-items-center">
                                            <span>Old notification</span>
                                            <span class="badge badge-pill badge-secondary ml-1 mr-1">old</span>
                                            <span class="flex-grow-1"></span>
                                            <span class="text-small text-muted ml-auto">{{\Illuminate\Support\Carbon::createFromTimeStamp(strtotime($notification->updated_at))->diffForHumans()}}</span>
                                        </p>
                                        <p class="text-small text-muted m-0">{{$notification->file_user->name}} Contributed!</p>
                                    </div>
                                </a>
                            </div>
                        @else

                            <div class="dropdown-item d-flex">
                                <div class="notification-icon">
                                    <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                                </div>
                                <a href="#" id="refresh_notification">
                                    <div class="notification-details flex-grow-1">
                                        <p class="m-0 d-flex align-items-center">
                                            <span>New notification</span>
                                            <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                                            <span class="flex-grow-1"></span>
                                            <span class="text-small text-muted ml-auto"></span>
                                        </p>
                                        <p class="text-small text-muted m-0"><strong>No new notifications!</strong></p>
                                    </div>
                                </a>
                            </div>

                        @endif
                    @endforeach

                </div>

            </div>
            <!-- Notificaiton End -->

        @endif

        @if(Auth::user()->user_type == 'Student' && Auth::user()->status == "enabled")

            <!-- Notificaiton -->

                <div class="dropdown" id="notify">

                    <div class="badge-top-container" role="button" id="dropdownNotification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        @if(count($student_unread_notifications) > 0)
                            <span class="badge badge-primary">{{count($student_unread_notifications)}}</span>
                        @endif
                        <i class="i-Bell text-muted header-icon"></i>
                    </div>

                    <!-- Notification dropdown -->

                    <div class="dropdown-menu dropdown-menu-right notification-dropdown" aria-labelledby="dropdownNotification" data-perfect-scrollbar data-suppress-scroll-x="true">


                        @foreach($student_notifications as $student_notification)
                            @if($student_notification->student_notify == 'unread')

                                <div class="dropdown-item d-flex">
                                    <div class="notification-icon">
                                        <form action="{{ route('contributions.action') }}" method="post">
                                            @csrf

                                            <input type="hidden" name="student_notification_id" value="{{$student_notification->comment_id}}">
                                            <button type="submit" name="action" value="student-notify-read">
                                                <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                                            </button>

                                        </form>
                                    </div>
                                    <a href="/contribution/{{$student_notification->id}}" id="refresh_notification">
                                        <div class="notification-details flex-grow-1">
                                            <p class="m-0 d-flex align-items-center">
                                                <span>New notification</span>
                                                <span class="badge badge-pill badge-primary ml-1 mr-1">new</span>
                                                <span class="flex-grow-1"></span>
                                                <span class="text-small text-muted ml-auto">{{\Illuminate\Support\Carbon::createFromTimeStamp(strtotime($student_notification->updated_at))->diffForHumans()}}</span>
                                            </p>
                                            @foreach($student_notification->comment_user as $comment_user)
                                                @if($student_notification->created_at == $student_notification->updated_at)
                                                    <p class="text-small text-muted m-0">Commented! on <strong>{{$student_notification->title}}</strong></p>
                                                @else
                                                    <p class="text-small text-muted m-0">Commented! on <strong>{{$student_notification->title}}</strong></p>
                                                @endif
                                            @endforeach
                                        </div>
                                    </a>
                                </div>

                            @elseif($student_notification->m_coordinator_notify == 'read')

                                <div class="dropdown-item d-flex">
                                    <div class="notification-icon">
                                        <form action="{{ route('contributions.action') }}" method="post">
                                            @csrf

                                            <input type="hidden" name="student_notification_id" value="{{$student_notification->comment_id}}">
                                            <button type="submit" name="action" value="student-notify-unread">
                                                <i class="i-Speach-Bubble-6 text-primary mr-1"></i>
                                            </button>

                                        </form>
                                    </div>
                                    <a href="/contribution/{{$student_notification->id}}" id="refresh_notification">
                                        <div class="notification-details flex-grow-1">
                                            <p class="m-0 d-flex align-items-center">
                                                <span>Old notification</span>
                                                <span class="badge badge-pill badge-secondary ml-1 mr-1">old</span>
                                                <span class="flex-grow-1"></span>
                                                <span class="text-small text-muted ml-auto">{{\Illuminate\Support\Carbon::createFromTimeStamp(strtotime($student_notification->updated_at))->diffForHumans()}}</span>
                                            </p>
                                            @foreach($student_notification->comment_user as $comment_user)
                                                <p class="text-small text-muted m-0">Commented! on <strong>{{$student_notification->title}}</strong></p>
                                            @endforeach
                                        </div>
                                    </a>
                                </div>

                            @else
                                <div class="dropdown-item d-flex">
                                    <div class="notification-icon">

                                        <i class="i-Speach-Bubble-6 text-primary mr-1"></i>

                                    </div>
                                    <a href="#" id="refresh_notification">
                                        <div class="notification-details flex-grow-1">
                                            <p class="m-0 d-flex align-items-center">
                                                <span>Old notification</span>
                                                <span class="badge badge-pill badge-secondary ml-1 mr-1">old</span>
                                                <span class="flex-grow-1"></span>
                                                <span class="text-small text-muted ml-auto">{{\Illuminate\Support\Carbon::now()}}</span>
                                            </p>
                                            <p class="text-small text-muted m-0"><strong>No contributions to show!</strong></p>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach

                    </div>

                </div>
                <!-- Notificaiton End -->

        @endif

            <!-- User avatar dropdown -->
            <div class="dropdown">
                <div class="user col align-self-end">
{{--                    <img src="{{asset('assist/back-end/images/faces/1.jpg')}}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                    <div id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer;">
{{--                        {{str_limit(Auth::user()->name, 1)}}--}}{{--{{Auth::user()->name}}--}}Options <i class="i-Arrow-Down"></i>
                    </div>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <div class="dropdown-header">
                            <i class="i-Lock-User mr-1"></i> {{Auth::user()->name}}
                        </div>

                        <a class="dropdown-item" href="/profile">Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out fa-lg"></i> {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- header top menu end -->

    <div class="side-content-wrap">
        <div class="sidebar-left open" data-perfect-scrollbar data-suppress-scroll-x="true">
            <ul class="navigation-left">

                <li class="nav-item">
                    <a class="nav-item-hold" href="/dashboard">
                        <i class="nav-icon i-Bar-Chart"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                    <div class="triangle"></div>
                </li>

                @if(Auth::user()->user_type == 'Administrator' || Auth::user()->user_type == 'Marketing Manager')
                    <li class="nav-item">
                        <a class="nav-item-hold" href="/faculties">
                            <i class="nav-icon i-Computer-Secure"></i>
                            <span class="nav-text">Faculties</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-item-hold" href="/manage-users">
                            <i class="nav-icon i-Administrator"></i>
                            <span class="nav-text">Users</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                @endif

                @if(Auth::user()->user_type != 'Guest')
                    <li class="nav-item">
                        <a class="nav-item-hold" href="/contributions">
                            <i class="nav-icon i-Library"></i>
                            <span class="nav-text">Contributions</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                @endif

                @if(Auth::user()->user_type == 'Administrator' || Auth::user()->user_type == 'Marketing Manager')
                    <li class="nav-item">
                        <a class="nav-item-hold" href="/contribution-settings">
                            <i class="nav-icon i-Double-Tap"></i>
                            <span class="nav-text">Contribution Settings</span>
                        </a>
                        <div class="triangle"></div>
                    </li>
                @endif
            </ul>
        </div>
        <div class="sidebar-overlay"></div>
    </div>
    <!--=============== Left side End ================-->

    <!-- ============ Body content start ============= -->
    <div class="main-content-wrap sidenav-open d-flex flex-column">
        {{--

        <div class="breadcrumb">
            <h1>Blank Page</h1>
            <ul>
                <li><a href="#">UI Kits</a></li>
                <li>Blank Page</li>
            </ul>
        </div>

        <div class="separator-breadcrumb border-top"></div>

        --}}

        @if(Auth::user()->status == 'disabled')
            <h1 class="text-center alert alert-danger col-md-6 offset-md-3">Your account has beed suspended!</h1>
            <br>
            <h5 class="text-center">Please contact "Administrator" for support</h5>
        @elseif(Auth::user()->status == 'enabled')

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('warning'))
            <div class="alert alert-warning alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($message = Session::get('danger'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger text-center">
                    {{ $error }} <br>
                </div>
            @endforeach
        @endif

        @yield('content');

        @endif

    </div>
    <!-- ============ Body content End ============= -->
</div>
<!--=============== End app-admin-wrap ================-->


<script src="{{asset('assist/back-end/js/vendor/jquery-3.3.1.min.js')}}"></script>
<script src="{{asset('assist/back-end/js/vendor/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assist/back-end/js/vendor/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('assist/back-end/js/es5/script.min.js')}}"></script>
<!-- page vendor js -->
<script src="{{asset('assist/back-end/js/vendor/datatables.min.js')}}"></script>
<!-- page custom js -->
<script src="{{asset('assist/js/datatables.script.js')}}"></script>

<script src="{{asset('assist/back-end/js/es5/dashboard.v1.script.min.js')}}"></script>

<script src="{{asset('assist/back-end/js/es5/script.min.js')}}"></script>

{{--<script src="{{asset('assist/back-end/ecanvas.js')}}"></script>--}}

<script>

    $(document).ready(function(){

        // alert('asf');

        $('#notify').load(' #notify');

        // Notification refresh
        setInterval(function(){
            $('#notify').load(' #notify');
        }, 1000*10);

        // Datatable render
        $('#facultyTable').DataTable();
        $('#contributionTable').DataTable();
        $('#contributionSettingTable').DataTable();
        $('#userTable').DataTable();

    });

    // check all function for check-box
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

</script>

</body>


<!-- Mirrored from demos.ui-lib.com/gull-html/blank.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 15 Jan 2019 05:36:30 GMT -->
</html>