<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
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
        $files = File::all();//Trae todos los registros de la tabla
        //$filecounter = File::count();
        //$usercounter = User::count();
        return view('file.index')->with('files', $files);//le pasamos la variable articulos a nuestro index
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('file.create');
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
        $files = new File();
        $files -> name = $request->get('nombre');
        $files -> size = $request->get('tamano');
        $files -> type = $request->get('tipo');
        $files -> folder = $request->get('carpeta');
        //guarda
        $files -> save();
        //redirecciona a pagina index
        return redirect('/files');

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
        $file = File::find($id);//Trae un registro de la tabla
        return view('file.edit')->with('file', $file);
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
        $file = File::find($id);
        $file -> name = $request->get('nombre');
        $file -> size = $request->get('tamano');
        $file -> type = $request->get('tipo');
        $file -> folder = $request->get('carpeta');
        //guarda
        $file -> save();
        //redirecciona a pagina index
        return redirect('/files');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $file = File::find($id);
        $file->delete();
        return redirect('/files');
    }
}
