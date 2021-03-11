<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //CONSTRUCTOR PARA PROTEGER FILES SOLO PARA LOGEADOS
    public function __construct(){
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();//Trae todos los registros de la tabla
        return view('user.index')->with('users', $users);//le pasamos la variable articulos a nuestro index
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //GUARDAR REGISTROS
        $users = new User();
        $users -> name = $request->get('nombre');
        $users -> email = $request->get('email');
        $users -> password = $request->get('contrasena');
        $users -> role = $request->get('rol');
        $users -> position = $request->get('puesto');
        $users -> phone = $request->get('telefono');
        //guarda
        $users -> save();
        //redirecciona a pagina index
        return redirect('/users');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);//Trae un registro de la tabla
        return view('user.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //GUARDAR CAMBIOS
        $user = User::find($id);
        $user -> name = $request->get('nombre');
        $user -> email = $request->get('email');
        $user -> role = $request->get('rol');
        $user -> position = $request->get('puesto');
        $user -> phone = $request->get('telefono');
        //guarda
        $user -> save();
        //redirecciona a pagina index
        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect('/users');
    }
}
