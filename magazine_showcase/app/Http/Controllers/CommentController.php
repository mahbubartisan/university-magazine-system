<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Contribution;
use App\Faculty;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index($id){

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

        $contributions = Contribution::join('faculties','contributions.faculty_id','=','faculties.id')
            ->select('contributions.*','faculties.name as faculty_name')
            ->get();

        $student_contribution = Contribution::where('id','=',$id)->get();

        if (Auth::user()->user_type == 'Marketing Coordinator'){
            foreach($student_contribution as $new_contribution){
                if ($new_contribution->m_coordinator_notify == 'unread'){
                    Contribution::where('id','=',$id)->update([
                        'm_coordinator_notify' => 'read',
                    ]);
                }
            }
        }
        if (Auth::user()->user_type == 'Student'){
            foreach($student_notifications as $student_notification){
                if ($student_notification->student_notify == 'unread'){
                    Comment::where('contribution_id','=',$id)->update([
                        'student_notify' => 'read',
                    ]);
                }
            }
        }
//die($id);
        $comments = Comment::where('contribution_id','=',$id)->get();
            return view('contribution.comments', compact('student_unread_notifications','student_notifications','comments','users','faculty_id','faculties','contributions','student_contribution','unread_notifications','notifications'));

    }

    public function action(Request $request, $id){

        switch ($request['action']) {

            case 'download_file':

                return Response::download(public_path('assist/contributions/'.$request['download_id']));

                break;

            case 'store':

                $this->validate($request, [
                    'comment' => 'required|string|min:6',
                ]);

                Comment::create([
                    'user_id' => Auth::user()->id,
                    'contribution_id' => $request['contribution_id'],
                    'comment' => $request['comment'],
                ]);

                return back()->with('success','Comment has been added successfully!');

                break;

            case 'edit':

                /*$faculty = Faculty::find($request['faculty_id']);
                return view('faculty.edit_faculties', compact('faculty'));*/

                break;

            case 'update':

                /*$this->validate($request, [
                    'name' => 'required|string|unique:faculties',
                ]);

                Comment::find($request['faculty_id'])->update([
                    'name' => $request['name'],
                    'slug' => str_slug($request['name'], '-'),
                ]);

                return redirect('faculties')->with('success','Faculty has been updated successfully!');*/

                break;

            case 'delete':

                /*Faculty::destroy($request['faculty_id']);
                return redirect('faculties');*/

                break;

        }
    }
}
