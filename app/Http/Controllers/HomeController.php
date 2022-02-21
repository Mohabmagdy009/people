<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user = User::find(Auth::user()->id);
        if ($user->is_manager == 1) {
            $employee_list = $user->employees()->select('name', 'last_login', 'last_activity_update')->orderBy('last_activity_update', 'DESC')->get();
        } else {
            $employee_list = null;
            return redirect('/toolsActivities');
        }
        //dd($employee_list);
        return view('home', compact('employee_list'));
    }
}
