<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckController extends Controller
{
    public function __construct(){
    	
    	$this->request = request();
 
        # 验证是否登录
        $this->middleware(function ($request, $next) {
            if (!\Session::get('admin.user')) {
                redirect(route('login.index'))->send();
                exit();
            }
             
            return $next($request);
        });


    }
}
