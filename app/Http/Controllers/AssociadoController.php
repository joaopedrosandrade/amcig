<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AssociadoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe o dashboard do associado
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        return view('associado.dashboard', compact('user'));
    }

    /**
     * Exibe o perfil do associado
     *
     * @return \Illuminate\View\View
     */
    public function profile()
    {
        $user = Auth::user();
        
        return view('associado.profile', compact('user'));
    }

    /**
     * Atualiza o perfil do associado
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'cep' => 'required|string|max:9',
            'logradouro' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'required|string|max:255',
            'cidade' => 'required|string|max:255',
            'uf' => 'required|string|size:2',
            'nome_comercio' => 'nullable|string|max:255',
            'endereco_comercio' => 'nullable|string',
            'ramo_atividade' => 'nullable|string|max:255',
        ], [
            'name.required' => 'O nome completo é obrigatório.',
            'telefone.required' => 'O telefone é obrigatório.',
            'cep.required' => 'O CEP é obrigatório.',
            'logradouro.required' => 'O logradouro é obrigatório.',
            'numero.required' => 'O número é obrigatório.',
            'bairro.required' => 'O bairro é obrigatório.',
            'cidade.required' => 'A cidade é obrigatória.',
            'uf.required' => 'A UF é obrigatória.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validações específicas para comerciantes
        if ($user->tipo_associado === 'comerciante') {
            if (empty($request->nome_comercio) || empty($request->endereco_comercio) || empty($request->ramo_atividade)) {
                return redirect()->back()
                    ->withErrors(['tipo_associado' => 'Para comerciantes, todos os campos do comércio são obrigatórios.'])
                    ->withInput();
            }
        }

        // Atualiza os dados
        $user->update([
            'name' => $request->name,
            'telefone' => $request->telefone,
            'cep' => $request->cep,
            'logradouro' => $request->logradouro,
            'numero' => $request->numero,
            'complemento' => $request->complemento,
            'bairro' => $request->bairro,
            'cidade' => $request->cidade,
            'uf' => $request->uf,
            'nome_comercio' => $request->nome_comercio,
            'endereco_comercio' => $request->endereco_comercio,
            'ramo_atividade' => $request->ramo_atividade,
        ]);

        return redirect()->route('associado.profile')
            ->with('success', 'Perfil atualizado com sucesso!');
    }
}
