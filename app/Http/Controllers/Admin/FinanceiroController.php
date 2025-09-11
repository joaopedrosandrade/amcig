<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Payment;
use App\Invoice;
use App\Subscription;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinanceiroController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Dashboard financeiro principal
     */
    public function index()
    {
        // Dados gerais de recebimentos
        $totalRecebido = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->sum('value');

        $totalPendente = Payment::where('status', 'PENDING')
            ->sum('value');

        $totalVencido = Payment::where('status', 'OVERDUE')
            ->sum('value');

        $totalEstornado = Payment::where('status', 'REFUNDED')
            ->sum('value');

        // Recebimentos do mês atual
        $recebimentosMesAtual = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->whereMonth('payment_date', Carbon::now()->month)
            ->whereYear('payment_date', Carbon::now()->year)
            ->sum('value');

        // Recebimentos do mês anterior
        $recebimentosMesAnterior = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->whereMonth('payment_date', Carbon::now()->subMonth()->month)
            ->whereYear('payment_date', Carbon::now()->subMonth()->year)
            ->sum('value');

        // Cálculo do crescimento percentual
        $crescimentoPercentual = 0;
        if ($recebimentosMesAnterior > 0) {
            $crescimentoPercentual = (($recebimentosMesAtual - $recebimentosMesAnterior) / $recebimentosMesAnterior) * 100;
        }

        // Contadores de faturas
        $faturasPendentes = Invoice::where('status', 'PENDING')->count();
        $faturasPagas = Invoice::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])->count();
        $faturasVencidas = Invoice::where('status', 'OVERDUE')->count();

        // Assinaturas ativas
        $assinaturasAtivas = Subscription::where('status', 'ACTIVE')->count();
        $assinaturasSuspensas = Subscription::where('status', 'SUSPENDED')->count();

        // Recebimentos por método de pagamento (últimos 30 dias)
        $recebimentosPorMetodo = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->where('payment_date', '>=', Carbon::now()->subDays(30))
            ->selectRaw('payment_method, SUM(value) as total')
            ->groupBy('payment_method')
            ->get();

        // Recebimentos dos últimos 12 meses para gráfico
        $recebimentosUltimos12Meses = [];
        for ($i = 11; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $total = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
                ->whereMonth('payment_date', $mes->month)
                ->whereYear('payment_date', $mes->year)
                ->sum('value');
            
            $recebimentosUltimos12Meses[] = [
                'mes' => $mes->format('M/Y'),
                'total' => $total
            ];
        }

        return view('admin.financeiro.dashboard', compact(
            'totalRecebido',
            'totalPendente',
            'totalVencido',
            'totalEstornado',
            'recebimentosMesAtual',
            'recebimentosMesAnterior',
            'crescimentoPercentual',
            'faturasPendentes',
            'faturasPagas',
            'faturasVencidas',
            'assinaturasAtivas',
            'assinaturasSuspensas',
            'recebimentosPorMetodo',
            'recebimentosUltimos12Meses'
        ));
    }

    /**
     * Lista de pagamentos
     */
    public function pagamentos(Request $request)
    {
        $query = Payment::with(['user', 'invoice']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_inicio')) {
            $query->where('payment_date', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('payment_date', '<=', $request->data_fim);
        }

        if ($request->filled('metodo_pagamento')) {
            $query->where('payment_method', $request->metodo_pagamento);
        }

        $pagamentos = $query->orderBy('payment_date', 'desc')->paginate(20);

        $statusOptions = [
            'PENDING' => 'Pendente',
            'CONFIRMED' => 'Confirmado',
            'RECEIVED' => 'Recebido',
            'RECEIVED_IN_CASH' => 'Recebido em Dinheiro',
            'OVERDUE' => 'Vencido',
            'REFUNDED' => 'Estornado',
            'RECEIVED_WITH_OVERDUE' => 'Recebido com Atraso'
        ];

        $metodosPagamento = Payment::distinct()->pluck('payment_method')->filter();

        return view('admin.financeiro.pagamentos', compact('pagamentos', 'statusOptions', 'metodosPagamento'));
    }

    /**
     * Lista de faturas
     */
    public function faturas(Request $request)
    {
        $query = Invoice::with(['user', 'subscription']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('data_inicio')) {
            $query->where('due_date', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('due_date', '<=', $request->data_fim);
        }

        $faturas = $query->orderBy('due_date', 'desc')->paginate(20);

        // Estatísticas para o dashboard
        $totalFaturas = Invoice::count();
        $faturasPendentes = Invoice::where('status', 'PENDING')->count();
        $faturasPagas = Invoice::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])->count();
        $faturasVencidas = Invoice::where('status', 'OVERDUE')->count();
        $faturasEstornadas = Invoice::where('status', 'REFUNDED')->count();

        // Faturas por status
        $faturasPorStatus = Invoice::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->orderBy('total', 'desc')
            ->get();

        // Faturas recentes
        $faturasRecentes = Invoice::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Faturas vencidas
        $faturasVencidasList = Invoice::with(['user'])
            ->where('status', 'OVERDUE')
            ->orderBy('due_date', 'desc')
            ->get();

        // Faturas por valor
        $valorTotalPendente = Invoice::where('status', 'PENDING')->sum('value');
        $valorTotalPago = Invoice::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])->sum('value');
        $valorTotalVencido = Invoice::where('status', 'OVERDUE')->sum('value');

        $statusOptions = [
            'PENDING' => 'Pendente',
            'CONFIRMED' => 'Confirmado',
            'RECEIVED' => 'Recebido',
            'RECEIVED_IN_CASH' => 'Recebido em Dinheiro',
            'OVERDUE' => 'Vencido',
            'REFUNDED' => 'Estornado',
            'RECEIVED_WITH_OVERDUE' => 'Recebido com Atraso'
        ];

        return view('admin.financeiro.faturas', compact(
            'faturas', 
            'statusOptions',
            'totalFaturas',
            'faturasPendentes',
            'faturasPagas',
            'faturasVencidas',
            'faturasEstornadas',
            'faturasPorStatus',
            'faturasRecentes',
            'faturasVencidasList',
            'valorTotalPendente',
            'valorTotalPago',
            'valorTotalVencido'
        ));
    }

    /**
     * Relatório de recebimentos
     */
    public function relatorio(Request $request)
    {
        $dataInicio = $request->filled('data_inicio') ? $request->data_inicio : Carbon::now()->startOfMonth();
        $dataFim = $request->filled('data_fim') ? $request->data_fim : Carbon::now()->endOfMonth();

        // Recebimentos por período
        $recebimentos = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->whereBetween('payment_date', [$dataInicio, $dataFim])
            ->with(['user', 'invoice'])
            ->orderBy('payment_date', 'desc')
            ->get();

        // Resumo por status
        $resumoStatus = Payment::whereBetween('payment_date', [$dataInicio, $dataFim])
            ->selectRaw('status, COUNT(*) as quantidade, SUM(value) as total')
            ->groupBy('status')
            ->get();

        // Resumo por método de pagamento
        $resumoMetodo = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->whereBetween('payment_date', [$dataInicio, $dataFim])
            ->selectRaw('payment_method, COUNT(*) as quantidade, SUM(value) as total')
            ->groupBy('payment_method')
            ->get();

        // Recebimentos por dia
        $recebimentosPorDia = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
            ->whereBetween('payment_date', [$dataInicio, $dataFim])
            ->selectRaw('DATE(payment_date) as data, COUNT(*) as quantidade, SUM(value) as total')
            ->groupBy('data')
            ->orderBy('data', 'desc')
            ->get();

        return view('admin.financeiro.relatorio', compact(
            'recebimentos',
            'resumoStatus',
            'resumoMetodo',
            'recebimentosPorDia',
            'dataInicio',
            'dataFim'
        ));
    }

    /**
     * Dados para gráficos (AJAX)
     */
    public function dadosGraficos(Request $request)
    {
        $periodo = $request->get('periodo', '12'); // meses

        $dados = [];
        for ($i = $periodo - 1; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            $total = Payment::whereIn('status', ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE'])
                ->whereMonth('payment_date', $mes->month)
                ->whereYear('payment_date', $mes->year)
                ->sum('value');
            
            $dados[] = [
                'mes' => $mes->format('M/Y'),
                'total' => $total
            ];
        }

        return response()->json($dados);
    }
}
