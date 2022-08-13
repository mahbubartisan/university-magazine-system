<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Contribution;
use App\ContributionSetting;
use App\Faculty;
use App\User;
use Notification;
use App\Notifications\ContributionNotification;
use Carbon\Carbon;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ContributionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        $faculties = Faculty::all();
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

        $contributions = Contribution::join('faculties','contributions.faculty_id','=','faculties.id')
            ->select('contributions.*','faculties.name as faculty_name')
            ->get();

        $student_contributions = Contribution::where('user_id','=',Auth::user()->id)->get();

        $comments = Comment::all();

        $contribution_settings = ContributionSetting::join('contributions','contribution_settings.academic_year','=',DB::raw('YEAR(contributions.created_at)'))
            ->select('contribution_settings.id','contribution_settings.academic_year','contribution_settings.closure_date','contribution_settings.final_closure_date')
            ->groupBy('contribution_settings.id')
            ->groupBy('contribution_settings.academic_year')
            ->groupBy('contribution_settings.closure_date')
            ->groupBy('contribution_settings.final_closure_date')
            ->get() ;

        $times = ContributionSetting::where('start_date','<',Carbon::now())
            ->orWhere ('final_closure_date','>',Carbon::now())
//            ->limit(1)
            ->get();

        return view('contribution.contributions', compact('student_unread_notifications','student_notifications','comments','faculties','notifications','unread_notifications', 'contributions','student_contributions','contribution_settings','times'));
    }
    public function action(Request $request){

        switch ($request['action']) {

            case 'notify-read':

                $notification_id = $request['notification_id'];
                Contribution::where('id','=',$notification_id)->update([
                    'm_coordinator_notify' => 'read'
                ]);

                return back();

                break;

            case 'notify-unread':

                $notification_id = $request['notification_id'];
                Contribution::where('id','=',$notification_id)->update([
                    'm_coordinator_notify' => 'unread'
                ]);

                return back();

                break;

            case 'student-notify-read':

                $notification_id = $request['student_notification_id'];
                Comment::where('id','=',$notification_id)->update([
                    'student_notify' => 'read'
                ]);

                return back();

                break;

            case 'student-notify-unread':

                $notification_id = $request['student_notification_id'];
                Comment::where('id','=',$notification_id)->update([
                    'student_notify' => 'unread'
                ]);

                return back();

                break;

            case 'approved':

                die($request['contribution_id']);

//                $contribution_id = $request['contribution_id'];

                /*Contribution::where('id','=',$contribution_id)->update([
                    'status' => 'approved'
                ]);*/

                return back();

                break;

            case 'disapproved':

                $notification_id = $request['contribution_id'];
                Contribution::where('id','=',$notification_id)->update([
                    'status' => 'disapproved'
                ]);

                return back();

                break;

            case 'create':

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

                return view('contribution.create_contributions',compact('student_unread_notifications','student_notifications','notifications','unread_notifications'));

                break;

            case 'store':

//                var_dump($request);

                $this->validate($request, [
                    'title' => 'required|unique:contributions',
                    'file' => 'required|file|mimes:png,jpg,jpeg,docx,doc|max:25600',
                ]);

                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $filename  = time() . '.' . $extension;

                $file->move(base_path('public/assist/contributions/'), $filename);

                if ($extension == 'png' || 'PNG' || 'jpg' || 'JPG' || 'jpeg' || 'JPEG'){
                    $type = 'image';
                }
                if ($extension == 'docx'){
                    $type = 'document';
                }

                $contribution_setting_id = ContributionSetting::where('academic_year','=',Carbon::now()->year)->value('id');

//                dd($contribution_setting_id);

                $faculty_id = Auth::user()->user_faculty->pluck('id');
                $faculty_id = explode('[',$faculty_id);
                $faculty_id = explode(']',$faculty_id[1]);
//                die($faculty_id[0]);

                $new_contribution = Contribution::create([
                    'user_id' => Auth::user()->id,
                    'title' => $request['title'],
                    'file' => $filename,
                    'type' => $type,
                    'contribution_setting_id' => $contribution_setting_id,
                    'faculty_id' => $faculty_id[0],
                ]);

                /*Notification::route('mail', 'logicbreaker.com@gmail.com')
                    ->route('nexmo', '5555555555')
                    ->notify(new ContributionNotification($new_contribution));*/

                return redirect('contributions')->with('success','Congratulations! You have successfully submitted your Contribution');

                break;

            case 'edit':

                $users = User::all();
                $faculties = Faculty::all();
                $faculty_id = Auth::user()->user_faculty->pluck('id');
                $faculty_id = explode('[',$faculty_id);
                $faculty_id = explode(']',$faculty_id[1]);
                //    $contributions  = Contribution::all();

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

                $contribution = Contribution::find()->get();

                return view('contribution.edit_contributions', compact('faculties','users','unread_notifications','notifications','student_unread_notifications','student_notifications','contribution'));

                break;

            case 'update':

                $this->validate($request, [
                    'title' => 'required',
                    'file' => 'file|mimes:png,jpg,jpeg,docx,doc|max:25600',
                ]);


                if (!$request->file('file'))
                {
                    Contribution::find($request['student_contribution_id'])->update([
                        'title' => $request['title'],
                        'm_coordinator_notify' => 'unread',
                    ]);
                }
                else{
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();
                    $filename  = time() . '.' . $extension;

                    $file->move(base_path('public/assist/contributions/'), $filename);

                    if ($extension == 'png' || 'PNG' || 'jpg' || 'JPG' || 'jpeg' || 'JPEG'){
                        $type = 'image';
                    }
                    if ($extension == 'docx'){
                        $type = 'document';
                    }

                    Contribution::find($request['student_contribution_id'])->update([
                        'title' => $request['title'],
                        'file' => $filename,
                        'type' => $type,
                        'm_coordinator_notify' => 'unread',
                    ]);
                }

//                die($request['title'].'-'.$filename.'-'.$type);


                return redirect('contributions')->with('success','Congratulations! You have successfully updated your Contribution');

                break;

            case 'delete':

                Contribution::destroy($request['stud_cont_id']);
                return redirect('contributions')->with('success','Contribution has been deleted successfully');

                break;

            /*case 'downloadAllZip':

                    $academic_year = $request['academic_year'];

                    $files = glob(public_path('assist/contributions/'.$academic_year.'/*'));

                    Zipper::make(public_path('assist/contributions/'.$academic_year.'-contributions.zip'))->add($files)->close();

                    return Response::download(public_path('assist/contributions/'.$academic_year.'-contributions.zip'));



                break;*/

            case 'downloadSelectedZip':

                $ids = $request->input('ids');

                if ($ids == ''){
                    return back()->with('warning','Please select "one" or "many" to continue!');
                }
                if (count($ids) > 1){

                    foreach ($ids as $id) {
                        $files = glob(public_path('assist/contributions/'.$id));
                        Zipper::make(public_path('assist/contributions/selected-contributions.zip'))->add($files)->close();
                    }
                    return Response::download(public_path('assist/contributions/selected-contributions.zip'));

                }
                else{

                    foreach ($ids as $id) {
                        return Response::download(public_path('assist/contributions/'.$id));
                    }

                }


                return back();

                break;

        }
    }
    public function approve_disapprove($id,$status){
//        die($id);
        $created_at = Contribution::find($id)->value('created_at');

        Contribution::find($id)->update([
            'status' => $status,
            'updated_at' => $created_at,
        ]);

        return back();
    }
    public function stud_edit($id){

        $users = User::all();
        $faculties = Faculty::all();
        $faculty_id = Auth::user()->user_faculty->pluck('id');
        $faculty_id = explode('[',$faculty_id);
        $faculty_id = explode(']',$faculty_id[1]);
        //    $contributions  = Contribution::all();

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

        $contribution = Contribution::find($id);

        return view('contribution.edit_contributions', compact('faculties','users','unread_notifications','notifications','student_unread_notifications','student_notifications','contribution'));

    }

    public function stud_delete($id){

        Contribution::destroy($id);
        return back();

    }
}

