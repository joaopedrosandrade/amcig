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
     * Exibe a página de sucesso após o cadastro
     *
     * @return \Illuminate\View\View
     */
    public function associadoSuccess()
    {
        return view('front.associado.success');
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
                'nome' => 'required|string',
                'cpf' => 'required|string|unique:users,cpf',
                'data_nascimento' => 'required|date|before:today',
                'telefone' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'cep' => 'required|string',
                'logradouro' => 'required|string',
                'numero' => 'required|string',
                'complemento' => 'nullable|string',
                'bairro' => 'required|string',
                'cidade' => 'required|string',
                'uf' => 'required|string',
                'tipo_associado' => 'required|in:morador,comerciante',
                'nome_comercio' => 'nullable|string',
                'endereco_comercio' => 'nullable|string',
                'ramo_atividade' => 'nullable|string',
                'senha' => 'required|string|min:6',
                'aceiteTermos' => 'required',
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

                'aceiteTermos.required' => 'Você deve aceitar os termos e condições.',
                'aceiteTermos.in' => 'Você deve aceitar os termos e condições.',






            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validação da confirmação de senha
            if ($request->senha !== $request->senha_confirmation) {
                return response()->json([
                    'success' => false,
                    'message' => 'A confirmação de senha não confere.',
                    'errors' => ['senha' => ['A confirmação de senha não confere.']]
                ], 422);
            }

            // Validações específicas para comerciantes
            if ($request->tipo_associado === 'comerciante') {
                $errosComercio = [];
                
                if (empty($request->nome_comercio)) {
                    $errosComercio['nome_comercio'] = ['O nome do comércio é obrigatório para comerciantes.'];
                }
                
                if (empty($request->endereco_comercio)) {
                    $errosComercio['endereco_comercio'] = ['O endereço do comércio é obrigatório para comerciantes.'];
                }
                
                if (empty($request->ramo_atividade)) {
                    $errosComercio['ramo_atividade'] = ['O ramo de atividade é obrigatório para comerciantes.'];
                }
                
                if (!empty($errosComercio)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Para comerciantes, todos os campos do comércio são obrigatórios.',
                        'errors' => $errosComercio
                    ], 422);
                }
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
                'message' => 'Cadastro realizado com sucesso!',
                'redirect' => route('associado.success')
            ]);

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
