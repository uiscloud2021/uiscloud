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
use Illuminate\Support\Facades\Storage;
use ZipArchive;

//use Storage;
use Carbon\Carbon;

class DashController extends Controller
{
    //CONSTRUCTOR PARA PROTEGER FILES SOLO PARA LOGEADOS
    public function __construct(){
        $this->middleware('auth');
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
        $nivel = (int)$request->nivel_folder;
        $nivel_id = $nivel + 1;
        $url_folder="";
        $folder_id = 0;
        $folder_name="";
        
        if(isset($request->name_category)){
            $category_name = $request->name_category;
        }else{
            $cons_category = Category::where('id', '=', $category_id)->get()->first();
            $category_name = $cons_category->name;
        }
        
        if($nivel==0){
            $files = File::where('category_id', $category_id)
            ->where('nivel', $nivel_id)
            ->whereHas('users', function($query) use($current_user){
                $query->where('user_id', '=', $current_user);
            })->orderBy('name')->get();

            $folders = Folder::where('nivel', '=', $nivel_id)
            ->where('category_id', $category_id)
            ->whereHas('users', function($query) use($current_user){
                $query->where('user_id', '=', $current_user);
            })->orderBy('name')->get();

        }else{
            $folder_id = $request->id_folder;
            $name_fold = $request->name_fold;

            if($folder_id==""){
                $cons_fold = Folder::where('name', '=', $name_fold)
                ->where('category_id', '=', $category_id)->get()->first();
                //$folder_id = $cons_fold->folder_id;
                $folder_id = $cons_fold->id;
            }

            $fold = Folder::where('id', '=', $folder_id)->get()->first();
            $folder_name = $fold->name_uiscloud;
            $url_folder = $fold->url;


            $files = File::where('category_id', $category_id)
            ->where('nivel', $nivel_id)
            ->where('id_folder', $folder_id)
            ->whereHas('users', function($query) use($current_user){
                $query->where('user_id', '=', $current_user);
            })->get();

            $folders = Folder::where('nivel', '=', $nivel_id)
            ->where('category_id', $category_id)
            ->where('folder_id', $folder_id)
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
        //return response($folder_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /*public function list_folders(Request $request)
    {
        $category_id=$request->id_category;
        $current_user = auth()->id();
        $folder_id=$request->id_folder;
        $nivel_id=$request->nivel;

        $files = File::where('category_id', $category_id)
        ->where('nivel', $nivel_id)
        ->where('id_folder', $folder_id)
        ->whereHas('users', function($query) use($current_user){
            $query->where('user_id', '=', $current_user);
        })->get();
        
        return datatables()->of($files)
        ->addColumn('img', function ($files) {
            $icono = strtolower($files->type);
            $htm2 = '<a><img src="vendor/adminlte/dist/img/icons/'.$icono.'.png" style="text-align:center;" width="60%" heigth="60%"></a>';
            return $htm2;
        })
        ->addColumn('file_name', function ($files) {
            $html = '<h5><a href="#" style="color:#000000;" onclick="DescargarFile('.$files->id.');">'.$files->name.'.'.$files->type.'</a></h5>';
            return $html;
        })
        ->addColumn('version', function ($files) {
            $htm5 = '<h6>'.$files->version.'</h6>';
            return $htm5;
        })
        ->addColumn('date', function ($files) {
            $ht4 = $files->created_at->__toString();
            $html4 = date("F j, Y, g:i a", strtotime($ht4));
            return '<h6>'.$html4.'</h6>';
        })
        ->addColumn('edit', function ($files) {
            $html2 = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="EditFile('.$files->id.')">Editar</a>';
            return $html2;
        })
        ->addColumn('delete', function ($files) {
            $route="dashboard.delete_files',$files->id";
            $html3 = '<button type="button" name="delete" id="'.$files->id.'" class="delete btn btn-danger btn-sm">Eliminar</button>';
            return $html3;
        })
        ->rawColumns(['img', 'file_name', 'date', 'edit', 'delete'])
        ->make(true);
    }*/


    public function list_files(Request $request)
    {
        $category_id=$request->id_category;
        $current_user = auth()->id();
        $folder_id=$request->id_folder;
        $nivel_id=$request->nivel;

        $items = [];

        $files = File::where('category_id', $category_id)
        ->where('nivel', $nivel_id)
        ->where('id_folder', $folder_id)
        ->whereHas('users', function($query) use($current_user){
            $query->where('user_id', '=', $current_user);
        })->get();

        $folders = Folder::where('nivel', '=', $nivel_id)
        ->where('category_id', $category_id)
        ->where('folder_id', $folder_id)
        ->whereHas('users', function($query) use($current_user){
            $query->where('user_id', '=', $current_user);
        })->get();

        foreach($files as $file){
            $item = [];
            $item['id'] = $file->id;
            $item['contenido'] = $file->type;
            $item['name'] = $file->name;
            $item['created_at'] = $file->updated_at;
            $item['size'] = $file->size;
            $item['type'] = "file";
            array_push($items, $item);
        }

        foreach($folders as $folder){
            $item = [];
            $item['id'] = $folder->id;
            $item['contenido'] = $folder->contenido;
            $item['name'] = $folder->name_uiscloud;
            $item['created_at'] = $folder->updated_at;
            $item['size'] = $folder->url;
            $item['type'] = "folder";
            array_push($items, $item);
        }

        return datatables()->of($items)
        ->addColumn('img', function ($items) {
            if($items['type']=="folder"){
                $htm = '<a style="cursor:pointer" onclick="DashSubmit('.$items['id'].');"><img src="vendor/adminlte/dist/img/icons/folder'.$items['contenido'].'.png" style="text-align:center;" width="70%" heigth="70%"></a>';
            }else{
                $icono = strtolower($items['contenido']);
                $htm = '<a style="cursor:pointer" onclick="DescargarFile('.$items['id'].');"><img src="vendor/adminlte/dist/img/icons/'.$icono.'.png" style="text-align:center;" width="70%" heigth="70%"></a>';
            }
            return $htm;
        })
        ->addColumn('name', function ($items) {
            if($items['type']=="folder"){
                $html = '<h5><a href="#" style="color:#000000;" onclick="DashSubmit('.$items['id'].');">'.$items['name'].'</a></h5>';
            }else{
                $html = '<h5><a href="#" style="color:#000000;" onclick="DescargarFile('.$items['id'].');">'.$items['name'].'.'.$items['contenido'].'</a></h5>';
            }
            return $html;
        })
        ->addColumn('date', function ($items) {
            $ht4 = $items['created_at']->__toString();
            $html4 = date("F j, Y, g:i a", strtotime($ht4));
            return '<h6>'.$html4.'</h6>';
        })
        ->addColumn('size', function ($items) {
            if($items['type']=="folder"){
                $directorio=$items['size'];
                $sizeArray= array(); 
                //Arreglo donde se almacenarán los archivos para posteriormente sumarlas
                //Se obtienen todos los archivos   
                $archivos_array = Storage::disk('s3')->allFiles($directorio); 
                //Se calcula el peso de cada archivo con una iteración almacenándolo en el array
                foreach ($archivos_array as $key => $file) {
                    $sizeArray[$key]=Storage::disk('s3')->size($file);
                }
                //Se procene a crear una suma de todo el array, convertirla a megas y solo obtener los dos decimales
                $html5=round((array_sum($sizeArray))/1048576, 2)." Mb";
               // $html5="";
            }else{
                $html5 = round(($items['size'])/1048576, 2)." Mb";
            }
            return '<h6>'.$html5.'</h6>';
        })
        ->addColumn('edit', function ($items) {
            if($items['type']=="folder"){
                //$html2 = "";
                $html2 = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="EditFolder('.$items['id'].')">Editar</a>';
            }else{
                $html2 = '<a class="btn btn-info btn-sm" href="javascript:void(0)" onclick="EditFile('.$items['id'].')">Editar</a>';
            }
            return $html2;
        })
        ->addColumn('delete', function ($items) {
            if($items['type']=="folder"){
                $html3 = '';
            }else{
                $route="dashboard.delete_files,".$items['id'];
                $html3 = '<button type="button" name="delete" id="'.$items['id'].'" class="delete btn btn-danger btn-sm">Eliminar</button>';
            }
            return $html3;
        })
        ->rawColumns(['img', 'name', 'date', 'size', 'edit', 'delete'])
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
        $id_folder_prev = $files->id_folder;
        $url_file_prev = $files->url;

        //CONSULTA PARA CATEGORIA
        $name_category_prev = Category::where('id', '=', $category_prev)->get()->first();
        $category_name = $name_category_prev->name;

        //CONSULTA PARA URL FOLDER PREV
        $cons_folder_prev = Folder::where('id', '=', $id_folder_prev)->get()->first();
        $folder_name_prev = $cons_folder_prev->name;
        $folder_url_prev = $cons_folder_prev->url;

        //CONSULTA PARA EL USUARIO QUE ELIMINARA EL ARCHIVO
        $name_user = User::where('id', '=', $current_user)->get()->first();
        $user_delete = $name_user->name;

        //FECHA ACTUAL
        $fecha=Carbon::now();
        //MOVER ARCHIVO A RECYCLED
        $recycle = Storage::copy($folder_url_prev.'/'.$archivo_prev, 'Recycled/'.$archivo_prev, 'public');
        Storage::disk('s3')->delete($folder_url_prev.'/'.$archivo_prev);
        //Storage::move('Recycled/', $folder_url_prev.'/'.$archivo_prev, 'public');
        //NUEVA URL EN RECYCLED
        $url_new = Storage::url('Recycled/'.$archivo_prev);
            
        //GUARDAR ARCHIVO EN RECYCLED
        $recycled = new Recycled();
        $recycled->name = $name_file;
        $recycled->filename = $archivo_prev;
        $recycled->url = $url_file_prev;
        $recycled->url_new = $url_new;
        $recycled->size = $files->size;
        $recycled->type = $files->type;
        $recycled->version = $files->version;
        $recycled->user = $user_delete;
        $recycled->category = $category_name;
        $recycled->folder = $folder_url_prev;
        $recycled->file_id = $id_file;
        // Guardar
        $recycled->save();

        /*$logs = log::where('id', '=', $id_file)->get();
        if($logs>0){
            $log_delete = Log::where('file_id', $id_file)->delete();
        }*/
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
            $time=Carbon::now();
            $current_user = auth()->id();
            $id_file = $request->id;
            $block=$request->block_editf;

            //CARGAR LOS DATOS DEL ARCHIVO
            $files_prev = File::where('id', '=', $id_file)->get()->first();
            $archivo_prev = $files_prev->filename;
            $name_file = $files_prev->name;
            $category_prev = $files_prev->category_id;
            $iduser_prev = $files_prev->id_user;
            $version_ant = $files_prev->version;
            $id_folder_prev = $files_prev->id_folder;
            $url_file_prev = $files_prev->url;

            $archivoConvert = strtr($archivo_prev, ":", "_");

            //CONSULTA PARA CATEGORIA
            $name_category_prev = Category::where('id', '=', $category_prev)->get()->first();
            $category_name = $name_category_prev->name;

            //CONSULTA PARA URL FOLDER PREV
            $cons_folder_prev = Folder::where('id', '=', $id_folder_prev)->get()->first();
            $folder_name_prev = $cons_folder_prev->name;
            $folder_url_prev = $cons_folder_prev->url;

            //CONSULTA PARA EL USUARIO QUE MODIFICARA EL ARCHIVO
            $name_user = User::where('id', '=', $current_user)->get()->first();
            $user_modify = $name_user->name;

            $archivo_new = $request->file('archivo_editf');

            if($archivo_new!=""){
                //get filename with extension
                $filenamewithextension = $request->file('archivo_editf')->getClientOriginalName();

                if($archivoConvert!=$filenamewithextension){
                    return response("desigual");
                }
            }

            //GUARDAR SI EL ARCHIVO ES MODIFICADO
            if($archivo_new!=""){
                //NOMBRE NUEVO DE ARCHIVO
                $fecha=Carbon::now();
                //MOVER ARCHIVO A MODIFICADOS
                $modified = Storage::copy($folder_url_prev.'/'.$archivo_prev, 'Modified/'.$archivo_prev, 'public');
                Storage::disk('s3')->delete($folder_url_prev.'/'.$archivo_prev);
                //NUEVA URL EN MODIFICADOS
                $url_new_modify = Storage::url('Modified/'.$archivo_prev);

                //GUARDAR DATOS EN TABLA LOGS MODIFICADOS
                $log = new Log();
                $log->directory = $folder_url_prev;
                $log->name = $name_file;
                $log->details = $request->details_editf;
                $log->filename = $archivo_prev;
                $log->url = $url_new_modify;
                $log->size = $files_prev->size;
                $log->type = $files_prev->type;
                $log->version = $files_prev->version;
                $log->user = $user_modify;
                $log->file_id = $id_file;
                // Guardar
                $log->save();
            }

            //GUARDAR CAMBIOS
            $files = File::find($id_file);
            $version = $version_ant + 1;
            
            if($archivo_new!=""){
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
         
                //get file extension
                $extension = $request->file('archivo_editf')->getClientOriginalExtension();
                $filenameoriginal = $filename.'_'.$time.'.'.$extension;
                
                //filename to store
                $filenametostore = $folder_url_prev."/".$filename.'_'.$time.'.'.$extension;
                
                //GUARDAR ARCHIVO NUEVO EN S3
                Storage::disk('s3')->put($filenametostore, fopen($request->file('archivo_editf'), 'r+'), 'public');
                //Storage::disk('s3')->put($filenametostore, 'public');
                $filename = $files -> filename = $filenameoriginal;
                $files -> url = Storage::disk('s3')->url($filenametostore);
                $files -> size = Storage::disk('s3')->size($filenametostore);
                $files -> type = $extension;
                $files -> version = $version;

                //VERIFICAR VERSIONAMIENTO
                if($extension == "doc" || $extension == "docx" || $extension == "xls" || $extension == "xlsx" || $extension == "vsd" || $extension == "ppt" || $extension == "pptx" || $extension == "DOC" || $extension == "DOCX" || $extension == "XLS" || $extension == "XLSX" || $extension == "VSD" || $extension == "PPT" || $extension == "PPTX"){
                    $versionamiento="No";
                }else{
                    $versionamiento="Si";
                }
                $files -> versionamiento = $versionamiento;
                $files -> bloqueado = '0';
                $files -> user_block = "";
            }else{
                $files -> name = $request->name_editf;
                $files -> bloqueado = $block;
            }

            $files -> id_user = $current_user;
            $files -> category_id = $category_prev;
            
            if($block==0){
                $files -> user_block = "";
            }
            //guarda
            $files -> save();

            return response("actualizado");
            
         }
    }

    public function details(Request $request)
    {
        $radio=$request->radio;
        $logs = Log::where('file_id', $radio)->get();

        return datatables()->of($logs)
        ->addColumn('name', function ($logs) {
            $html = '<a href="'.$logs->url.'">'.$logs->name.'</a>';
            return $html;
        })
        ->addColumn('details', function ($logs) {
            $html2 = $logs->details;
            return $html2;
        })
        ->addColumn('version', function ($logs) {
            $html3 = $logs->version;
            return $html3;
        })
        ->addColumn('user', function ($logs) {
            $html5 = $logs->user;
            return $html5;
        })
        ->addColumn('date', function ($logs) {
            $html4 = $logs->created_at->__toString();
            return $html4;
        })
        ->rawColumns(['name', 'details', 'version', 'user', 'date'])
        ->make(true);

    }


    public function created_files(Request $request)
    {
         if($request->ajax()){
            $time=Carbon::now();
            $current_user = auth()->id();
            //LE PASO EL NOMBRE DE LA CARPETA
            $category_id = $request->category_id_addf;
            $nivel = $request->nivel_addf;
            $id_folder = $request->idfolder_addf;

            $name_category = Category::where('id', '=', $category_id)->get()->first();
            $folder = $name_category->name;

            //get filename with extension
            $filenamewithextension = $request->file('archivo_addf')->getClientOriginalName();
         
            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
         
            //get file extension
            $extension=$request->file('archivo_addf')->extension();
            //$extension = $request->file('archivo_addf')->getClientOriginalExtension();
            $filenameoriginal = $filename.'_'.$time.'.'.$extension;
                
            if($id_folder != 0){
                $cons_folder = Folder::where('id', '=', $id_folder)->get()->first();
                $url_folder = $cons_folder->url;
                //filename to store
                $filenametostore = $url_folder."/".$filename.'_'.$time.'.'.$extension;
                //Upload File to s3
                Storage::disk('s3')->put($filenametostore, fopen($request->file('archivo_addf'), 'r+'), 'public');
                //Storage::disk('s3')->put($filenametostore, 'public');
            }else{
                //filename to store
                $filenametostore = $folder."/".$filename.'_'.$time.'.'.$extension;
                Storage::disk('s3')->put($filenametostore, fopen($request->file('archivo_addf'), 'r+'), 'public');
                //Storage::disk('s3')->put($filenametostore, 'public');
            }
            
            $version="1";

            //VERIFICAR VERSIONAMIENTO
            if($extension == "doc" || $extension == "docx" || $extension == "xls" || $extension == "xlsx" || $extension == "vsd" || $extension == "ppt" || $extension == "pptx" || $extension == "DOC" || $extension == "DOCX" || $extension == "XLS" || $extension == "XLSX" || $extension == "VSD" || $extension == "PPT" || $extension == "PPTX"){
                $versionamiento="No";
            }else{
                $versionamiento="Si";
            }

            //GUARDAR REGISTROS
            $files = new File();
            $files -> name = $request->name_addf;
            //$filename = $files -> filename = basename($path);
            $filename = $files -> filename = $filenameoriginal;
            $files -> url = Storage::disk('s3')->url($filenametostore);
            $files -> size = Storage::disk('s3')->size($filenametostore);
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
        //$url = $files->url;
        $name_origin=$files->filename;
        $id_folder = $files->id_folder;
        $id_category = $files->category_id;

        if($id_folder==0){
            $category =Category::where('id', '=', $id_category)->get()->first();
            $path = $category->name;
            $filenametostore = $path."/".$name_origin;
            $url = Storage::disk('s3')->url($filenametostore);
        }else{
            $folder = Folder::where('id', '=', $id_folder)->get()->first();
            $path = $folder->url;
            $filenametostore = $path."/".$name_origin;
            $url = Storage::disk('s3')->url($filenametostore);
        }
        
        if($versionamiento == "No"){
            $users = User::where('id', '=', $current_user)->get()->first();
            $user_name = $users->name;
            
            if($block == 0){
                $file = File::find($file_id);
                $file -> bloqueado = '1';
                $file -> user_block = $user_name;
                $file -> save();
                return response()->json(['success'=>'disponible', 'url'=>$url]);
            }else if($user_name==$user){
                return response()->json(['success'=>'bloqueadoedit', 'id'=>$file_id]);
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
        ->addColumn('date', function ($folders) {
            $html4 = $folders->created_at->__toString();
            return $html4;
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
        ->rawColumns(['name', 'date', 'edit', 'delete'])
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
                $id_folder=0;
            }else{
                $url_ant = $request->url_addc;
                Storage::makeDirectory($url_ant."/".$folder_new);
                $url=$url_ant."/".$folder_new;

                $name_fold = explode('/',$url);
                $separar=$nivel-1;
                //$nom_fold = Folder::where('name', '=', $name_fold[$separar])->get()->first();
                $nom_fold = Folder::where('url', '=', $url_ant)->get()->first();
                $id_folder = $nom_fold->id;
            }
            $new_url = $url;
            //GUARDAR REGISTROS
            $folders = new Folder();
            $folders -> name = $folder_new;
            $folders -> name_uiscloud = $folder_new;
            $folders -> url = $new_url;
            $folders -> contenido = '0';
            $folders -> nivel = $nivel;
            $folders -> id_user = $current_user;
            $folders -> category_id = $category_id;
            $folders -> folder_id = $id_folder;
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
            $category_id = $request->category_id_editc;
            $nivel = $request->nivel_editc;

            //CARGAR LOS DATOS DEL ARCHIVO
            $folder_prev = Folder::where('id', '=', $id_folder)->get()->first();
            $url_prev = $folder_prev->url;
            $contenido = $folder_prev->contenido;
            $dir_folder_id = $folder_prev->folder_id;
            $name_prev = $folder_prev->name;

            $name_category = Category::where('id', '=', $category_id)->get()->first();
            $directory = $name_category->name;

            //LE PASO EL NOMBRE DE LA CARPETA
            $name_new = $request->name_editc;

            //$directories = Storage::allDirectories($directory);
            //CREAR CARPETA(directorio) EN S3
            /*if($contenido==0){
                Storage::deleteDirectory($url_prev);
                if($nivel==1){
                    $url=$directory."/".$name_new;
                    Storage::makeDirectory($url);
                }else{
                    $dirfolder = Folder::where('id', '=', $dir_folder_id)->get()->first();
                    $url_dirfolder = $dirfolder->url;
                    $url=$url_dirfolder."/".$name_new;
                    Storage::makeDirectory($url);
                }
                //CAMBIAMOS TODAS LAS URL CON EL NOMBRE DE LA NUEVA CARPETA
                $cons_folder = Folder::where('url', 'like', "%$name_prev%")->get();
                foreach($cons_folder as $folder){
                    $url_new = str_replace($name_prev, $name_new, $folder->url);
                    $cons_newfolder = Folder::find($folder->id);
                    $cons_newfolder -> url = $url_new;
                    $cons_newfolder -> id_user = $current_user;
                    $cons_newfolder -> save();
                }*/
                //GUARDAR CAMBIOS
                $folders = Folder::find($id_folder);
                $folders -> name_uiscloud = $name_new;
                $folders -> id_user = $current_user;
                $folders -> save();

                return response("actualizado");
            /*}else{
                return response("noactualizado");
            }*/
            //return response($directories);
         }
    }




    public function comprimir_files(Request $request)
    {
        if($request->ajax()){
            $id_files=$request->id;
            $current_user = auth()->id();

            $public_dir=public_path();
        	// Zip File Name
            $zipFileName = 'AllDocuments'.time().'.zip';
            // Create ZipArchive Obj
            $zip = new ZipArchive;

            if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {    
                // Add Multiple file   
                foreach($id_files as $file) {
                    $files = File::where('id', '=', $file)->get()->first();
                    $zip->addFromString($files->filename, file_get_contents($files->url));
                }        
                $zip->close();
            }
            // Set Header
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
            $filetopath=$public_dir.'/'.$zipFileName;
            // Create Download Response
            if(file_exists($filetopath)){
                $directorio = Storage::disk('s3')->put("ZIP/$zipFileName", file_get_contents($filetopath), 'public');
                $url = Storage::disk('s3')->url("ZIP/".$zipFileName);
                //ELIMINAMOS EL ARCHIVO LOCAL
                unlink($filetopath);

                return response($url);
            }

            //ELIMINAR DESDE CHECKBOX VARIOS FILES AL MISMO TIEMPO
            //$files = File::whereIn('id', $id_files);
            /*if($files->delete()){
                echo "file delete";
            }*/
            
        }
    }



    public function created_zip(Request $request)
    {
        if($request->ajax()){
            $time=Carbon::now();
            $current_user = auth()->id();
            //LE PASO EL NOMBRE DE LA CARPETA
            $category_id = $request->category_id_addz;
            $nivel = $request->nivel_addz;
            $id_folder = $request->idfolder_addz;
            
            $filezip = $request->file('archivo_addz');
            $filecount = count($filezip);

            $name_category = Category::where('id', '=', $category_id)->get()->first();
            $folder = $name_category->name;

            

            foreach($filezip as $filename) {
                $name = $filename->getClientOriginalName();
                //name file sin extension
                $name_sinext = pathinfo($name, PATHINFO_FILENAME);
                //extension file
                $extension = $filename->getClientOriginalExtension();
                $filenameoriginal = $name_sinext.'_'.$time.'.'.$extension;

                if($id_folder != 0){
                    $cons_folder = Folder::where('id', '=', $id_folder)->get()->first();
                    $url_folder = $cons_folder->url;
                    //filename to store
                    $filenametostore = $url_folder."/".$filenameoriginal;
                    //Upload File to s3
                    Storage::disk('s3')->put($filenametostore, fopen($filename, 'r+'), 'public');
                    //Storage::disk('s3')->put($filenametostore, 'public');
                }else{
                    //filename to store
                    $filenametostore = $folder."/".$filenameoriginal;
                    Storage::disk('s3')->put($filenametostore, fopen($filename, 'r+'), 'public');
                    //Storage::disk('s3')->put($filenametostore, 'public');
                }    
  
                $version="1";
                //VERIFICAR VERSIONAMIENTO
                if($extension == "doc" || $extension == "docx" || $extension == "xls" || $extension == "xlsx" || $extension == "vsd" || $extension == "ppt" || $extension == "pptx" || $extension == "DOC" || $extension == "DOCX" || $extension == "XLS" || $extension == "XLSX" || $extension == "VSD" || $extension == "PPT" || $extension == "PPTX"){
                    $versionamiento="No";
                }else{
                    $versionamiento="Si";
                }

                //GUARDAR REGISTROS
                $files = new File();
                $files -> name = $name_sinext;
                $files -> filename = $filenameoriginal;
                $files -> url = Storage::disk('s3')->url($filenametostore);
                $files -> size = Storage::disk('s3')->size($filenametostore);
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

                //GUARDAR LA RELACION DE LOS USUARIOS PARA EL ARCHIVO
                $users = User::whereHas('categories', function($query) use($category_id){
                    $query->where('category_id', '=', $category_id);
                })->get();
                foreach ($users as $us){
                    $files->users()->attach($us->id);
                }
                    
            }

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

            return response('guardado');
            
            

            /*/SUBO EL ARCHIVO ZIP
            $zip = new ZipArchive;
            $nombresFichZIP = array();
            $public_dir=public_path();
        	// Zip File Name
            $zipFileName = 'CreateZIP'.time().'.zip';
            if ($zip->open($public_dir.'/'.$zipFileName, ZipArchive::CREATE) === TRUE) {    
                // Add Multiple file 
                foreach($filezip as $fzip) {
                    $content = file_get_contents($fzip);
                    $zip->addFromString(pathinfo ( $fzip->getClientOriginalName(), PATHINFO_BASENAME), $content);
                    
                }        
                $zip->close();
            }
            // Set Header
            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
            $ziptopath=$public_dir.'/'.$zipFileName;
            // Create Download Response
            if(file_exists($ziptopath)){
                $directorio = Storage::disk('s3')->put("ZIP/$zipFileName", file_get_contents($ziptopath), 'public');
                $url = Storage::disk('s3')->url("ZIP/".$zipFileName);
            }*/
            /*
            $nombresFichZIP = array();
            $zip2 = new ZipArchive;
            if ($zip2->open($ziptopath) === TRUE){
                for($i = 0; $i < $zip2->numFiles; $i++){
	                //obtenemos nombre del fichero con extension
	                $nombresFichZIP['name'][$i] = $zip2->getNameIndex($i);
                    //get filename without extension
                    $filename[$i] = pathinfo($nombresFichZIP['name'][$i], PATHINFO_FILENAME);
                    //get file extension
                    $extension[$i] = pathinfo($nombresFichZIP['name'][$i], PATHINFO_EXTENSION);
                    $filenameoriginal[$i] = $filename[$i].'_'.$time.'.'.$extension[$i];
                    if($id_folder != 0){
                        $cons_folder = Folder::where('id', '=', $id_folder)->get()->first();
                        $url_folder = $cons_folder->url;
                        //filename to store
                        $filenametostore[$i] = $url_folder."/".$filenameoriginal[$i];
                        //Upload File to s3
                        Storage::disk('s3')->put($filenametostore[$i], 'public');
                    }else{
                        //filename to store
                        $filenametostore[$i] = $folder."/".$filenameoriginal[$i];
                        Storage::disk('s3')->put($filenametostore[$i], 'public');
                    }
                    $version[$i]="1";
                    //VERIFICAR VERSIONAMIENTO
                    if($extension[$i] == "doc" || $extension[$i] == "docx" || $extension[$i] == "xls" || $extension[$i] == "xlsx" || $extension[$i] == "vsd" || $extension[$i] == "ppt" || $extension[$i] == "pptx"){
                        $versionamiento[$i]="No";
                    }else{
                        $versionamiento[$i]="Si";
                    }
                    //GUARDAR REGISTROS
                    $files[$i] = new File();
                    $files[$i] -> name = $filename[$i];
                    $files[$i] -> filename = $filenameoriginal[$i];
                    $files[$i] -> url = Storage::disk('s3')->url($filenametostore[$i]);
                    $files[$i] -> size = Storage::disk('s3')->size($filenametostore[$i]);
                    $files[$i] -> type = $extension[$i];
                    $files[$i] -> version = $version[$i];
                    $files[$i] -> id_user = $current_user;
                    $files[$i] -> category_id = $category_id;
                    $files[$i] -> versionamiento = $versionamiento[$i];
                    $files[$i] -> bloqueado = '0';
                    $files[$i] -> user_block = "";
                    $files[$i] -> id_folder = $id_folder;
                    $files[$i] -> nivel = $nivel;
                    //GUARDAR
                    $files[$i] -> save();
                    //GUARDAR LA RELACION DE LOS USUARIOS PARA EL ARCHIVO
                    $users[$i] = User::whereHas('categories', function($query) use($category_id){
                        $query->where('category_id', '=', $category_id);
                    })->get();
                    foreach ($users[$i] as $us[$i]){
                        $files[$i]->users()->attach($us[$i]->id);
                    }
                }
                $zip2->close();
            }*/

            /*/ELIMINAMOS EL ARCHIVO LOCAL
            unlink($ziptopath);
            
            //GUARDAR CONTENIDO EN CATEGORIA (CARPETA LLENA O VACIA)
            $categ = Category::find($category_id);
            $categ -> contenido = '1';
            $categ -> save();
            if($id_folder != 0){
                //GUARDAR CONTENIDO EN FOLDER (CARPETA LLENA O VACIA)
                $fold = Folder::find($id_folder);
                $fold -> contenido = '1';
                $fold -> save();
            }*/
            
            //return response('guardado');
        }
    }

    


    public function redirectToGoogleProvider(Google $googleDoc)
    {
        $client = $googleDoc->client();
        $auth_url = $client->createAuthUrl();
        return redirect($auth_url);

    }

    public function handleProviderGoogleCallback(Request $request)
    {

        if($request->has('code')){

            $client = $this->client;
            $client->authenticate($request->input('code'));
            $token = $client->getAccessToken();
            $request->session()->put('access_token',$token);


            return redirect('/home')->with('success','post saves successfully');

        }
        else{

            $client=$this->client;
            $auth_url = $client->createAuthUrl();
            return redirect($auth_url);
        }

    }

    
}