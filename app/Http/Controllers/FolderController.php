<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Models\User;
use Storage;

class FolderController extends Controller
{
    //CONSTRUCTOR PARA PROTEGER FILES SOLO PARA LOGEADOS
    public function __construct(){
        $this->middleware('can:folders.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $folders = Folder::all();//Trae todos los registros de la tabla
        return view('folder.index', compact('folders'));//le pasamos la variable a nuestro index
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('folder.create', compact('users'));
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
            'name' => 'required|unique:folders',
            'users' => 'required'
        ]);

        //LE PASO EL NOMBRE DE LA CARPETA
        $fold = $request->get('name');
        //CREAR CARPETA(directorio) EN S3
        Storage::makeDirectory($fold);

        //GUARDAR REGISTROS
        $folder = Folder::create($request->all());

        if($request->users){
            $folder->users()->attach($request->users);//GUARDAR LAS RELACIONES CATEGORY_USER
        }

        //redirecciona a pagina categorias edit
        return redirect()->route('folders.edit',$folder)->with('info', 'La carpeta se creó correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Folder $folder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Folder $folder)
    {
        $users = User::all();//PASAR RELACION DE USUARIOS
        return view('folder.edit', compact('folder', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Folder $folder)
    {
        //VALIDAR CAMPOS
        $request->validate([
            //'name' => "required|unique:folders,name,$folder->id",
            'users' => 'required'
        ]);
        
        /*/CARGAR LOS DATOS DEL DIRECTORIO
        $fold = Folder::where('id', '=', $folder->id)->get()->first();
        $name = $fold->name;
        $url = $fold->url;
        
        Storage::deleteDirectory($url);
        Storage::makeDirectory($folder->name);*/

        //GUARDAR CAMBIOS
        //$folder -> update($request->all());//GUARDAR TODOS LOS CAMBIOS

        if($request->users){
            $folder->users()->sync($request->users);//CAMBIOS EN TABLA RELACION CATEGORY_USER
        }
        //redirecciona a pagina edit
        return redirect()->route('folders.edit',$folder)->with('info', 'La carpeta se modificó correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Folder $folder)
    {
        $fold = $folder->name;
        $url = $folder->url;
        $contenido = $folder->contenido;

        //ELIMINAR UN DIRECTORIO de s3
        if($contenido==0){
            Storage::deleteDirectory($url);
            $folder->delete();
            return redirect()->route('folders.index',$folder)->with('info', 'La carpeta se eliminó correctamente');
        }else{
            return redirect()->route('folders.index',$folder)->with('info', 'La carpeta no se puede eliminar porque existen archivos en su contenido');
        }

        
    }
}
