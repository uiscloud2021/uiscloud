<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\User;
use App\Models\File;

class LogController extends Controller
{
    //CONSTRUCTOR PARA PROTEGER FILES SOLO PARA LOGEADOS
    public function __construct(){
        $this->middleware('can:logs.index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$logs = Log::with('files')->get();
        $logs = Log::all();//Trae todos los registros de la tabla
        return view('log.index', compact('logs'));//le pasamos la variable articulos a nuestro index
    }
}
