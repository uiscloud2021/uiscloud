<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    //CONSTRUCTOR PARA PROTEGER FILES SOLO PARA LOGEADOS
    public function __construct(){
        //PROTEGRE LAS RUTAS POR EL CONTROLADOR DEPENDIENDO DE ROLES Y PERMISOS
        $this->middleware('can:users.index');//PROTEGE TODAS LAS RUTAS
        //$this->middleware('can:users.index')->only('index');//SOLO PROTEGE LO QUE ESPECIFIQUEMOS
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();//Trae todos los registros de la tabla
        return view('user.index', compact('users'));//le pasamos la variable articulos a nuestro index
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();//PARA SELECT
        return view('user.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //VALIDAR CAMPOS
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required',
            'position' => 'required',
            'phone' => 'required'
        ]);
        
        //GUARDAR REGISTROS
        $users = new User();
        $users -> name = $request->get('name');
        $users -> email = $request->get('email');
        $users -> password = bcrypt($request->get('password'));
        $users -> position = $request->get('position');
        $users -> phone = $request->get('phone');
        //guarda
        $users -> save();

        if($request->role){
            $users->roles()->attach($request->role);//GUARDAR LAS RELACIONES ROLES
        }
        return redirect()->route('users.edit', $users)->with('info', 'El usuario se creó correctamente');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();//PARA SELECT
        return view('user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //VALIDAR CAMPOS
        $request->validate([
            'name' => 'required',
            'email' => "required|unique:users,email,$user->id",
            'role' => 'required',
            'position' => 'required',
            'phone' => 'required'
        ]);
        
        //CONDICION PARA GUARDAR CAMBIO DE PASSWORD
        $pw_prev = $request->get('password');
        $pw_new = $request->get('new_password');
        if($pw_new==""){
            $pw=$pw_prev;
        }else{
            $pw_new = bcrypt($request->get('new_password'));
            $pw=$pw_new;
        }

        //GUARDAR Cambios
        $users = User::find($user->id);
        $users -> name = $request->get('name');
        $users -> email = $request->get('email');
        //$users -> password = bcrypt($request->get('new_password'));
        $users -> password = $pw;
        $users -> position = $request->get('position');
        $users -> phone = $request->get('phone');
        //guarda
        $users -> save();

        if($request->role){
            $users->roles()->sync($request->role);//CAMBIOS EN TABLA RELACION ROLES
        }
        //redirecciona a pagina edit
        return redirect()->route('users.edit',$user)->with('info', 'El usuario se modificó correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index',$user)->with('info', 'El usuario se eliminó correctamente');;
    }
}
