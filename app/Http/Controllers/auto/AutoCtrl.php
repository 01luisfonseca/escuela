<?php

namespace App\Http\Controllers\auto;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp;

class AutoCtrl extends Controller
{
    public function getTarjetas(){
        $cliente=new GuzzleHttp\Client();
        $res=$cliente->request('GET','http://sac.nuevocolegiolusadi.edu.co/');
        dd($res);
    }

    public function getDispositivos(){
    }
}
