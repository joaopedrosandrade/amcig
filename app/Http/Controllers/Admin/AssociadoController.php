<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
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
     * Atualiza o status de um associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'status' => 'required|in:ativo,inativo,suspenso'
        ]);

        $associado = User::findOrFail($request->id);
        $associado->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status atualizado com sucesso!'
        ]);
    }


}
