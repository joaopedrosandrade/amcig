<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * Exibe a página de criação de associado
     *
     * @return \Illuminate\View\View
     */
    public function associadoCreate()
    {
        return view('front.associado.create');
    }
}
