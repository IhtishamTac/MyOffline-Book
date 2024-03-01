<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role)
    {
        if ($request->user()->role != $role) {
            // abort(403, 'Unauthorized action.');
            if(auth()->user()->role == 'admin'){
                return redirect()->route('home.admin');
            }else if(auth()->user()->role == 'pustakawan'){
                return redirect()->route('home');
            }else if(auth()->user()->role == 'owner'){
                return redirect()->route('home.owner');
            }
        }

        return $next($request);
    }
}
