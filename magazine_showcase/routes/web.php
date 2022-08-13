<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {

    $faculty_id = Auth::user()->user_faculty->pluck('id');
    $faculty_id = explode('[',$faculty_id);
    $faculty_id = explode(']',$faculty_id[1]);
//                die($faculty_id[0]);

    $unread_notifications = \App\Contribution::where('m_coordinator_notify','=','unread')
        ->where('contributions.faculty_id','=',$faculty_id[0])->get();
    $notifications = \App\Contribution::orderBy('m_coordinator_notify','asc')
        ->where('contributions.faculty_id','=',$faculty_id[0])->get();

    $student_unread_notifications = \App\Comment::join('contributions','contributions.id','=', 'comments.contribution_id')
        ->where('contributions.user_id','=',Auth::user()->id)
        ->where('comments.student_notify','=','unread')
        ->where('contributions.faculty_id','=',$faculty_id[0])
        ->select('comments.id as comment_id','comments.*','contributions.*')
        ->get();

    $student_notifications = \App\Comment::join('contributions','contributions.id','=', 'comments.contribution_id')
        ->where('contributions.user_id','=',Auth::user()->id)
        ->orderBy('student_notify','asc')
        ->where('contributions.faculty_id','=',$faculty_id[0])
        ->select('comments.id as comment_id','comments.*','contributions.*')
        ->get();

    $student_contributions = \App\Contribution::where('contributions.faculty_id','=',$faculty_id[0])->where('user_id','=',Auth::user()->id)->get();
    $users = \App\User::all();
    $contributions = \App\Contribution::all();
    $cont_settings = \App\ContributionSetting::all();
    $faculties = \App\Faculty::all();

    return view('dashboard.dashboard',compact('student_unread_notifications','student_notifications','notifications','unread_notifications','faculties','users','student_contributions','contributions','cont_settings'));
})->name('dashboard')->middleware('auth');

Route::post('/dashboard', function (Request $request) {
    $notification_id = $request['notification_id'];

    $post = \App\Contribution::where('id','=','notification_id')->get();

    if ($post->m_coordinator == 'unread'){
        \App\Contribution::where('id','=','notification_id')->update([
            'm_coordinator' => 'read'
        ]);
    }elseif ($post->m_coordinator == 'read'){
        \App\Contribution::where('id','=','notification_id')->update([
            'm_coordinator' => 'unread'
        ]);
    }

    return back();
})->middleware('auth');

Route::get('/profile', function () {

    $faculty_id = Auth::user()->user_faculty->pluck('id');
    $faculty_id = explode('[',$faculty_id);
    $faculty_id = explode(']',$faculty_id[1]);
//                die($faculty_id[0]);

    $unread_notifications = \App\Contribution::where('m_coordinator_notify','=','unread')
        ->where('contributions.faculty_id','=',$faculty_id[0])->get();
    $notifications = \App\Contribution::orderBy('m_coordinator_notify','asc')
        ->where('contributions.faculty_id','=',$faculty_id[0])->get();

    $student_unread_notifications = \App\Comment::join('contributions','contributions.id','=', 'comments.contribution_id')
        ->where('contributions.user_id','=',Auth::user()->id)
        ->where('comments.student_notify','=','unread')
        ->where('contributions.faculty_id','=',$faculty_id[0])
        ->select('comments.id as comment_id','comments.*','contributions.*')
        ->get();

    $student_notifications = \App\Comment::join('contributions','contributions.id','=', 'comments.contribution_id')
        ->where('contributions.user_id','=',Auth::user()->id)
        ->orderBy('student_notify','asc')
        ->where('contributions.faculty_id','=',$faculty_id[0])
        ->select('comments.id as comment_id','comments.*','contributions.*')
        ->get();

    return view('profile.view_profile',compact('student_unread_notifications','student_notifications','notifications','unread_notifications'));
});

Auth::routes(['verify' => false]);

//Route::get('/home', 'HomeController@index')->name('home')/*->middleware('verified')*/;

Route::get('/faculties', 'FacultyController@index');
Route::post('/faculties', 'FacultyController@action')->name('faculties.action');

Route::get('/contribution-settings', 'SettingsController@contribution_settings_index');
Route::post('/contribution-settings', 'SettingsController@contribution_settings_action')->name('contribution-settings.contribution_settings_action');

Route::get('/manage-users', 'SettingsController@system_users');
Route::post('/manage-users', 'SettingsController@manage_users')->name('manage-users');

Route::get('/contributions', 'ContributionController@index');
Route::post('/contributions', 'ContributionController@action')->name('contributions.action');

Route::get('/contribution/{id}', 'CommentController@index');
Route::post('/contribution/{id}', 'CommentController@action')->name('comment.action');

Route::get('/contributions-status/{id}/{status}', 'ContributionController@approve_disapprove')->name('contributions-extra');

Route::get('/contributions-edit/{id}', 'ContributionController@stud_edit')->name('contributions-extra');
Route::get('/contributions-delete/{id}', 'ContributionController@stud_delete')->name('contributions-extra');
