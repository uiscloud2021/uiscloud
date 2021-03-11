<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use App\Models\User;

class DashController extends Controller
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
        $filecounter = File::count();//Trae el contador de los registros de la tabla
        $usercounter = User::count();
        return view('dashboard')->with('filecounter', $filecounter)->with('usercounter', $usercounter);//le pasamos las variables a nuestro dashboard
    }
}
