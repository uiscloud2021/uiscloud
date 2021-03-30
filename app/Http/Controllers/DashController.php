<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\File;

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
        $current_user = auth()->user();
        $category_id = $request->selected_category;
        $category_name = $request->name_category;

        $files = File::where('category_id', $category_id)->get();
        //$files = 
        //$files = $current_user->files;
        //return $files;
        return view('dashboard.show', compact('files'))->with('category_name', $category_name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    
}