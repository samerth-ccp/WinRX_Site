<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {   
        if(auth()->guard('admin')->check())
        {
            $data = DB::table('admins')
                    ->select('admins.id')
                    ->where('admins.id',auth()->guard('admin')->id())
                    ->first();
            if (empty($data->id))
            {
                if(Session::has('last_session')){
                    if($request->session()->getId()!== Session::get('last_session')){
                        auth()->guard('admins')->logout();
                    }
                }else{
                    Session::put('last_session',$request->session()->getId());
                }
                
                auth()->guard('admins')->logout();
                return redirect()->intended(route('backend.login'))->with('error', 'You do not have access to admin side');
            }
            return $next($request);
        }
        else 
        {
            if($request->ajax()){
                return response('Unauthenticated', 401);
            }
            return redirect()->intended(route('backend.login'))->with('info', 'Please Login to access admin area');
        }
    }

}

