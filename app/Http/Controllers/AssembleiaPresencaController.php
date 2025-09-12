<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Assembleia;
use App\Presenca;
use App\User;
use Illuminate\Support\Facades\Validator;

class AssembleiaPresencaController extends Controller
{
    /**
     * Exibe o formulário de lista de presença
     */
    public function show($link)
    {
        $assembleia = Assembleia::where('link_presenca', $link)
            ->where('lista_presenca_ativa', true)
            ->firstOrFail();

        return view('assembleia.presenca', compact('assembleia'));
    }

    /**
     * Processa a lista de presença
     */
    public function store(Request $request, $link)
    {
        $assembleia = Assembleia::where('link_presenca', $link)
            ->where('lista_presenca_ativa', true)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|max:14',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        // Remove formatação do CPF
        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);

        // Verifica se já existe presença com este CPF nesta assembleia
        $presencaExistente = Presenca::where('assembleia_id', $assembleia->id)
            ->where('cpf', $cpf)
            ->first();

        if ($presencaExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Você já está registrado nesta assembleia!'
            ], 422);
        }

        // Tenta encontrar o usuário pelo CPF
        $user = User::where('cpf', $cpf)->first();

        // Cria a presença
        Presenca::create([
            'assembleia_id' => $assembleia->id,
            'user_id' => $user ? $user->id : null,
            'nome' => $request->nome,
            'cpf' => $cpf,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'observacoes' => $request->observacoes,
            'data_presenca' => now(),
            'ip_address' => $request->ip()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Presença registrada com sucesso!'
        ]);
    }

    /**
     * Busca usuário por CPF para preenchimento automático
     */
    public function buscarUsuario(Request $request)
    {
        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
        
        if (strlen($cpf) != 11) {
            return response()->json(['success' => false]);
        }

        $user = User::where('cpf', $cpf)->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'user' => [
                    'nome' => $user->name,
                    'email' => $user->email,
                    'telefone' => $user->telefone
                ]
            ]);
        }

        return response()->json(['success' => false]);
    }
}
