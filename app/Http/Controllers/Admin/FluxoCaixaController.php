<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FluxoCaixaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Exibe a página de Contas a Pagar
     *
     * @return \Illuminate\View\View
     */
    public function contasPagar()
    {
        return view('admin.fluxo-caixa.contas-pagar');
    }

    /**
     * Exibe a página de Contas a Receber
     *
     * @return \Illuminate\View\View
     */
    public function contasReceber()
    {
        return view('admin.fluxo-caixa.contas-receber');
    }
}

