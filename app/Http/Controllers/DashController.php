<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\File;
use App\Models\Log;
use Yajra\Datatables\Datatables;
use App\Models\Recycled;
use App\Models\Folder;
use Storage;
use Carbon\Carbon;

class DashController extends Controller
{
    //CONSTRUCTOR PARA PROTEGER FILES SOLO PARA LOGEADOS
    public function __construct(){
        $this->middleware('can:dashboard');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //RECUPERAR LAS CATEGORIAS QUE LE PERTENECEN AL USUARIO AUTENTIFICADO
        $current_user = auth()->user();
        $categories = $current_user->categories;

        return view('dashboard', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $current_user = auth()->id();
        $category_id = $request->id_category;
        $category_name = $request->name_category;
        $nivel = $request->nivel_folder;
        $nivel_id = $nivel + 1;
        $url_folder="";
        $folder_id = 0;
        $folder_name="";
        
        if($nivel==0){
            $files = File::where('category_id', $category_id)
            ->where('nivel', $nivel_id)
            ->whereHas('users', function($query) use($current_user){
                $query->where('user_id', '=', $current_user);
            })->get();

            $folders = Folder::where('nivel', '=', $nivel_id)
            ->where('category_id', $category_id)
            ->whereHas('users', function($query) use($current_user){
                $query->where('user_id', '=', $current_user);
            })->get();

        }else{
            $folder_id = $request->id_folder;
            $url_folder = $request->url_folder;

            $fold = Folder::where('id', '=', $folder_id)->get()->first();
            $folder_name = $fold->name;

            $files = File::where('category_id', $category_id)
            ->where('nivel', $nivel_id)
            ->where('id_folder', $folder_id)
            ->whereHas('users', function($query) use($current_user){
                $query->where('user_id', '=', $current_user);
            })->get();

            $folders = Folder::where('nivel', '=', $nivel_id)
            ->where('category_id', $category_id)
            ->whereHas('users', function($query) use($current_user){
                $query->where('user_id', '=', $current_user);
            })->get();
        }
        return view('dashboard.show', compact('files','folders'))
            ->with('category_name', $category_name)
            ->with('category_id', $category_id)
            ->with('nivel_id', $nivel_id)
            ->with('folder_id', $folder_id)
            ->with('url_folder', $url_folder)
            ->with('folder_name', $folder_name);
            //return response($nivel_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function list_files(Request $request)
    {
        $category_id=$request->id_category;
        $current_user = auth()->id();
        $folder_id=$request->id_folder;
        $nivel_id=$request->nivel;

        //$files = File::where('category_id', $category_id)->get();
        $files = File::where('category_id', $category_id)
        ->where('nivel', $nivel_id)
        ->where('id_folder', $folder_id)
        ->whereHas('users', function($query) use($current_user){
            $query->where('user_id', '=', $current_user);
        })->get();
        
        return datatables()->of($files)
        ->addColumn('file_name', function ($files) {
            $html = '<a href="#" onclick="DescargarFile('.$files->id.');">'.$files->name.'</a>';
            //$html1 .= '&nbsp&nbsp&nbsp<img src="vendor/adminlte/dist/img/icons/'.$files->type.'.png" width="10%" heigth="10%">';
            return $html;
        })
        ->addColumn('img', function ($files) {
            $html1 = '<input type="radio" name="radio_details" value="'.$files->id.'" onclick="Details(this.value);">';
            //$html1 .= '&nbsp&nbsp&nbsp<img src="vendor/adminlte/dist/img/icons/'.$files->type.'.png" width="10%" heigth="10%">';
            return $html1;
        })
        ->addColumn('type', function ($files) {
            $html10 = $files->type;
            return $html10;
        })
        ->addColumn('edit', function ($files) {
            $html2 = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="EditFile('.$files->id.')">Editar</a>';
            return $html2;
        })
        ->addColumn('delete', function ($files) {
            $route="dashboard.delete_files',$files->id";
            //$html3 = '<a name="delete" href="{{route('.$route.')}}" class="delete btn btn-danger btn-sm">Eliminar</a>';
            $html3 = '<button type="button" name="delete" id="'.$files->id.'" class="delete btn btn-danger btn-sm">Eliminar</button>';
            return $html3;
        })
        ->rawColumns(['file_name', 'img', 'type', 'edit', 'delete'])
        ->make(true);
    }

    

    public function delete_files(Request $request)
    {
        $id_file=$request->id;
        $current_user = auth()->id();

        //CONSULTA PARA DATOS DE ARCHIVO A ELIMINAR
        $files = File::where('id', '=', $id_file)->get()->first();
        $name_file = $files->name;
        $archivo_prev = $files->filename;
        $category_prev = $files->category_id;

        //CONSULTA PARA CARPETA ANTERIOR
        $name_category_prev = Category::where('id', '=', $category_prev)->get()->first();
        $folder_prev = $name_category_prev->name;

        //CONSULTA PARA EL USUARIO QUE ELIMINARA EL ARCHIVO
        $name_user = User::where('id', '=', $current_user)->get()->first();
        $user_delete = $name_user->name;

        //FECHA ACTUAL
        $fecha=Carbon::now();
        //MOVER ARCHIVO A RECYCLED
        $recycled = Storage::move($folder_prev.'/'.$archivo_prev, 'Recycled/'.$fecha.$archivo_prev);
        //NUEVA URL EN RECYCLED
        $url_new = Storage::url('Recycled/'.$fecha.$archivo_prev);
            
        //GUARDAR ARCHIVO EN RECYCLED
        $recycled = new Recycled();
        $recycled->name = $name_file;
        $recycled->filename = $archivo_prev;
        $recycled->url = $files->url;
        $recycled->url_new = $url_new;
        $recycled->size = $files->size;
        $recycled->type = $files->type;
        $recycled->version = $files->version;
        $recycled->user = $user_delete;
        $recycled->category = $folder_prev;
        $recycled->folder = $folder_prev;
        // Guardar
        $recycled->save();

        $file_delete = File::where('id', $id_file)->delete();
        
        return response("eliminado");
    }

    public function edit_files(Request $request)
    {
        $id=$request->id;
        $files = File::where('id', $id)->get()->toJson();
        
        return json_encode($files);
    }

    public function update_files(Request $request)
    {
        if($request->ajax()){
            $current_user = auth()->id();
            $id_file = $request->id;

            //CARGAR LOS DATOS DEL ARCHIVO
            $files_prev = File::where('id', '=', $id_file)->get()->first();
            $archivo_prev = $files_prev->filename;
            $category_prev = $files_prev->category_id;
            $iduser_prev = $files_prev->id_user;
            $version_ant = $files_prev->version;

            //CONSULTA PARA CARPETA ANTERIOR
            $name_category_prev = Category::where('id', '=', $category_prev)->get()->first();
            $folder_prev = $name_category_prev->name;

            $archivo_new = $request->file('archivo_editf');
            //GUARDAR SI EL ARCHIVO ES MODIFICADO
            if($archivo_new!=""){
                //NOMBRE NUEVO DE ARCHIVO
                $fecha=Carbon::now();
                //MOVER ARCHIVO A MODIFICADOS
                $modified = Storage::move($folder_prev.'/'.$archivo_prev, 'Modified/'.$fecha.$archivo_prev);
                //NUEVA URL EN MODIFICADOS
                $url_new = Storage::url('Modified/'.$fecha.$archivo_prev);

                //GUARDAR DATOS EN TABLA LOGS MODIFICADOS
                $log = new Log();
                $log->directory = $folder_prev;
                $log->details = $request->details_editf;
                $log->filename = $archivo_prev;
                $log->url = $url_new;
                $log->size = $files_prev->size;
                $log->type = $files_prev->type;
                $log->version = $files_prev->version;
                $log->user_id = $iduser_prev;
                $log->file_id = $id_file;
                // Guardar
                $log->save();
            }

            //GUARDAR CAMBIOS
            $files = File::find($id_file);
            $files -> name = $request->name_editf;
            $version = $version_ant + 1;
            
            if($archivo_new!=""){
                //GUARDAR ARCHIVO NUEVO EN S3
                //$path = $request->file('archivo_editf')->store(path: $folder_prev, options: 's3');
                $path = $request->file('archivo_editf')->store($folder_prev, 's3');
                $extension = $request->file('archivo_editf')->extension();
                $filename = $files -> filename = basename($path);
                $files -> url = Storage::disk(name: 's3')->url($path);
                $files -> size = Storage::disk(name: 's3')->size($path);
                $files -> type = $extension;
                $files -> version = $version;

                //VERIFICAR VERSIONAMIENTO
                if($extension == "doc" || $extension == "docx" || $extension == "xls" || $extension == "xlsx"){
                    $versionamiento="No";
                }else{
                    $versionamiento="Si";
                }
                $files -> versionamiento = $versionamiento;
            }

            $block=$request->block_editf;

            $files -> id_user = $current_user;
            $files -> category_id = $category_prev;
            $files -> bloqueado = $block;
            if($block==0){
                $files -> user_block = "";
            }
            //guarda
            $files -> save();

            /*/GUARDAR LA RELACION DE LOS USUARIOS PARA EL ARCHIVO
            $users = User::whereHas('categories', function($query) use($category_prev){
                $query->where('category_id', '=', $category_prev);
            })->get();
            foreach ($users as $us){
                $files->users()->sync($us->id);
            }*/
            return response("actualizado");
         }
    }

    public function details(Request $request)
    {
        $radio=$request->radio;
        $logs = Log::where('file_id', $radio)->get();
        return response(json_encode($logs, JSON_UNESCAPED_UNICODE));
    }


    public function created_files(Request $request)
    {
         if($request->ajax()){
            $current_user = auth()->id();
            //LE PASO EL NOMBRE DE LA CARPETA
            $category_id = $request->category_id_addf;
            $nivel = $request->nivel_addf;
            $id_folder = $request->idfolder_addf;

            $name_category = Category::where('id', '=', $category_id)->get()->first();
            $folder = $name_category->name;

            //GUARDAR ARCHIVO EN S3
            if($id_folder != 0){
                $cons_folder = Folder::where('id', '=', $id_folder)->get()->first();
                $url_folder = $cons_folder->url;

                $path = $request->file('archivo_addf')->store(path: $url_folder, options: 's3');
            }else{
                $path = $request->file('archivo_addf')->store(path: $folder, options: 's3');
            }
            
            $extension = $request->file('archivo_addf')->extension();
            $version="1";

            //VERIFICAR VERSIONAMIENTO
            if($extension == "doc" || $extension == "docx" || $extension == "xls" || $extension == "xlsx"){
                $versionamiento="No";
            }else{
                $versionamiento="Si";
            }

            //GUARDAR REGISTROS
            $files = new File();
            $files -> name = $request->name_addf;
            $filename = $files -> filename = basename($path);
            $files -> url = Storage::disk(name: 's3')->url($path);
            $files -> size = Storage::disk(name: 's3')->size($path);
            $files -> type = $extension;
            $files -> version = $version;
            $files -> id_user = $current_user;
            $files -> category_id = $category_id;
            $files -> versionamiento = $versionamiento;
            $files -> bloqueado = '0';
            $files -> user_block = "";
            $files -> id_folder = $id_folder;
            $files -> nivel = $nivel;
            //GUARDAR
            $files -> save();

            //GUARDAR CONTENIDO EN CATEGORIA (CARPETA LLENA O VACIA)
            $categ = Category::find($category_id);
            $categ -> contenido = '1';
            $categ -> save();

            if($id_folder != 0){
                //GUARDAR CONTENIDO EN FOLDER (CARPETA LLENA O VACIA)
                $fold = Folder::find($id_folder);
                $fold -> contenido = '1';
                $fold -> save();
            }

            //GUARDAR LA RELACION DE LOS USUARIOS PARA EL ARCHIVO
            $users = User::whereHas('categories', function($query) use($category_id){
                $query->where('category_id', '=', $category_id);
            })->get();
            foreach ($users as $us){
                $files->users()->attach($us->id);
            }
            
            return response("guardado");
         }

    }


    public function download_files(Request $request)
    {
        $file_id=$request->file_id;
        $current_user = auth()->id();
        $files = File::where('id', '=', $file_id)->get()->first();
        $versionamiento = $files->versionamiento;
        $block = $files->bloqueado;
        $user = $files->user_block;
        $url = $files->url;

        if($versionamiento == "No"){
            if($block == 0){
                $users = User::where('id', '=', $current_user)->get()->first();
                $user_name = $users->name;

                $fil = File::find($file_id);
                $fil -> bloqueado = '1';
                $fil -> user_block = $user_name;
                $fil -> save();

                return response()->json(['success'=>'disponible', 'url'=>$url]);
            }else{
                return response()->json(['success'=>'bloqueado', 'user'=>$user]);
            }
        }else{
            return response()->json(['success'=>'disponible', 'url'=>$url]);
        }

    }




    public function folderdetails(Request $request)
    {
        $id_folder=$request->radio;
        $folders = Folder::where('id', $id_folder)->get();
        
        return datatables()->of($folders)
        ->addColumn('name', function ($folders) {
            $html = $folders->name;
            return $html;
        })
        ->addColumn('edit', function ($folders) {
            $html2 = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="EditFolder('.$folders->id.')">Editar</a>';
            return $html2;
        })
        ->addColumn('delete', function ($folders) {
            $route="dashboard.delete_folder',$folders->id";
            $html3 = '<button type="button" name="delete" id="'.$folders->id.'" class="delete btn btn-danger btn-sm">Eliminar</button>';
            return $html3;
        })
        ->rawColumns(['name', 'edit', 'delete'])
        ->make(true);
    }



    public function created_folder(Request $request)
    {
         if($request->ajax()){
            $current_user = auth()->id();
            //LE PASO EL NOMBRE DE LA CARPETA
            $category_id = $request->category_id_addc;
            $nivel = $request->nivel_addc;

            $name_category = Category::where('id', '=', $category_id)->get()->first();
            $directory = $name_category->name;
            //LE PASO EL NOMBRE DE LA CARPETA
            $folder_new = $request->name_addc;

            if($nivel==1){
                //CREAR CARPETA(directorio) EN S3
                Storage::makeDirectory($directory."/".$folder_new);
                $url=$directory."/".$folder_new;
            }else{
                $url_ant = $request->url_addc;
                Storage::makeDirectory($url_ant."/".$folder_new);
                $url=$url_ant."/".$folder_new;
            }

            //GUARDAR REGISTROS
            $folders = new Folder();
            $folders -> name = $folder_new;
            $folders -> url = $url;
            $folders -> contenido = '0';
            $folders -> nivel = $nivel;
            $folders -> id_user = $current_user;
            $folders -> category_id = $category_id;
            //GUARDAR
            $folders -> save();

            //GUARDAR LA RELACION DE LOS USUARIOS PARA LA CARPETA
            $users = User::whereHas('categories', function($query) use($category_id){
                $query->where('category_id', '=', $category_id);
            })->get();
            foreach ($users as $us){
                $folders->users()->attach($us->id);
            }
            
            return response("guardado");
         }

    }



    public function edit_folder(Request $request)
    {
        $id=$request->id;
        $folders = Folder::where('id', $id)->get()->toJson();
        
        return json_encode($folders);
    }



    public function update_folder(Request $request)
    {
        if($request->ajax()){
            $current_user = auth()->id();
            $id_folder = $request->id;

            //CARGAR LOS DATOS DEL ARCHIVO
            $folder_prev = Folder::where('id', '=', $id_folder)->get()->first();
            $url_prev = $folder_prev->url;

            //LE PASO EL NOMBRE DE LA CARPETA
            $category_id = $request->category_id_editc;
            $nivel = $request->nivel_editc;

            $name_category = Category::where('id', '=', $category_id)->get()->first();
            $directory = $name_category->name;

            //LE PASO EL NOMBRE DE LA CARPETA
            $folder_new = $request->name_editc;
            //CREAR CARPETA(directorio) EN S3
            Storage::deleteDirectory($url_prev);
            Storage::makeDirectory($directory."/".$folder_new);

            $url=$directory."/".$folder_new;

            //GUARDAR CAMBIOS
            $folders = Folder::find($id_folder);
            $folders -> name = $folder_new;
            $folders -> url = $url;
            $folders -> id_user = $current_user;
            //guarda
            $folders -> save();

            return response("actualizado");
         }
    }

    
}


