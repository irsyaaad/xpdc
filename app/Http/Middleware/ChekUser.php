<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\RoleMenu;
use Request;
use App\Models\Menu;
use Session;
use Route;

class ChekUser
{   
    public function handle($request, Closure $next)
    {   
        //return $next($request);

        $roles = Session("role")["id_role"];
        $url1 = $request->segment(1);
        $url2 = $request->segment(2);
        $url3 = $request->segment(3);
        $url4 = $request->segment(4);
        $method = $request->method();
        
        if($roles=="1"){
            return $next($request);
        }else{
            $permit = RoleMenu::getAccess($url1,$roles);
            if($permit == null){
                abort(404);
            }
            if($method=="POST"){
                if($permit->c_insert==1){
                    return $next($request);
                }else{
                    abort(404);
                }
            }elseif($method=="PUT"){
                if($permit->c_update==1){
                    return $next($request);
                }else{
                    abort(404);
                }
            }elseif($method=="DELETE"){
                if($permit->c_delete==1){
                    return $next($request);
                }else{
                    abort(404);
                }
            }else{
                if($url2==null and $permit->c_read==1){
                    return $next($request);
                }elseif($url2=="create" and $permit->c_insert==1){
                    return $next($request);
                }elseif($url3=="edit" and $permit->c_update==1){
                    
                    return $next($request);
                }elseif($url3==null and $url2!="create" and $permit->c_other==1){
                    return $next($request);
                }elseif($url3!=null and $url2!="create" and $permit->c_other==1){
                    return $next($request);
                }elseif($url3!=null and $permit->c_update==1){
                    return $next($request);
                }else{
                    abort(404);
                }
            }
        }
    }  
}
