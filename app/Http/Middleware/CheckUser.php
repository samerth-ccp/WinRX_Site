<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CheckUser
{
    public function handle($request, Closure $next)
    {
        if(!empty(auth()->guard('user')->id()))
        {

            $session_id = session()->getId();

            $data = DB::table('users')
                    ->select('users.*')
                    ->where([['users.id',auth()->guard('user')->id()],['status',"1"],['verify_status','1'],['deleted_status',"0"],['type','user']])
                    ->get()->first();
            if (empty($data->id))
            {

                if(Session::has('last_session')){
                    if($request->session()->getId()!== Session::get('last_session')){
                        auth()->guard('user')->logout();
                    }
                }else{
                    Session::put('last_session',$request->session()->getId());
                }

                auth()->guard('user')->logout();
                return redirect()->intended(route('frontend.login'))->with('error', 'Please Login to access user area.');
            }else{
                Session::put('UserData',$data);
            }

            return $next($request);
        }
        else
        {
            if($request->ajax()){
                return response('Unauthenticated', 401);
            }
            return redirect()->intended(route('frontend.login'))->with('error', 'Please Login to access user area');
        }
    }

}

