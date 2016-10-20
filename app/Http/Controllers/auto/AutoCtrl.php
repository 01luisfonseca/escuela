<?php

namespace App\Http\Controllers\auto;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp;

use App\modelos\Authdevice;
use App\modelos\Generales;
use App\modelos\Asiserved;
use App\modelos\Tarjetas;
use Log;

class AutoCtrl extends Controller
{
    private $server;
    private $serial;

    public function __construct(){
        $this->server=Generales::where('nombre','Servidor principal')->first();
        $this->serial=Generales::where('nombre','Serial')->first();
    }
    public function getTarjetas(){
        $client=new GuzzleHttp\Client();
        $resp=0;
        $url='http://'.$this->server->valor.'/'.$this->serial->valor.'/device/asistencia';
        $request = new \GuzzleHttp\Psr7\Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            $this->guardarTarjetas(json_decode($response->getBody()));
        });
        $promise->wait();
    }

    private function guardarTarjetas($array){
        $col=collect($array);
        $tarjetas=Tarjetas::all();
        $items= array();
        $encontrada=false;
        foreach ($col as $tarjapi) {
            foreach ($tarjetas as $tarjlocal) {
                if($tarjlocal->tarjeta==$tarjapi->tarjeta){
                    $encontrada=true;
                }
            }
            if (!$encontrada) {
                $items[]=$tarjapi->tarjeta;
            }
        }
        $mensaje='Se crearon '.count($items).' tarjetas localmente. ';
        foreach ($items as $item) {
            $tarj=new Tarjetas;
            $tarj->tarjeta=$item;
            $tarj->save();
        }
        $items=array();
        $tarjetas=Tarjetas::all();
        foreach ($tarjetas as $tarjlocal) {
            foreach ($col as $tarjapi) {
                if($tarjlocal->tarjeta==$tarjapi->tarjeta){
                    $encontrada=true;
                }
            }
            if (!$encontrada) {
                $items[]=$tarjlocal->tarjeta;
            }
        }
        $mensaje.='Se eliminaron '.count($items).' tarjetas localmente.';
        foreach ($items as $item) {
            $tarj=Tarjetas::where('tarjeta',$item)->first();
            $tarj->delete();
        }
        return response()->json(['mensaje'=>$mensaje]);
    }

    public function getDispositivos(){
        $client=new GuzzleHttp\Client();
        $resp=0;
        $url='http://'.$this->server->valor.'/'.$this->serial->valor.'/device/all';
        $request = new \GuzzleHttp\Psr7\Request('GET', $url);
        $promise = $client->sendAsync($request)->then(function ($response) {
            $this->guardarDispositivos(json_decode($response->getBody()));
        });
        $promise->wait();
    }

    private function guardarDispositivos($array){
        $col=collect($array);
        $dispos=Authdevice::all();
        $items= array();
        $encontrada=false;
        foreach ($col as $disjapi) {
            foreach ($dispos as $dislocal) {
                if($dislocal->serial==$disjapi->serial){
                    $encontrada=true;
                }
            }
            if (!$encontrada) {
                $items[]=['serial'=>$disjapi->serial,'nombre'=>$disjapi->nombre,'descripcion'=>$disjapi->descripcion,'estado'=>$disjapi->estado];
            }
        }
        foreach ($items as $item) {
            $dispositivo=new Authdevice;
            $dispositivo->serial=$item['serial'];
            $dispositivo->nombre=$item['nombre'];
            $dispositivo->descripcion=$item['descripcion'];
            $dispositivo->estado=$item['estado'];
            $dispositivo->save();
        }
        $mensaje='Se crearon '.count($items).' dispositivos localmente. ';
        $dispos=Authdevice::all();
        $items= array();
        $encontrada=false;
        foreach ($dispos as $dislocal) {
            foreach ($col as $disjapi) {
                if($dislocal->serial==$disjapi->serial){
                    $encontrada=true;
                }
            }
            if (!$encontrada) {
                $items[]=$disjapi->id;
            }
        }
        $mensaje.='Se eliminaron '.count($items).' dispositivos localmente.';
        foreach ($items as $item) {
            $dispositivo=Authdevice::find($item);
            $dispositivo->delete();
        }
        return response()->json(['mensaje'=>$mensaje]);
    }

    public function enviarAsistencias(){
        $asiserved=Asiserved::where('tarjeta','!=','')->take(10)->get();
        foreach ($asiserved as $asis) {
            $client=new GuzzleHttp\Client();
            $url='http://'.$this->server->valor.'/'.$asis->lectora.'/device/asistencia/'.$asis->tarjeta;
            $resp=$client->request('GET',$url);
            if($resp->getBody()=='Asistio'){
                $obj=Asiserved::find($asis->id);
                $obj->delete();
                echo 'Borrado id '.$asis->id.'. ';
            }
        }
    }
}
