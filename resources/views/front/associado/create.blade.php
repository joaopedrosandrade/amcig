<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Cadastro de Associado - AMCIG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Cadastro de Associado AMCIG" name="description" />
    <meta content="AMCIG" name="author" />
    
    <!-- layout setup -->
    <script type="module" src="{{asset('assets/js/layout-setup.js')}}"></script>
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">    <!-- Simplebar Css -->
    <link rel="stylesheet" href="{{asset('assets/libs/simplebar/simplebar.min.css')}}">
    <!-- Swiper Css -->
    <link href="{{asset('assets/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
    <!-- Nouislider Css -->
    <link href="{{asset('assets/libs/nouislider/nouislider.min.css')}}" rel="stylesheet">
    <!-- Bootstrap Css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!--icons css-->
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css">
    <!-- SweetAlert2 CSS -->
    <link href="{{asset('assets/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css">
    

    
    <!-- IMask para máscaras -->
    <script src="https://unpkg.com/imask"></script>
    
    <!-- Modal de CEP Inválido -->
    <div class="modal fade" id="cepInvalidoModal" tabindex="-1" aria-labelledby="cepInvalidoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="cepInvalidoModalLabel">
                        <i class="ri-error-warning-line me-2"></i>CEP Não Permitido
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">
                        <strong>O CEP informado não é permitido.</strong>
                    </p>
                    <p class="text-muted mb-0">
                        Devido a restrições da associação, apenas moradores e comerciantes residentes em <strong>São Mateus-ES</strong> podem se cadastrar.
                    </p>
                    
                </div>
               
            </div>
        </div>
    </div>
    
    <!-- CSS personalizado para validação -->
    <style>
        .is-loading {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath d='M8 3a5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .cep-error {
            display: block;
        }
        
        .form-control.is-valid,
        .form-select.is-valid {
            border-color: #198754;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #dc3545;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4m0-1.4-1.4 1.4'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Estilos para a barra de progresso */
        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
            overflow: hidden;
        }
        
        .progress-bar {
            transition: width 0.6s ease;
            border-radius: 10px;
        }
        
        .progress-bar.bg-success {
            background-color: #198754 !important;
        }
        
        .progress-bar.bg-warning {
            background-color: #ffc107 !important;
        }
        
        .progress-bar.bg-danger {
            background-color: #dc3545 !important;
        }
        
        /* Responsividade para mobile */
        @media (max-width: 768px) {
            .progress-container {
                padding: 0.75rem;
                margin-bottom: 1rem !important;
            }
            
            .progress {
                height: 6px !important;
            }
            
            .progress-container small {
                font-size: 0.75rem;
            }
        }
        
        /* Estilos para o header fixo */
        .fixed-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-logo {
            height: 35px;
            width: auto;
        }
        
        .header-title {
            font-weight: 700;
            font-size: 1.2rem;
            color: #495057;
        }
        
        .header-progress {
            max-width: 500px;
            margin: 0 auto;
        }
        
        .header-progress .progress {
            background-color: #e9ecef;
            border-radius: 8px;
        }
        
        .header-progress small {
            font-size: 0.75rem;
        }
        
        /* Ajuste do conteúdo principal para o header fixo */
        .main-content {
            margin-top: 80px;
        }
        
        /* Responsividade do header */
        @media (max-width: 768px) {
            .fixed-header {
                padding: 0.75rem 0;
            }
            
            .header-logo {
                height: 30px;
            }
            
            .header-title {
                font-size: 1rem;
            }
            
            .header-progress small {
                font-size: 0.7rem;
            }
            
            .main-content {
                margin-top: 70px;
            }
        }
        
        @media (max-width: 576px) {
            .header-progress {
                max-width: 100%;
            }
            
            .header-progress .progress {
                height: 4px !important;
            }
        }
        
        /* Estilos para os cards de links informativos */
        .info-link-card {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            cursor: pointer;
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .info-link-card:hover {
            background: #e9ecef;
            border-color: #6c757d;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .info-link-card i {
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .info-link-card .fw-semibold {
            color: #495057;
            margin-bottom: 0.25rem;
        }
        
        .info-link-card small {
            color: #6c757d;
            line-height: 1.2;
        }
        
        /* Responsividade dos cards */
        @media (max-width: 768px) {
            .info-link-card {
                min-height: 80px;
                padding: 0.5rem !important;
            }
            
            .info-link-card .fw-semibold {
                font-size: 0.85rem;
            }
            
            .info-link-card small {
                font-size: 0.7rem;
            }
            
            .info-link-card i {
                font-size: 1.2rem !important;
                margin-bottom: 0.25rem !important;
            }
        }
        
        @media (max-width: 576px) {
            .info-link-card {
                min-height: 70px;
                padding: 0.4rem !important;
            }
            
            .info-link-card .fw-semibold {
                font-size: 0.8rem;
            }
            
            .info-link-card small {
                font-size: 0.65rem;
            }
            
            .info-link-card i {
                font-size: 1rem !important;
                margin-bottom: 0.2rem !important;
            }
            
            /* Reduzir margem inferior da seção de links */
            .main-content .text-center.mb-4 {
                margin-bottom: 1rem !important;
            }
        }
        
        /* Estilos para mensagens de erro */
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }
        
        .error-message.show {
            display: block;
        }
        
        .form-control.is-invalid + .error-message {
            display: block;
        }
    </style>
</head>

<body>

<!-- Header Fixo -->
<header class="fixed-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <!-- Logo -->
            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <img src="assets/images/logo-md.png" alt="Logo AMCIG" class="header-logo me-2">
                    <span class="header-title d-none d-md-block">AMCIG</span>
                </div>
            </div>
            
            <!-- Barra de Progresso -->
            <div class="col">
                <div class="header-progress">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted" id="progressoTexto">0%</small>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" id="barraProgresso" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="mt-1">
                        <small class="text-muted" id="camposRestantes">Faça parte, seja um associado</small>
                    </div>
                </div>
            </div>
            
            <!-- Menu Hambúrguer -->
            <div class="col-auto">
                <button class="btn btn-link text-muted p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#headerMenu" aria-controls="headerMenu">
                    <i class="ri-menu-line fs-4"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Menu Lateral -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="headerMenu" aria-labelledby="headerMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="headerMenuLabel">Menu AMCIG</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-unstyled">
            <li class="mb-3">
                <a href="{{ url('/') }}" class="text-decoration-none text-dark">
                    <i class="ri-home-line me-2"></i>Página Inicial
                </a>
            </li>
            <li class="mb-3">
                <a href="{{ route('login') }}" class="text-decoration-none text-dark">
                    <i class="ri-login-box-line me-2"></i>Área do Associado
                </a>
            </li>
            <li class="mb-3">
                <a href="#!" class="text-decoration-none text-dark">
                    <i class="ri-information-line me-2"></i>Sobre a AMCIG
                </a>
            </li>
            <li class="mb-3">
                <a href="#!" class="text-decoration-none text-dark">
                    <i class="ri-phone-line me-2"></i>Contato
                </a>
            </li>
            <li class="mb-3">
                <a href="#!" class="text-decoration-none text-dark">
                    <i class="ri-file-text-line me-2"></i>Documentos
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="container main-content">
    <div class="row justify-content-center align-items-center min-vh-100 pt-8 pb-10">
        <div class="col-12 col-md-12 col-lg-11 col-xl-10">
            <!-- Links Importantes -->
            <div class="text-center mb-4">
                <div class="row g-2 g-md-3 justify-content-center">
                    <div class="col-12 col-md-4">
                        <a href="#!" class="text-decoration-none">
                            <div class="info-link-card p-3 rounded">
                                <i class="ri-file-text-line fs-4 text-primary mb-2"></i>
                                <div class="fw-semibold">Termos de Uso do Associado</div>
                                <small class="text-muted">Conheça os direitos e deveres</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-md-4">
                        <a href="#!" class="text-decoration-none">
                            <div class="info-link-card p-3 rounded">
                                <i class="ri-calendar-event-line fs-4 text-primary mb-2"></i>
                                <div class="fw-semibold">Dados da Assembleia</div>
                                <small class="text-muted">Próximas reuniões e atas</small>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 col-md-4">
                        <a href="#!" class="text-decoration-none">
                            <div class="info-link-card p-3 rounded">
                                <i class="ri-team-line fs-4 text-primary mb-2"></i>
                                <div class="fw-semibold">Diretoria e Comitê</div>
                                <small class="text-muted">Conheça nossa equipe</small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card mx-xxl-8 shadow-none">
                <div class="card-body p-8">
                    <h3 class="fw-medium text-center">Cadastro de Associado</h3>
                    
                    <div class="text-center mb-4">
                        <img src="assets/images/logos.png" alt="Logo AMCIG" class="img-fluid" style="max-height: 100px;">
                    </div>
                    
                    <p class="mb-8 text-muted text-center">Preencha os dados abaixo para se tornar um associado</p>
                    
                    <form id="formAssociado" method="POST" action="{{ route('associado.store') }}">
                        @csrf
                        <!-- Dados pessoais primeiro -->
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite seu nome completo" required>
                                <div class="error-message" id="nome-error"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cpf" name="cpf" placeholder="000.000.000-00" required>
                                <div class="error-message" id="cpf-error"></div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="dataNascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="dataNascimento" name="data_nascimento" required>
                                <div class="error-message" id="dataNascimento-error"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="telefone" class="form-label">Telefone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000" required>
                                <div class="error-message" id="telefone-error"></div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required>
                            <div class="error-message" id="email-error"></div>
                        </div>
                        
                        <!-- CEP e Endereço -->
                        <div class="mb-4">
                            <label for="cep" class="form-label">CEP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="cep" name="cep" placeholder="00000-000" maxlength="9" required>
                            <div class="form-text">Digite o CEP para autopreenchimento do endereço</div>
                            <div class="error-message" id="cep-error"></div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="logradouro" class="form-label">Logradouro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro" placeholder="Rua, Avenida, etc." required>
                            <div class="error-message" id="logradouro-error"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="numero" class="form-label">Número <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="numero" name="numero" placeholder="Número" required>
                                <div class="error-message" id="numero-error"></div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="complemento" class="form-label">Complemento</label>
                                <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Apto, Casa, etc.">
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="bairro" class="form-label">Bairro <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Bairro" required>
                            <div class="error-message" id="bairro-error"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="cidade" class="form-label">Cidade <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cidade" name="cidade" value="São Mateus" readonly>
                                <div class="form-text">Apenas moradores e comerciantes de São Mateus podem se associar</div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="uf" class="form-label">UF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="uf" name="uf" value="ES" readonly>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="tipoAssociado" class="form-label">Tipo de Associado <span class="text-danger">*</span></label>
                            <select class="form-select" id="tipoAssociado" name="tipo_associado" required>
                                <option value="">Selecione o tipo</option>
                                <option value="morador">Morador</option>
                                <option value="comerciante">Comerciante</option>
                                <option value="ambos">Morador e Comerciante</option>
                            </select>
                            <div class="error-message" id="tipoAssociado-error"></div>
                        </div>
                        
                        <!-- Campos do comércio (aparecem condicionalmente) -->
                        <div id="camposComercio" class="d-none">
                            <h5 class="mb-3 text-primary">Informações do Comércio</h5>
                            
                            <div class="mb-4">
                                <label for="nomeComercio" class="form-label">Nome do Comércio <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nomeComercio" name="nome_comercio" placeholder="Nome do estabelecimento comercial">
                                <div class="error-message" id="nomeComercio-error"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="enderecoComercio" class="form-label">Endereço do Comércio <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="enderecoComercio" name="endereco_comercio" placeholder="Endereço completo do comércio">
                                <div class="error-message" id="enderecoComercio-error"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="ramoAtividade" class="form-label">Ramo de Atividade <span class="text-danger">*</span></label>
                                <select class="form-select" id="ramoAtividade" name="ramo_atividade">
                                    <option value="">Selecione o ramo</option>
                                    <option value="alimentacao">Alimentação</option>
                                    <option value="varejo">Varejo</option>
                                    <option value="servicos">Serviços</option>
                                    <option value="construcao">Construção</option>
                                    <option value="transporte">Transporte</option>
                                    <option value="saude">Saúde</option>
                                    <option value="educacao">Educação</option>
                                    <option value="lazer">Lazer e Turismo</option>
                                    <option value="outros">Outros</option>
                                </select>
                                <div class="error-message" id="ramoAtividade-error"></div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="senha" class="form-label">Senha <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite sua senha" required>
                                <button type="button" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted toggle-password" id="toggle-senha" data-target="senha"><i class="ri-eye-off-line align-middle"></i></button>
                            </div>
                            <div class="error-message" id="senha-error"></div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="confirmarSenha" class="form-label">Confirmar Senha <span class="text-danger">*</span></label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="confirmarSenha" name="senha_confirmation" placeholder="Confirme sua senha" required>
                                <button type="button" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted toggle-password" id="toggle-confirmarSenha" data-target="confirmarSenha"><i class="ri-eye-off-line align-middle"></i></button>
                            </div>
                            <div class="error-message" id="confirmarSenha-error"></div>
                        </div>
                        
                        <div class="my-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="aceiteTermos" name="aceiteTermos" required>
                                <label class="form-check-label" for="aceiteTermos">
                                    Li e aceito os <a href="#!" class="link">termos e condições</a> da associação <span class="text-danger">*</span>
                                </label>
                            </div>
                            <div class="error-message" id="aceiteTermos-error"></div>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn btn-primary w-100 mb-4">Cadastrar como Associado</button>
                        </div>
                    </form>
                    
                    <p class="text-center mt-6 mb-0 text-muted fs-13">Já é associado? <a href="{{ route('login') }}" class="link fw-semibold">Faça login aqui</a></p>
                </div>
            </div>
            <p class="position-relative text-center fs-13 mb-0">©
                <script>document.write(new Date().getFullYear())</script> AMCIG - Associação de Moradores e Comerciantes da Ilha de Guriri
            </p>
        </div>
    </div>
</div>

<!-- JAVASCRIPT -->
<script src="{{asset('assets/libs/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/js/scroll-top.init.js')}}"></script>
<script src="{{asset('assets/js/auth/auth.init.js')}}"></script>
<script src="{{asset('assets/js/action.js')}}"></script>

<script src="{{asset('assets/libs/sweetalert2/sweetalert2.min.js')}}"></script>

<script src="{{asset('assets/js/validacao.js')}}"></script>
<script>

</script>

</body>
</html>