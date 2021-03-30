<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\Category;
use App\Models\User;
use App\Models\Recycled;
use Storage;
use Carbon\Carbon;

class FileController extends Controller
{
    //CONSTRUCTOR PARA PROTEGER FILES SOLO PARA LOGEADOS
    public function __construct(){
        $this->middleware('can:files.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $files = File::all();//Trae todos los registros de la tabla
        return view('file.index', compact('files'));//le pasamos la variable articulos a nuestro index
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');//PARA SELECT
        $users = User::all();//PARA CHECKBOX
        return view('file.create', compact('categories', 'users'));
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
            'name' => 'required|unique:files',
            'archivo' => 'required',
            'category_id' => 'required',
            'users' => 'required'
        ]);

        //LE PASO EL NOMBRE DE LA CARPETA
        $category_id = $request->get('category_id');
        $name_category = Category::where('id', '=', $category_id)->get()->first();
        $folder = $name_category->name;

        //GUARDAR ARCHIVO EN S3
        $path = $request->file('archivo')->store(path: $folder, options: 's3');
        //$path = $request->file('archivo')->store($folder);
        $extension = $request->file('archivo')->extension();
        //GUARDAR REGISTROS
        $files = new File();
        $files -> name = $request->get('name');
        $filename = $files -> filename = basename($path);
        $files -> url = Storage::disk(name: 's3')->url($path);
        $files -> size = Storage::disk(name: 's3')->size($path);
        $files -> type = $extension;
        $files -> user_id = $request->get('user_id');
        $files -> category_id = $request->get('category_id');
        
        //GUARDAR
        $files -> save();

        //GUARDAR CAMBIOS
        $categ = Category::find($category_id);
        $categ -> contenido = '1';
        $categ -> save();

        if($request->users){
            $files->users()->attach($request->users);//GUARDAR LAS RELACIONES CATEGORY_USER
        }
        //redirecciona a pagina edit
        return redirect()->route('files.edit',$files)->with('info', 'El archivo se subio correctamente');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        $categories = Category::pluck('name', 'id');//PARA SELECT
        $users = User::all();//PARA CHECKBOX
        return view('file.edit', compact('file', 'categories', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //VALIDAR CAMPOS
        $request->validate([
            'name' => "required|unique:files,name,$file->id",
            'category_id' => 'required',
            'users' => 'required'
        ]);

        //ALMACENAR EN VARIABLE EL ARCHIVO Y LA URL
        $archivo_prev = $file->filename;
        $category_prev = $file->category_id;
        $user_prev = $file->user_id;

        //CONSULTA PARA CARPETA ANTERIOR
        $name_category_prev = Category::where('id', '=', $category_prev)->get()->first();
        $folder_prev = $name_category_prev->name;

        //CONSULTA PARA EL USUARIO
        $name_user_prev = User::where('id', '=', $user_prev)->get()->first();
        $user_prev = $name_user_prev->name;

        //LE PASO EL NOMBRE DE LA CARPETA EN CASO DE CAMBIO
        $category_id = $request->get('category_id');
        $name_category = Category::where('id', '=', $category_id)->get()->first();
        $folder = $name_category->name;

        $archivo_new = $request->file('archivo');

        if($archivo_new!=""){
            //NOMBRE NUEVO DE ARCHIVO
            $fecha=Carbon::now();
            //MOVER ARCHIVO A RECYCLED
            $recycled = Storage::move($folder_prev.'/'.$archivo_prev, 'Recycled/'.$fecha.$archivo_prev);
            //NUEVA URL EN RECYCLED
            $url_new = Storage::url('Recycled/'.$fecha.$archivo_prev);
            
            //GUARDAR ARCHIVO ANTERIOR EN RECYCLED
            $recycled = new Recycled();
            $recycled->name = $file->name;
            $recycled->filename = $file->filename;
            $recycled->url = $file->url;
            $recycled->url_new = $url_new;
            $recycled->size = $file->size;
            $recycled->type = $file->type;
            $recycled->user = $user_prev;
            $recycled->category = $folder_prev;
            $recycled->folder = $folder_prev;
            // Guardar
            $recycled->save();
        }

        //GUARDAR CAMBIOS
        $files = File::find($file->id);
        $files -> name = $request->get('name');

        if($archivo_new!=""){
            //GUARDAR ARCHIVO NUEVO EN S3
            $path = $request->file('archivo')->store(path: $folder, options: 's3');
            $extension = $request->file('archivo')->extension();
            $filename = $files -> filename = basename($path);
            $files -> url = Storage::disk(name: 's3')->url($path);
            $files -> size = Storage::disk(name: 's3')->size($path);
            $files -> type = $extension;
        }

        //$files -> user_id = $request->get('user_id');
        $files -> category_id = $request->get('category_id');
        //guarda
        $files -> save();

        if($request->users){
            $files->users()->sync($request->users);//CAMBIOS EN TABLA RELACION CATEGORY_USER
        }

        //redirecciona
        return redirect()->route('files.edit',$files)->with('info', 'El archivo se modificó correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {

        //ALMACENAR EN VARIABLE EL ARCHIVO Y LA URL
        $archivo_prev = $file->filename;
        $category_prev = $file->category_id;
        $user_prev = $file->user_id;

        //CONSULTA PARA CARPETA ANTERIOR
        $name_category_prev = Category::where('id', '=', $category_prev)->get()->first();
        $folder_prev = $name_category_prev->name;

        //CONSULTA PARA EL USUARIO
        $name_user_prev = User::where('id', '=', $user_prev)->get()->first();
        $user_prev = $name_user_prev->name;

        //FECHA
        $fecha=Carbon::now();
        //MOVER ARCHIVO A RECYCLED
        $recycled = Storage::move($folder_prev.'/'.$archivo_prev, 'Recycled/'.$fecha.$archivo_prev);
        //NUEVA URL EN RECYCLED
        $url_new = Storage::url('Recycled/'.$fecha.$archivo_prev);
            
        //GUARDAR ARCHIVO ANTERIOR EN RECYCLED
        $recycled = new Recycled();
        $recycled->name = $file->name;
        $recycled->filename = $file->filename;
        $recycled->url = $file->url;
        $recycled->url_new = $url_new;
        $recycled->size = $file->size;
        $recycled->type = $file->type;
        $recycled->user = $user_prev;
        $recycled->category = $folder_prev;
        $recycled->folder = $folder_prev;
        // Guardar
        $recycled->save();

        $file->delete();
        return redirect()->route('files.index',$file)->with('info', 'El archivo se eliminó correctamente');
    }
}
