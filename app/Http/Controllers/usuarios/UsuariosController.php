<?php

namespace App\Http\Controllers\usuarios;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\modelos\TipoUsuario;
use Validator;
use App\modelos\Users;
use App\Helpers\NoOrphanRegisters;

class UsuariosController extends Controller
{
    
    public function editar($id)
    {
        return view('regmodUsuario',[
            'opciones'=>$this->tiposUsuario(),
            'accionusuario'=>null,
            'accion'=>'actualizar',
            'metodo'=>'POST',
            'user'=>Users::find($id)
        ]);
    }

    public function index()
    {
        return view('usuarios');
    }

    public function getCarnet()
    {
        return view('carnet');
    }

    
    public function getBuscar()
    {
        return view('searchUsuario',['principal'=>Users::with('tipo_usuario')->orderBy('updated_at','DESC')->take(50)->get()]);
    }

    
    public function postModificar(Request $request)
    {
        $resultado='No se encontraron resultados';
        $validacion=Validator::make($request->all(),[
            'busqueda' => 'required|min:2|max:20'
        ]);
        if(!$validacion->fails()){
            $user=Users::with('tipo_usuario')->select('users.*','tipo_usuario.nombre_tipo')
                ->join('tipo_usuario','tipo_usuario_id','=','tipo_usuario.id')
                ->where('name','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('lastname','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('identificacion','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('email','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('telefono','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('acudiente','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('tipo_usuario.nombre_tipo','LIKE','%'.$request->input('busqueda').'%')
                ->orWhere('tarjeta','LIKE','%'.$request->input('busqueda').'%')
                ->get();
            $resultado='Se ha(n) encontrado '.$user->count().' coincidencia(s)';
            return view('searchUsuario',['respuesta'=>1,'user'=>$user, 'principal'=>Users::with('tipo_usuario')->orderBy('updated_at','DESC')->take(50)->get()]);
        }
        
        return back()->withErrors($validacion)->withFlashMessage($resultado);
    }

    
    public function gactualizar(Request $request)
    {
        return view('regmodUsuario',[
            'opciones'=>$this->tiposUsuario(),
            'accionusuario'=>$request->input('accionusuario'),
            'accion'=>'actualizar',
            'metodo'=>'POST',
            'user'=>Users::find($request->input('id'))
        ]);
    }

    public function pactualizar(Request $request)
    {
        $resultado='';
        $validacion=NULL;
        $val=false;
        $user=User::find($request->input('id'));
        if($request->has('cambiapass')){
            $validacion=Validator::make($request->all(),[
                'password' => 'required|min:8',
                'verifica_password'=>'required|same:password'
            ]);
            if(!$validacion->fails()){
                $user->password=bcrypt($request->input('password'));
                $val=true;
            }else{
                $resultado='No se cambia la contraseña';
            }
        }else{
            $validacion=Validator::make($request->all(),[
                'identificacion' => 'required',
                'name' => 'required',
                'lastname' => 'required'
            ]);
            if(!$validacion->fails()){
                $user->name=$request->input('name');
                $user->lastname=$request->input('lastname');
                $user->identificacion=$request->input('identificacion');
                $user->birday=$request->input('birday');
                $user->tarjeta=$request->input('tarjeta');
                $user->email=$request->input('email');
                $user->telefono=$request->input('telefono');
                $user->direccion=$request->input('direccion');
                $user->acudiente=$request->input('acudiente');
                $user->tipo_usuario_id=$request->input('tipo_usuario');
                $val=true;
            }else{
                $resultado='No se modifica el usuario';
            }
        }
        if($val){
            $user->save();
            $resultado='Usuario Actualizado Exitosamente.';
            return redirect('usuarios/modificar')->withFlashMessage($resultado)->withErrors($validacion);
        }
        return redirect()->route('usuarios/modificar')->withFlashMessage($resultado)->withErrors($validacion);  /////////
    }
        
    

    public function eliminarUsuario(Request $request)
    {
        if($request->has('name')){
            $noOrphan=new NoOrphanRegisters;
            $user=User::find($request->input('id'));
            $user->delete();//problemas con esta instruccion. Parece que es un problemao con los pass
            return redirect()->route('usuarios')->withFlashMessage('Usuario eliminado correctamente. '.$noOrphan->getLimpiarHuerfanos());
        }
        return view('eliminausuario',['user'=>User::find($request->input('id'))]);
    }

    
    private function tiposUsuario()
    {
        return TipoUsuario::all();
    }
    
    public function perfil()
    {
        return $this->editar(auth()->user()->id);
    }

    
    public function postModificaPassword()
    {
        return view('usuarios/modificar')->with('respuesta',1);
    }

    
    public function getNuevo()
    {
        return view('regmodUsuario',['opciones'=>$this->tiposUsuario(),'accion'=>'registrar']);
    }

    
    public function postRegistrar(Request $request)
    {
        $resultado='';
        $validacion=Validator::make($request->all(),[
            'identificacion' => 'required|unique:users',
            'name'=>'required',
            'lastname'=>'required',
            'password' => 'required|min:8',
            'verifica_password'=>'required|same:password'
        ]);
        if(!$validacion->fails()){
            $user=new User;
            $user->name=$request->input('name');
            $user->lastname=$request->input('lastname');
            $user->identificacion=$request->input('identificacion');
            $user->password=bcrypt($request->input('password'));
            $user->birday=$request->input('birday');
            $user->email=$request->input('email');
            $user->tarjeta=$request->input('tarjeta');
            $user->telefono=$request->input('telefono');
            $user->direccion=$request->input('direccion');
            $user->acudiente=$request->input('acudiente');
            $user->tipo_usuario_id=$request->input('tipo_usuario');
            $user->save();
            $resultado='Usuario Creado Exitosamente.';
        }
        return back()->withFlashMessage($resultado)->withErrors($validacion);
    }
    

}
