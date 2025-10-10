<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\ContaBancaria;
use Illuminate\Http\Request;

class ContaBancariaController extends Controller
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
     * Exibe a listagem de contas bancárias
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $contas = ContaBancaria::orderBy('principal', 'desc')
            ->orderBy('nome')
            ->paginate(20);

        return view('admin.configuracoes.contas-bancarias.index', compact('contas'));
    }

    /**
     * Exibe formulário de criação
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.configuracoes.contas-bancarias.create');
    }

    /**
     * Salva uma nova conta bancária
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'banco' => 'required|string|max:255',
            'agencia' => 'nullable|string|max:20',
            'numero_conta' => 'nullable|string|max:30',
            'tipo_conta' => 'required|in:corrente,poupanca,aplicacao,caixa',
            'saldo_inicial' => 'required|numeric',
            'titular' => 'nullable|string|max:255',
            'cpf_cnpj_titular' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string',
            'ativo' => 'nullable|boolean',
            'principal' => 'nullable|boolean',
        ]);

        // Saldo atual começa igual ao saldo inicial
        $validatedData['saldo_atual'] = $validatedData['saldo_inicial'];
        
        // Se marcar como principal, desmarcar as outras
        if (isset($validatedData['principal']) && $validatedData['principal']) {
            ContaBancaria::where('principal', true)->update(['principal' => false]);
        }

        ContaBancaria::create($validatedData);

        return redirect()->route('admin.contas-bancarias.index')
            ->with('success', 'Conta bancária cadastrada com sucesso!');
    }

    /**
     * Exibe formulário de edição
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $conta = ContaBancaria::findOrFail($id);
        
        return view('admin.configuracoes.contas-bancarias.edit', compact('conta'));
    }

    /**
     * Atualiza uma conta bancária
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $conta = ContaBancaria::findOrFail($id);

        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'banco' => 'required|string|max:255',
            'agencia' => 'nullable|string|max:20',
            'numero_conta' => 'nullable|string|max:30',
            'tipo_conta' => 'required|in:corrente,poupanca,aplicacao,caixa',
            'saldo_inicial' => 'required|numeric',
            'titular' => 'nullable|string|max:255',
            'cpf_cnpj_titular' => 'nullable|string|max:20',
            'observacoes' => 'nullable|string',
            'ativo' => 'nullable|boolean',
            'principal' => 'nullable|boolean',
        ]);

        // Se marcar como principal, desmarcar as outras
        if (isset($validatedData['principal']) && $validatedData['principal']) {
            ContaBancaria::where('id', '!=', $id)
                ->where('principal', true)
                ->update(['principal' => false]);
        }

        $conta->update($validatedData);

        return redirect()->route('admin.contas-bancarias.index')
            ->with('success', 'Conta bancária atualizada com sucesso!');
    }

    /**
     * Remove uma conta bancária
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $conta = ContaBancaria::findOrFail($id);
        
        // Verificar se tem contas a pagar vinculadas
        if ($conta->contasPagar()->count() > 0) {
            return redirect()->route('admin.contas-bancarias.index')
                ->with('error', 'Não é possível excluir esta conta pois existem pagamentos vinculados a ela.');
        }

        $conta->delete();

        return redirect()->route('admin.contas-bancarias.index')
            ->with('success', 'Conta bancária excluída com sucesso!');
    }
}

