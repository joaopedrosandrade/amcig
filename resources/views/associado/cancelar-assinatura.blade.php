@extends('layouts.associado')

@section('title', 'Cancelar Assinatura - AMCIG')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">Cancelar Assinatura</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('associado.dashboard') }}">Início</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('associado.pagamentos') }}">Minhas Mensalidades</a></li>
                            <li class="breadcrumb-item active">Cancelar Assinatura</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div> <br>
        <!-- end page title -->

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ri-error-warning-line me-2 text-danger"></i>Confirmar Cancelamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="ri-alert-line me-2"></i>Atenção!
                            </h6>
                            <p class="mb-0">Você está prestes a cancelar sua assinatura. Esta ação não pode ser desfeita.</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Detalhes da Assinatura:</h6>
                                <ul class="list-unstyled">
                                    <li><strong>Valor:</strong> {{ $subscription->formatted_value }}</li>
                                    <li><strong>Tipo:</strong> {{ ucfirst($subscription->billing_type) }}</li>
                                    <li><strong>Status:</strong> 
                                        <span class="badge bg-success">Ativa</span>
                                    </li>
                                    <li><strong>Próximo Vencimento:</strong> {{ $subscription->formatted_next_due_date }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Consequências do Cancelamento:</h6>
                                <ul class="list-unstyled text-muted">
                                    <li><i class="ri-close-line text-danger me-1"></i> Não haverá mais cobranças automáticas</li>
                                    <li><i class="ri-close-line text-danger me-1"></i> Você perderá acesso aos benefícios</li>
                                    <li><i class="ri-close-line text-danger me-1"></i> Faturas pendentes continuarão válidas</li>
                                    <li><i class="ri-close-line text-danger me-1"></i> Será necessário reativar manualmente</li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('associado.pagamentos') }}" class="btn btn-secondary">
                                <i class="ri-arrow-left-line me-1"></i>Voltar
                            </a>
                            
                            <form method="POST" action="{{ route('associado.cancelar') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja cancelar sua assinatura? Esta ação não pode ser desfeita.')">
                                    <i class="ri-close-line me-1"></i>Confirmar Cancelamento
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection
