<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Evento;
use App\PresencaEvento;
use Illuminate\Support\Facades\Validator;

class EventoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $eventos = Evento::orderBy('data_evento', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->get();

        return view('admin.eventos.index', compact('eventos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.eventos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Debug: verificar autenticação
        if (!auth('admin')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_evento' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fim' => 'nullable|after:hora_inicio',
            'local' => 'required|string|max:255',
            'tipo' => 'required|in:assembleia,reuniao,palestra,workshop,outro',
            'pauta' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'quorum_minimo' => 'nullable|integer|min:1',
            'criar_lista_presenca' => 'nullable|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Preparar dados para criação do evento
            $eventoData = $request->except(['criar_lista_presenca']);
            
            $evento = Evento::create($eventoData);
            
            \Log::info('Evento criado com sucesso', ['id' => $evento->id, 'titulo' => $evento->titulo]);

            // Se foi solicitado criar lista de presença automaticamente
            if ($request->criar_lista_presenca == '1') {
                // Gerar link único para presença usando o método do modelo
                $evento->gerarLinkPresenca();
                $evento->update(['lista_presenca_ativa' => true]);
                
                \Log::info('Link de presença criado automaticamente', [
                    'evento_id' => $evento->id, 
                    'link' => $evento->link_presenca
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Evento criado com sucesso!' . 
                    ($request->criar_lista_presenca == '1' ? 
                        ' Link de presença foi gerado e ativado automaticamente.' : ''),
                'redirect' => route('admin.eventos.index')
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar evento', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $evento = Evento::with('presencas.user')->findOrFail($id);
        return view('admin.eventos.show', compact('evento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $evento = Evento::findOrFail($id);
        return view('admin.eventos.edit', compact('evento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $evento = Evento::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_evento' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'nullable|after:hora_inicio',
            'local' => 'required|string|max:255',
            'tipo' => 'required|in:assembleia,reuniao,palestra,workshop,outro',
            'status' => 'required|in:agendado,em_andamento,concluido,cancelado',
            'pauta' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'quorum_minimo' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $evento->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Evento atualizado com sucesso!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $evento = Evento::findOrFail($id);
        $evento->delete();

        return response()->json([
            'success' => true,
            'message' => 'Evento excluído com sucesso!'
        ]);
    }

    /**
     * Gera link de lista de presença
     */
    public function gerarLinkPresenca($id)
    {
        $evento = Evento::findOrFail($id);
        $link = $evento->gerarLinkPresenca();

        return response()->json([
            'success' => true,
            'link' => route('evento.presenca', $link),
            'message' => 'Link gerado com sucesso!'
        ]);
    }

    /**
     * Ativa/desativa lista de presença
     */
    public function toggleListaPresenca($id)
    {
        $evento = Evento::findOrFail($id);

        if ($evento->lista_presenca_ativa) {
            $evento->desativarListaPresenca();
            $message = 'Lista de presença desativada!';
        } else {
            $evento->ativarListaPresenca();
            $message = 'Lista de presença ativada!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'ativa' => $evento->lista_presenca_ativa
        ]);
    }

    /**
     * Exibe lista de presenças
     */
    public function presencas($id)
    {
        $evento = Evento::with('presencas.user')->findOrFail($id);
        return view('admin.eventos.presencas', compact('evento'));
    }

    /**
     * Exporta lista de presenças
     */
    public function exportarPresencas($id)
    {
        $evento = Evento::with('presencas.user')->findOrFail($id);

        $csvData = [];
        $csvData[] = ['Nome', 'CPF', 'Email', 'Telefone', 'Data/Hora Presença', 'Observações'];

        foreach ($evento->presencas as $presenca) {
            $csvData[] = [
                $presenca->nome ?? $presenca->user->name ?? 'N/A',
                $presenca->cpf ?? $presenca->user->cpf ?? 'N/A',
                $presenca->email ?? $presenca->user->email ?? 'N/A',
                $presenca->telefone ?? $presenca->user->telefone ?? 'N/A',
                $presenca->data_presenca->format('d/m/Y H:i'),
                $presenca->observacoes ?? 'N/A'
            ];
        }

        $filename = 'lista_presenca_' . $evento->titulo . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= '"' . implode('","', $row) . '"' . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}