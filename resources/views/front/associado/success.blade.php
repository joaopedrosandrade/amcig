<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8" />
    <title>Cadastro Realizado - AMCIG</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Cadastro Realizado AMCIG" name="description" />
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
    
    <!-- CSS personalizado -->
    <style>
        .success-icon {
            font-size: 4rem;
            color: #10b981;
            margin-bottom: 1rem;
        }
        
        .success-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .success-header {
            background: #10b981;
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        
        .success-body {
            padding: 3rem 2rem;
            text-align: center;
            background: white;
        }
        
        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .next-steps {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }
        
        .text-primary {
            color: #3b82f6 !important;
        }
        
        .success-card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            transition: box-shadow 0.3s ease;
        }
    </style>
</head>

<body>



<!-- Conteúdo Principal -->
<div class="container main-content">
    <div class="row justify-content-center align-items-center min-vh-100 pt-8 pb-10">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <div class="card success-card mx-xxl-8 shadow-none">
              
                
                <div class="success-body">
                    <div class="text-center mb-4">
                        <img src="{{asset('assets/images/logos.png')}}" alt="Logo AMCIG" class="img-fluid" style="max-height: 100px;">
                      </div>
                    <h2 class="fw-bold mb-2">Parabéns!</h2>
                    <p class="mb-0 fs-5">Seu cadastro foi realizado com sucesso</p>
                    
                    <div class="info-box">
                        <h5 class="text-primary mb-3">
                            <i class="ri-information-line me-2"></i>O que acontece agora?
                        </h5>
                        <p class="mb-0 text-muted">
                            Seu cadastro foi enviado para análise da diretoria da AMCIG. 
                            Em breve você receberá uma notificação por email sobre o status da sua aprovação.
                        </p>
                    </div>
                    
                    <div class="next-steps">
                        <h6 class="text-primary mb-3">
                            <i class="ri-list-check me-2"></i>Próximos passos:
                        </h6>
                        <ul class="text-muted mb-0">
                            <li>Aguardar análise da diretoria (1-3 dias úteis)</li>
                            <li>Receber email de confirmação</li>
                           
                            <li>Fazer login na área do associado</li>
                            <li>Realizar o pagamento da primeira mensalidade</li>
                            <li>Acessar recursos exclusivos da associação</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary me-3">
                            <i class="ri-login-box-line me-2"></i>Fazer Login
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-primary">
                            <i class="ri-home-line me-2"></i>Voltar ao Início
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="ri-time-line me-1"></i>
                            Tempo estimado para aprovação: 1-3 dias úteis
                        </small>
                    </div>
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

</body>
</html>
