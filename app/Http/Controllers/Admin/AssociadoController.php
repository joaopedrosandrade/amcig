<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Events\AssociadoAprovado;
use App\Events\AssociadoRejeitado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssociadoController extends Controller
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
     * Exibe a listagem de associados
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $associados = User::select([
            'id',
            'name',
            'email',
            'cpf',
            'matricula',
            'tipo_associado',
            'status',
            'created_at'
        ])->where('status', 'aprovado')->get();

        // Formata as datas no fuso horário de São Paulo
        foreach ($associados as $associado) {
            if ($associado->created_at) {
                $associado->created_at_formatted = $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i');
            } else {
                $associado->created_at_formatted = 'N/A';
            }
        }

        return view('admin.associados.index', compact('associados'));
    }

    /**
     * Retorna os dados para o DataTable
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function data(Request $request)
    {
        $associados = User::select([
            'id',
            'name',
            'email',
            'cpf',
            'matricula',
            'tipo_associado',
            'status',
            'created_at'
        ])->where('status', 'aprovado')->get();

        $data = [];
        foreach ($associados as $associado) {
            $tipos = [
                'morador' => 'Morador',
                'comerciante' => 'Comerciante'
            ];

            $status = $associado->status ?? 'pendente';
            $badges = [
                'aprovado' => '<span class="badge bg-success">Aprovado</span>',
                'ativo' => '<span class="badge bg-success">Ativo</span>',
                'inativo' => '<span class="badge bg-danger">Inativo</span>',
                'pendente' => '<span class="badge bg-warning">Pendente</span>',
                'suspenso' => '<span class="badge bg-secondary">Suspenso</span>'
            ];

            $data[] = [
                'id' => $associado->id,
                'name' => $associado->name,
                'email' => $associado->email,
                'cpf' => $associado->cpf ?? 'N/A',
                'matricula' => $associado->matricula ?? 'N/A',
                'tipo_associado' => $tipos[$associado->tipo_associado] ?? 'N/A',
                'status' => $badges[$status] ?? '<span class="badge bg-warning">Pendente</span>',
                'created_at' => $associado->created_at ? $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : 'N/A',
                'actions' => '<button type="button" class="btn btn-sm btn-info view-associado" data-id="' . $associado->id . '" title="Visualizar">
                                <i class="ri-eye-line"></i>
                            </button>'
            ];
        }

        return response()->json([
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data)
        ]);
    }

    /**
     * Exibe os detalhes de um associado
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $associado = User::findOrFail($request->id);
        
        return view('admin.associados.show', compact('associado'));
    }

    /**
     * Exibe a listagem de associados pendentes
     *
     * @return \Illuminate\View\View
     */
    public function pendentes()
    {
        $associadosPendentes = User::select([
            'id',
            'name',
            'email',
            'cpf',
            'matricula',
            'tipo_associado',
            'status',
            'created_at'
        ])->where('status', 'pendente')->get();

        // Formata as datas no fuso horário de São Paulo
        foreach ($associadosPendentes as $associado) {
            if ($associado->created_at) {
                $associado->created_at_formatted = $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i');
            } else {
                $associado->created_at_formatted = 'N/A';
            }
        }

        return view('admin.associados.pendentes', compact('associadosPendentes'));
    }

    /**
     * Retorna os dados para o DataTable dos associados pendentes
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendentesData(Request $request)
    {
        $associados = User::select([
            'id',
            'name',
            'email',
            'cpf',
            'matricula',
            'tipo_associado',
            'status',
            'created_at'
        ])->where('status', 'pendente')->get();

        $data = [];
        foreach ($associados as $associado) {
            $tipos = [
                'morador' => 'Morador',
                'comerciante' => 'Comerciante'
            ];

            $data[] = [
                'id' => $associado->id,
                'name' => $associado->name,
                'email' => $associado->email,
                'cpf' => $associado->cpf ?? 'N/A',
                'matricula' => $associado->matricula ?? 'N/A',
                'tipo_associado' => $tipos[$associado->tipo_associado] ?? 'N/A',
                'created_at' => $associado->created_at ? $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : 'N/A',
                'actions' => '<div class="btn-group" role="group">
                                <button type="button" class="btn btn-sm btn-info view-associado" data-id="' . $associado->id . '" title="Visualizar">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-success aprovar-associado" data-id="' . $associado->id . '" title="Aprovar">
                                    <i class="ri-check-line"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger rejeitar-associado" data-id="' . $associado->id . '" title="Rejeitar">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>'
            ];
        }

        return response()->json([
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data)
        ]);
    }

    /**
     * Aprova um associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function aprovar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id'
        ]);

        $associado = User::findOrFail($request->id);
        
        // Verifica se o associado está pendente
        if ($associado->status !== 'pendente') {
            return response()->json([
                'success' => false,
                'message' => 'Este associado não está pendente de aprovação.'
            ], 400);
        }

        $associado->update([
            'status' => 'aprovado',
            'data_aprovacao' => now()
        ]);

        // Disparar evento de aprovação
        event(new AssociadoAprovado($associado));

        return response()->json([
            'success' => true,
            'message' => 'Associado aprovado com sucesso!'
        ]);
    }

    /**
     * Rejeita um associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejeitar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'motivo' => 'nullable|string|max:500'
        ]);

        $associado = User::findOrFail($request->id);
        
        // Verifica se o associado está pendente
        if ($associado->status !== 'pendente') {
            return response()->json([
                'success' => false,
                'message' => 'Este associado não está pendente de aprovação.'
            ], 400);
        }

        $associado->update([
            'status' => 'rejeitado',
            'motivo_rejeicao' => $request->motivo
        ]);

        // Disparar evento de rejeição
        event(new AssociadoRejeitado($associado, $request->motivo));

        return response()->json([
            'success' => true,
            'message' => 'Associado rejeitado com sucesso!'
        ]);
    }
}
