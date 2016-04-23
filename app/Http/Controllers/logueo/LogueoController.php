<?php

namespace App\Http\Controllers\logueo;

use Illuminate\Http\Request;

use App\modelos\Users;
use App\Http\Requests;
use App\Http\Requests\VerificarLoginRequest;
use App\Http\Controllers\Controller;
use Auth;

class LogueoController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        return view('login');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postLogin(VerificarLoginRequest $request)
    {
        if(Auth::attempt(['identificacion'=>$request->input('identificacion'),'password'=>$request->input('password')],$request->input('remember'))){
            return redirect('autorizado/home');
        }else{
            return redirect('/');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        Auth::logout();
        return redirect('/');
    }

    
}
