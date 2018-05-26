<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $user_id = Auth::user()->isAdmin();

        if ($user_id) {
            return $next($request);
        }

        return redirect('/home');

    }
}
