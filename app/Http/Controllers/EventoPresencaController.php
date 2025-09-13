<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evento;
use App\PresencaEvento;
use App\User;
use Illuminate\Support\Facades\Validator;

class EventoPresencaController extends Controller
{
    /**
     * Exibe o formulário de lista de presença
     */
    public function show($link)
    {
        $evento = Evento::where('link_presenca', $link)
            ->where('lista_presenca_ativa', true)
            ->firstOrFail();

        return view('evento.presenca', compact('evento'));
    }

    /**
     * Processa a lista de presença
     */
    public function store(Request $request, $link)
    {
        $evento = Evento::where('link_presenca', $link)
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

        // Verifica se já existe presença com este CPF neste evento
        $presencaExistente = PresencaEvento::where('evento_id', $evento->id)
            ->where('cpf', $cpf)
            ->first();

        if ($presencaExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Você já está registrado neste evento!'
            ], 422);
        }

        // Tenta encontrar o usuário pelo CPF
        $user = User::where('cpf', $cpf)->first();

        // Cria a presença
        PresencaEvento::create([
            'evento_id' => $evento->id,
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