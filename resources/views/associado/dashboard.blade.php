<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Dashboard - AMCIG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Dashboard do Associado AMCIG" name="description" />
    <meta content="AMCIG" name="author" />
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!--icons css-->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Dashboard do Associado</h4>
                        <div>
                            <a href="{{ route('associado.profile') }}" class="btn btn-outline-primary btn-sm me-2">
                                <i class="ri-user-line me-1"></i>Meu Perfil
                            </a>
                            <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ri-logout-box-line me-1"></i>Sair
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Bem-vindo, {{ $user->name }}!</h5>
                                <p class="text-muted">Status: 
                                    <span class="badge bg-{{ $user->status === 'aprovado' ? 'success' : ($user->status === 'pendente' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </p>
                                <p class="text-muted">Tipo: {{ ucfirst($user->tipo_associado) }}</p>
                                <p class="text-muted">Email: {{ $user->email }}</p>
                                <p class="text-muted">Telefone: {{ $user->telefone }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Endereço:</h6>
                                <p class="text-muted">
                                    {{ $user->logradouro }}, {{ $user->numero }}
                                    @if($user->complemento)
                                        - {{ $user->complemento }}
                                    @endif
                                    <br>
                                    {{ $user->bairro }}<br>
                                    {{ $user->cidade }}/{{ $user->uf }}<br>
                                    CEP: {{ $user->cep }}
                                </p>
                                
                                @if($user->isComerciante())
                                    <h6>Informações do Comércio:</h6>
                                    <p class="text-muted">
                                        <strong>{{ $user->nome_comercio }}</strong><br>
                                        {{ $user->endereco_comercio }}<br>
                                        Ramo: {{ ucfirst($user->ramo_atividade) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        @if($user->status === 'pendente')
                            <div class="alert alert-warning mt-3">
                                <i class="ri-information-line me-2"></i>
                                <strong>Atenção:</strong> Sua conta ainda está pendente de aprovação pela diretoria. 
                                Você receberá uma notificação por email quando for aprovado.
                            </div>
                        @endif
                        
                        @if($user->status === 'aprovado')
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <i class="ri-calendar-event-line fs-1 mb-2"></i>
                                            <h6>Próximas Reuniões</h6>
                                            <p class="mb-0">Acompanhe as assembleias</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <i class="ri-file-text-line fs-1 mb-2"></i>
                                            <h6>Documentos</h6>
                                            <p class="mb-0">Acesse documentos importantes</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <i class="ri-team-line fs-1 mb-2"></i>
                                            <h6>Diretoria</h6>
                                            <p class="mb-0">Conheça nossa equipe</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
