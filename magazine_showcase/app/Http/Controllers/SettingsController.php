<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\ContributionSetting;
use App\Faculty;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function contribution_settings_index(){

        $faculties = Faculty::all();
        $contributions = Contribution::all();
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

        $contribution_settings = ContributionSetting::all();
        return view('settings.contribution_settings', compact('student_unread_notifications','student_notifications','faculties','contributions','notifications','unread_notifications', 'contribution_settings'));
    }
    public function contribution_settings_action(Request $request){

        switch ($request['action']) {

            case 'create':

                return view('settings.create_contribution_settings');

                break;

            case 'store':

                $this->validate($request, [
                    'academic_year' => 'required|numeric|min:'.Carbon::now()->year/*date(now('year'))*/,
                    'start_date' => 'required|date|after:yesterday',
                    'closure_date' => 'required|date|after:start_date',
                    'final_closure_date' => 'required|date|after:closure_date',
                ]);

                $res = ContributionSetting::where('final_closure_date', '>=', $request['start_date'])->value('final_closure_date');

                /*if (ContributionSetting::where('final_closure_date','=',$request['academic_year'])->exists()) {

                    return redirect('contribution-settings')->with('warning', 'A contest is ongoing. Please select a date after ' . $res);

                }

                else{

                }*/
//                die(date($res, +  '1 day'));

                if ($res <= \Carbon\Carbon::create($request['start_date'])->subDay(1)){
                    ContributionSetting::create([
                        'academic_year' => $request['academic_year'],
                        'start_date' => $request['start_date'],
                        'closure_date' => $request['closure_date'],
                        'final_closure_date' => $request['final_closure_date'],
                    ]);
                }else{
                    return redirect('contribution-settings')->with('warning', 'A contest is ongoing. Please select a date after ' . $res);
                }


                return redirect('contribution-settings')->with('success','Your settings have been saved!');

                break;

            case 'edit':

                $contribution_setting = ContributionSetting::find($request['contribution_setting_id']);
                return view('settings.edit_contribution_settings', compact('contribution_setting'));

                break;

            case 'update':

                $this->validate($request, [
                    'academic_year' => 'required|numeric|min:'.Carbon::now()->year/*date(now('year'))*/,
                    'start_date' => 'required|date',
                    'closure_date' => 'required|date|after:start_date',
                    'final_closure_date' => 'required|date|after:closure_date',
                ]);

                ContributionSetting::find($request['contribution_setting_id'])->update([
                    'academic_year' => $request['academic_year'],
                    'start_date' => $request['start_date'],
                    'closure_date' => $request['closure_date'],
                    'final_closure_date' => $request['final_closure_date'],
                ]);

                return redirect('contribution-settings');

                break;

            case 'delete':

                ContributionSetting::destroy($request['contribution_setting_id']);
                return redirect('contribution-settings')->with('success','Successfully Deleted!');

                break;

        }
    }

    public function system_users(){
        $users = User::all();
        $faculties = Faculty::all();
        $contributions  = Contribution::all();
        return view('settings.manage_users', compact('users','faculties','contributions'));
    }
    public function manage_users(Request $request){

        switch ($request['action']) {

            case 'enable':

                User::find($request['user_id'])->update([
                    'status' => 'enabled',
                ]);

                return redirect('manage-users');

                break;

            case 'disable':

                User::find($request['user_id'])->update([
                    'status' => 'disabled',
                ]);

                return redirect('manage-users');

                break;


            case 'create':


                $faculties = Faculty::all();
                $contributions = Contribution::all();
                return view('settings.register_user', compact('faculties','contributions'));

                break;

            case 'store':

                if ($request['user_type'] == 'Marketing Manager'){
                    $this->validate($request, [
                        'name' => ['required', 'string', 'max:255'],
                        'user_type' => ['required'],
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                        'password' => ['required', 'string', 'min:6', 'confirmed'],
                    ]);
                }
                else{
                    $this->validate($request, [
                        'name' => ['required', 'string', 'max:255'],
                        'user_type' => ['required'],
                        'faculty' => ['required'],
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                        'password' => ['required', 'string', 'min:6', 'confirmed'],
                    ]);
                }

//die($request['user_type']);

                User::create([
                    'name' => $request['name'],
                    'gander' => $request['gander'],
                    'email' => $request['email'],
                    'user_type' => $request['user_type'],
                    'password' => Hash::make($request['password']),
                    'status' => 'enabled'
                ]);

                $user_id = User::where('email','=', $request['email'])->value('id');
                $faculty_id = $request['faculty'];

                $user = User::find($user_id);
                $user->user_faculty()->attach($faculty_id);

                return redirect('manage-users')->with('success','User has been created successfully!');

                break;

            case 'edit':

                $user = User::find($request['user_id']);
                $users = User::all();
                $faculties = Faculty::all();
                return view('settings.edit_users', compact('user','users', 'faculties'));

                break;

            case 'update':

                if ($request['user_type'] == 'Marketing Manager'){
                    $this->validate($request, [
                        'name' => ['required', 'string', 'max:255'],
                        'user_type' => ['required'],
                    ]);
                }
                else{
                    $this->validate($request, [
                        'name' => ['required', 'string', 'max:255'],
                        'user_type' => ['required'],
                        'faculty' => ['required'],
                    ]);
                }

                $user_id = User::where('email','=', $request['email'])->value('id');
                $user = User::find($user_id);
                $faculty_id = $request['faculty'];
                $get_faculty = Faculty::where('id',$faculty_id)->value('name');

                if ($user->user_type == 'Marketing Manager'){
                    User::find($request['user_id'])->update([
                        'name' => $request['name'],
                        'gander' => $request['gander'],
                        'user_type' => $request['user_type'],
//                    'email' => $request['email'],
                        /*'password' => Hash::make($request['password']),*/
                    ]);

                    // custom codes
                    $user_id = User::where('email','=', $request['email'])->value('id');
                    $faculty_id = $request['faculty'];

                    $user = User::find($user_id);

                    $user->user_faculty()->detach();

                }else{
                    User::find($request['user_id'])->update([
                        'name' => $request['name'],
                        'gander' => $request['gander'],
                        'user_type' => $request['user_type'],
//                    'email' => $request['email'],
                        /*'password' => Hash::make($request['password']),*/
                    ]);

                    // custom codes
                    $user_id = User::where('email','=', $request['email'])->value('id');
                    $faculty_id = $request['faculty'];

                    $user = User::find($user_id);

                    $user->user_faculty()->detach();
                    $user->user_faculty()->attach($faculty_id);
                }

                return redirect('manage-users')->with('success','Congratulations! You have successfully updated the user!');

                break;

            case 'delete':

                User::destroy($request['user_id']);
                return redirect('manage-users')->with('success','User has been deleted successfully');

                break;

        }
    }
}
