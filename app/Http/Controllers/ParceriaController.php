<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Parceria;
use App\ConfiguracaoSistema;

class ParceriaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!ConfiguracaoSistema::isEnabled('menu_parcerias_visivel')) {
                abort(404, 'Funcionalidade não disponível no momento.');
            }
            return $next($request);
        });
    }
    /**
     * Exibe a listagem de parcerias para associados
     */
    public function index()
    {
        $parcerias = Parceria::ativas()
            ->ordenadas()
            ->get();

        // Separar parcerias em destaque das normais
        $parceriasDestaque = $parcerias->where('destaque', true);
        $parceriasNormais = $parcerias->where('destaque', false);

        // Agrupar por categoria
        $parceriasPorCategoria = $parcerias->groupBy('categoria');

        return view('associado.parcerias.index', compact('parceriasDestaque', 'parceriasNormais', 'parceriasPorCategoria'));
    }

    /**
     * Exibe os detalhes de uma parceria específica
     */
    public function show($id)
    {
        $parceria = Parceria::ativas()->findOrFail($id);

        // Buscar parcerias relacionadas (mesma categoria)
        $parceriasRelacionadas = Parceria::ativas()
            ->where('categoria', $parceria->categoria)
            ->where('id', '!=', $parceria->id)
            ->limit(4)
            ->get();

        return view('associado.parcerias.show', compact('parceria', 'parceriasRelacionadas'));
    }

    /**
     * Busca parcerias por categoria
     */
    public function categoria($categoria)
    {
        $parcerias = Parceria::ativas()
            ->where('categoria', $categoria)
            ->ordenadas()
            ->get();

        if ($parcerias->isEmpty()) {
            return redirect()->route('parcerias.index')
                ->with('error', 'Nenhuma parceria encontrada para esta categoria.');
        }

        return view('associado.parcerias.categoria', compact('parcerias', 'categoria'));
    }
}
