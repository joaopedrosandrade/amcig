<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ConfiguracaoSistema;
use Illuminate\Support\Facades\Validator;

class ConfiguracaoController extends Controller
{
    /**
     * Exibe a página de configurações
     */
    public function index()
    {
        $configuracoes = ConfiguracaoSistema::orderBy('categoria')->orderBy('nome')->get();
        
        // Agrupa por categoria
        $configuracoesPorCategoria = $configuracoes->groupBy('categoria');
        
        return view('admin.configuracoes.index', compact('configuracoesPorCategoria'));
    }

    /**
     * Atualiza uma configuração específica
     */
    public function update(Request $request, $id)
    {
        $configuracao = ConfiguracaoSistema::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'valor' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Valor é obrigatório'
            ], 422);
        }

        try {
            // Determina o tipo de valor e salva
            $valor = $request->valor;
            
            // Limpa todos os valores
            $configuracao->valor_boolean = null;
            $configuracao->valor_string = null;
            $configuracao->valor_integer = null;
            $configuracao->valor_text = null;
            
            // Define o valor apropriado
            if (is_bool($valor) || $valor === 'true' || $valor === 'false' || $valor === '1' || $valor === '0') {
                $configuracao->valor_boolean = filter_var($valor, FILTER_VALIDATE_BOOLEAN);
            } elseif (is_numeric($valor)) {
                $configuracao->valor_integer = (int) $valor;
            } elseif (is_string($valor) && strlen($valor) <= 255) {
                $configuracao->valor_string = $valor;
            } else {
                $configuracao->valor_text = $valor;
            }
            
            $configuracao->save();

            return response()->json([
                'success' => true,
                'message' => 'Configuração atualizada com sucesso!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar configuração: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ativa/desativa uma configuração
     */
    public function toggle($id)
    {
        $configuracao = ConfiguracaoSistema::findOrFail($id);
        
        $configuracao->ativo = !$configuracao->ativo;
        $configuracao->save();

        return response()->json([
            'success' => true,
            'message' => $configuracao->ativo ? 'Configuração ativada!' : 'Configuração desativada!',
            'ativo' => $configuracao->ativo
        ]);
    }

    /**
     * Inicializa configurações padrão do sistema
     */
    public function inicializar()
    {
        $configuracoesPadrao = [
            // Menus existentes no layout do associado
            ['chave' => 'menu_relatorios_visivel', 'nome' => 'Solicitações', 'descricao' => 'Exibir menu de solicitações para associados', 'categoria' => 'menus', 'valor' => true],
            ['chave' => 'menu_eventos_visivel', 'nome' => 'Financeiro', 'descricao' => 'Exibir menu financeiro para associados', 'categoria' => 'menus', 'valor' => true],
            ['chave' => 'menu_documentos_visivel', 'nome' => 'Informações', 'descricao' => 'Exibir menu de informações para associados', 'categoria' => 'menus', 'valor' => true],
            ['chave' => 'menu_parcerias_visivel', 'nome' => 'Parcerias', 'descricao' => 'Exibir menu de parcerias para associados', 'categoria' => 'menus', 'valor' => true],
            
            // Funcionalidades
            ['chave' => 'lista_presenca_ativa', 'nome' => 'Lista de Presença', 'descricao' => 'Permitir que associados confirmem presença em eventos', 'categoria' => 'funcionalidades', 'valor' => true],
            ['chave' => 'upload_documentos_ativa', 'nome' => 'Upload de Documentos', 'descricao' => 'Permitir que associados façam upload de documentos', 'categoria' => 'funcionalidades', 'valor' => false],
            ['chave' => 'comentarios_ativos', 'nome' => 'Comentários', 'descricao' => 'Permitir comentários em notícias e eventos', 'categoria' => 'funcionalidades', 'valor' => false],
        ];

        foreach ($configuracoesPadrao as $config) {
            ConfiguracaoSistema::set(
                $config['chave'],
                $config['valor'],
                $config['nome'],
                $config['descricao'],
                $config['categoria']
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Configurações padrão inicializadas com sucesso!'
        ]);
    }
}
