<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

    /**
     * Salva os dados do novo associado
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function associadoStore(Request $request)
    {
        try {
            // Validação dos dados
            $validator = Validator::make($request->all(), [
                'nome' => 'required|string|max:255',
                'cpf' => 'required|string|max:14|unique:users,cpf',
                'data_nascimento' => 'required|date|before:today',
                'telefone' => 'required|string|max:20',
                'email' => 'required|email|max:255|unique:users,email',
                'cep' => 'required|string|max:9',
                'logradouro' => 'required|string|max:255',
                'numero' => 'required|string|max:20',
                'complemento' => 'nullable|string|max:255',
                'bairro' => 'required|string|max:255',
                'cidade' => 'required|string|max:255',
                'uf' => 'required|string|size:2',
                'tipo_associado' => 'required|in:morador,comerciante,ambos',
                'nome_comercio' => 'nullable|string|max:255',
                'endereco_comercio' => 'nullable|string',
                'ramo_atividade' => 'nullable|string|max:255',
                'senha' => 'required|string|min:6|confirmed',
            ], [
                'nome.required' => 'O nome completo é obrigatório.',
                'cpf.required' => 'O CPF é obrigatório.',
                'cpf.unique' => 'Este CPF já está cadastrado.',
                'data_nascimento.required' => 'A data de nascimento é obrigatória.',
                'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
                'telefone.required' => 'O telefone é obrigatório.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'Digite um email válido.',
                'email.unique' => 'Este email já está cadastrado.',
                'cep.required' => 'O CEP é obrigatório.',
                'logradouro.required' => 'O logradouro é obrigatório.',
                'numero.required' => 'O número é obrigatório.',
                'bairro.required' => 'O bairro é obrigatório.',
                'cidade.required' => 'A cidade é obrigatória.',
                'uf.required' => 'A UF é obrigatória.',
                'tipo_associado.required' => 'O tipo de associado é obrigatório.',
                'tipo_associado.in' => 'Tipo de associado inválido.',
                'senha.required' => 'A senha é obrigatória.',
                'senha.min' => 'A senha deve ter pelo menos 6 caracteres.',
                'senha.confirmed' => 'A confirmação de senha não confere.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validações específicas para comerciantes
            if (in_array($request->tipo_associado, ['comerciante', 'ambos'])) {
                if (empty($request->nome_comercio) || empty($request->endereco_comercio) || empty($request->ramo_atividade)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Para comerciantes, todos os campos do comércio são obrigatórios.'
                    ], 422);
                }
            }

            // Validação de CEP (apenas São Mateus-ES)
            if ($request->cidade !== 'São Mateus' || $request->uf !== 'ES') {
                return response()->json([
                    'success' => false,
                    'message' => 'Apenas moradores e comerciantes de São Mateus-ES podem se associar.'
                ], 422);
            }

            DB::beginTransaction();

            // Cria o usuário
            $user = User::create([
                'name' => $request->nome,
                'cpf' => $request->cpf,
                'data_nascimento' => $request->data_nascimento,
                'telefone' => $request->telefone,
                'email' => $request->email,
                'password' => Hash::make($request->senha),
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'bairro' => $request->bairro,
                'cidade' => $request->cidade,
                'uf' => $request->uf,
                'tipo_associado' => $request->tipo_associado,
                'nome_comercio' => $request->nome_comercio,
                'endereco_comercio' => $request->endereco_comercio,
                'ramo_atividade' => $request->ramo_atividade,
                'status' => 'pendente',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cadastro realizado com sucesso! Aguarde a aprovação da diretoria.',
                'user_id' => $user->id
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
