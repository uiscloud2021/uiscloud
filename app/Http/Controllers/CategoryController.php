<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use Storage;

class CategoryController extends Controller
{
    //CONSTRUCTOR PARA PROTEGER CATEGORIES SOLO PARA LOGEADOS
    public function __construct(){
        $this->middleware('can:categories.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();//Trae todos los registros de la tabla
        return view('category.index', compact('categories'));//le pasamos la variable articulos a nuestro index
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$user = User::pluck('name', 'id');//PARA SELECT
        $users = User::all();
        return view('category.create', compact('users'));
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
            'name' => 'required|unique:categories',
            'users' => 'required'
        ]);

        //LE PASO EL NOMBRE DE LA CARPETA
        $folder = $request->get('name');
        //CREAR CARPETA(directorio) EN S3
        Storage::makeDirectory($folder);

        //GUARDAR REGISTROS
        $category = Category::create($request->all());

        if($request->users){
            $category->users()->attach($request->users);//GUARDAR LAS RELACIONES CATEGORY_USER
        }

        //redirecciona a pagina categorias edit
        return redirect()->route('categories.edit',$category)->with('info', 'El directorio se creó correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $users = User::all();//PASAR RELACION DE USUARIOS
        return view('category.edit', compact('category', 'users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        //VALIDAR CAMPOS
        $request->validate([
            'name' => "required|unique:categories,name,$category->id",
            'users' => 'required'
        ]);
        
        //CARGAR LOS DATOS DEL DIRECTORIO
        /*$directorio = Category::where('id', '=', $category->id)->get()->first();
        $name = $directorio->name;
        
        Storage::deleteDirectory($name);
        Storage::makeDirectory($category->name);*/

        //GUARDAR CAMBIOS
        //$category -> update($request->all());//GUARDAR TODOS LOS CAMBIOS

        if($request->users){
            $category->users()->sync($request->users);//CAMBIOS EN TABLA RELACION CATEGORY_USER
        }
        //redirecciona a pagina edit
        return redirect()->route('categories.edit',$category)->with('info', 'El directorio se modificó correctamente');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $directory = $category->name;

        //ELIMINAR UN DIRECTORIO de s3
        Storage::deleteDirectory($directory);

        $category->delete();
        return redirect()->route('categories.index',$category)->with('info', 'El directorio se eliminó correctamente');
    }
}
