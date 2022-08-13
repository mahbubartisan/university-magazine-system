<?php

namespace App\Http\Controllers;

use App\Contribution;
use App\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacultyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){

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

        $faculties = Faculty::all();
        return view('faculty.faculties', compact('student_unread_notifications','student_notifications','contributions','notifications','unread_notifications', 'faculties'));
    }
    public function action(Request $request){

        switch ($request['action']) {

            case 'create':

                return view('faculty.create_faculties');

                break;

            case 'store':

//                var_dump($request);

                $this->validate($request, [
                    'name' => 'required|string|unique:faculties|min:2',
                ]);

                Faculty::create([
                    'name' => $request['name'],
                    'slug' => str_slug($request['name'], '-'),
                ]);

                return redirect('faculties')->with('success','Faculty has been added successfully!');

                break;

            case 'edit':

                $faculty = Faculty::find($request['faculty_id']);
                return view('faculty.edit_faculties', compact('faculty'));

                break;

            case 'update':

                $this->validate($request, [
                    'name' => 'required|string|unique:faculties',
                ]);

                Faculty::find($request['faculty_id'])->update([
                    'name' => $request['name'],
                    'slug' => str_slug($request['name'], '-'),
                ]);

                return redirect('faculties')->with('success','Faculty has been updated successfully!');

                break;

            case 'delete':

                Faculty::destroy($request['faculty_id']);
                return redirect('faculties')->with('success','Faculty has been deleted successfully!');

                break;

        }
    }
}
