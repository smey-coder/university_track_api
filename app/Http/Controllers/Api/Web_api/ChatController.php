<?php
namespace App\Http\Controllers\Api\Web_api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Department;
use App\Models\User;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message'=>'required|string'
        ]);

        $message =
        strtolower($request->message);

        $reply =
        "Sorry, I don't understand your question yet.";


        /*
        |--------------------------------------------------------------------------
        | STUDENT COUNT
        |--------------------------------------------------------------------------
        */

        if(
            str_contains($message,'how many students')
            ||
            str_contains($message,'total students')
        )
        {


            $count =
            Student::count();


            $reply =
            "👨‍🎓 Total students in University Track: {$count} students.";

        }

        /*
        |--------------------------------------------------------------------------
        | TEACHER COUNT
        |--------------------------------------------------------------------------
        */

        elseif(
            str_contains($message,'teachers')
            ||
            str_contains($message,'total teacher')
        )
        {


            $count =
            Teacher::count();


            $reply =
            "👨‍🏫 Total teachers: {$count} teachers.";

        }

        /*
        |--------------------------------------------------------------------------
        | ASSIGNMENTS
        |--------------------------------------------------------------------------
        */
        elseif(

            str_contains($message,'assignment')

        )
        {
            $count =
            Assignment::count();


            $reply =
            "📝 Total assignments created: {$count} assignments.";

        }

        /*
        |--------------------------------------------------------------------------
        | SUBMISSIONS
        |--------------------------------------------------------------------------
        */
        elseif(

            str_contains($message,'submission')

        )
        {
            $count =
            AssignmentSubmission::count();
            $reply =
            "📤 Total assignment submissions: {$count} submissions.";

        }

        /*
        |--------------------------------------------------------------------------
        | DEPARTMENT
        |--------------------------------------------------------------------------
        */


        elseif(

            str_contains($message,'department')

        )
        {


            $count =
            Department::count();


            $reply =
            "🏢 Total departments: {$count} departments.";

        }






        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */


        elseif(

            str_contains($message,'user')

        )
        {

            $count =
            User::count();

            $reply =
            "👥 Total system users: {$count} users.";

        }
        return response()->json([

            'success'=>true,

            'reply'=>$reply
        ]);

    }


}