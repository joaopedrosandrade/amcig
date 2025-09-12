<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Assembleia;
use App\Presenca;
use Illuminate\Support\Facades\Validator;

class AssembleiaController extends Controller
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
        $assembleias = Assembleia::orderBy('data_assembleia', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->get();

        return view('admin.assembleias.index', compact('assembleias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.assembleias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_assembleia' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fim' => 'nullable|after:hora_inicio',
            'local' => 'required|string|max:255',
            'tipo' => 'required|in:ordinaria,extraordinaria',
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

        $assembleia = Assembleia::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Assembleia criada com sucesso!',
            'redirect' => route('admin.assembleias.index')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $assembleia = Assembleia::with('presencas.user')->findOrFail($id);
        return view('admin.assembleias.show', compact('assembleia'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $assembleia = Assembleia::findOrFail($id);
        return view('admin.assembleias.edit', compact('assembleia'));
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
        $assembleia = Assembleia::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'data_assembleia' => 'required|date',
            'hora_inicio' => 'required',
            'hora_fim' => 'nullable|after:hora_inicio',
            'local' => 'required|string|max:255',
            'tipo' => 'required|in:ordinaria,extraordinaria',
            'status' => 'required|in:agendada,em_andamento,concluida,cancelada',
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

        $assembleia->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Assembleia atualizada com sucesso!'
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
        $assembleia = Assembleia::findOrFail($id);
        $assembleia->delete();

        return response()->json([
            'success' => true,
            'message' => 'Assembleia excluída com sucesso!'
        ]);
    }

    /**
     * Gera link de lista de presença
     */
    public function gerarLinkPresenca($id)
    {
        $assembleia = Assembleia::findOrFail($id);
        $link = $assembleia->gerarLinkPresenca();

        return response()->json([
            'success' => true,
            'link' => route('assembleia.presenca', $link),
            'message' => 'Link gerado com sucesso!'
        ]);
    }

    /**
     * Ativa/desativa lista de presença
     */
    public function toggleListaPresenca($id)
    {
        $assembleia = Assembleia::findOrFail($id);

        if ($assembleia->lista_presenca_ativa) {
            $assembleia->desativarListaPresenca();
            $message = 'Lista de presença desativada!';
        } else {
            $assembleia->ativarListaPresenca();
            $message = 'Lista de presença ativada!';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'ativa' => $assembleia->lista_presenca_ativa
        ]);
    }

    /**
     * Exibe lista de presenças
     */
    public function presencas($id)
    {
        $assembleia = Assembleia::with('presencas.user')->findOrFail($id);
        return view('admin.assembleias.presencas', compact('assembleia'));
    }

    /**
     * Exporta lista de presenças
     */
    public function exportarPresencas($id)
    {
        $assembleia = Assembleia::with('presencas.user')->findOrFail($id);

        $csvData = [];
        $csvData[] = ['Nome', 'CPF', 'Email', 'Telefone', 'Data/Hora Presença', 'Observações'];

        foreach ($assembleia->presencas as $presenca) {
            $csvData[] = [
                $presenca->nome ?? $presenca->user->name ?? 'N/A',
                $presenca->cpf ?? $presenca->user->cpf ?? 'N/A',
                $presenca->email ?? $presenca->user->email ?? 'N/A',
                $presenca->telefone ?? $presenca->user->telefone ?? 'N/A',
                $presenca->data_presenca->format('d/m/Y H:i'),
                $presenca->observacoes ?? 'N/A'
            ];
        }

        $filename = 'lista_presenca_' . $assembleia->titulo . '_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= '"' . implode('","', $row) . '"' . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
