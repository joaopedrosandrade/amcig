<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8" />
    <title>AMCIG - Sistema Administrativo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta content="Dashboard Administrativo AMCIG" name="description" />
    <meta content="AMCIG" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- layout setup -->
    <script type="module" src="{{asset('assets/js/layout-setup.js')}}"></script>
    
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">    <!-- Simplebar Css -->
    <!-- plugin css -->
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/jsvectormap/jsvectormap.min.css')}}">

    <!-- Simplebar Css -->
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <!--datatable responsive css-->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
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
    
    @stack('styles')
</head>
<body>
<!-- begin::App -->
<div id="layout-wrapper">
    <!-- Begin Header -->
    <header class="app-header" id="appHeader">
        <div class="container-fluid w-100">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-inline-flex align-items-center gap-2">
                    <a href="index.html" class="align-items-end logo-main d-none me-5">
                        <img height="35" width="34" class="logo-dark" alt="Dark Logo" src="{{asset('assets/images/logo-md.png')}}"">
                        <h3 class="text-body-emphasis fw-bolder mb-0 ms-1">AMCIG</h3>
                    </a>
                    <button type="button" class="vertical-toggle btn header-btn" id="toggleSidebar" aria-label="Toggle Sidebar">
                        <i class="bi bi-arrow-bar-left header-icon"></i>
                    </button>
                    <button type="button" class="horizontal-toggle btn header-btn d-none" id="toggleHorizontal" aria-label="Toggle Menu">
                        <i class="ri-menu-2-line header-icon"></i>
                    </button>
                    <!-- Search Bar -->
                    <div class="form-icon right d-none d-md-block" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <input type="text" class="form-control form-control-icon bg-transparent rounded-pill min-w-300px" id="Search" placeholder="Pesquisa..." required>
                        <div class="search-btn">
                            <div><i class="ri-search-line text-muted fs-16"></i></div>
                            <div><span class="badge bg-light-subtle text-muted">PESQUISE POR NOME</span></div>
                        </div>
                    </div>
                </div>
                <div class="flex-shrink-0 d-flex align-items-center gap-4">
                    <div class="d-flex gap-2 align-items-center">
                        <div class="dropdown pe-dropdown-mega d-none d-md-block">
                            <button class="btn header-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                                <i class="bi bi-bell"></i>
                                <div class="icon-dot"></div>
                            </button>
                            <div class="dropdown-menu dropdown-mega-md header-dropdown-menu pe-noti-dropdown-menu p-0">
                                <div class="p-3 border-bottom">
                                    <h6 class="d-flex align-items-center mb-0">Notificação <span class="badge bg-warning-subtle text-warning ms-auto">4 não lidas</span></h6>
                                </div>
                                <div>
                                    <div class="noti-item">
                                        
                                        <div class="flex-grow-1">
                                            <a href="#!" class="text-decoration-none stretched-link">
                                                <h6 class="mb-1 fw-semibold">Solicitação de Associado</h6>
                                            </a>
                                            <p class="text-muted mb-2 fs-12 mb-2">Segunda-feira, 25/08 - 14:33</p>
                                            <div class="p-2 bg-body-tertiary bg-opacity-50 rounded">
                                                <p class="mb-0 lh-base fs-13">Ana Maria Oliveira - 134.184.515-20 - Morador</p>
                                            </div>
                                        </div>
                                        <a href="#!" class="position-absolute top-0 end-0 mt-2 me-3 fs-18 link link-danger z-1">
                                            <i class="bi bi-x"></i>
                                        </a>
                                    </div>
                                
                                 
                                 
                                </div>
                            </div>
                        </div>
                      
                        <button class="btn header-btn d-block d-md-none" type="button" data-bs-toggle="modal" data-bs-target="#searchModal">
                            <i class="ri-search-line"></i>
                        </button>
                      
                    </div>
                    <div class="dark-mode-btn" id="toggleMode">
                        <button class="btn header-btn active" id="lightModeBtn" type="button" aria-label="Switch to light mode">
                            <i class="bi bi-brightness-high"></i>
                        </button>
                        <button class="btn header-btn" id="darkModeBtn" type="button" aria-label="Switch to Dark mode">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </div>
                    <div class="dropdown pe-dropdown-mega d-none d-md-block">
                        <button class="header-profile-btn btn gap-1 text-start" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="d-none d-xl-block pe-2">
                                <span class="d-block mb-0 fs-12 fw-semibold">    {{ Auth::guard('admin')->user()->name }}</span>
                                <span class="d-block mb-0 fs-10 text-muted">    {{ Auth::guard('admin')->user()->email }}</span>
                            </div>
                            <span class="header-btn btn position-relative">
                                <img src="{{asset('assets/images/avatar/avatar-3.jpg')}}" alt="Avatar Image" class="img-fluid rounded-circle">
                            </span>
                        </button>
                        <div class="dropdown-menu dropdown-mega-sm header-dropdown-menu p-3">
                            <div class="border-bottom pb-2 mb-2 d-flex align-items-center gap-2">
                                <img src="{{asset('assets/images/avatar/avatar-3.jpg')}}" alt="Avatar Image" class="avatar-md">
                                <div>
                                    <a href="javascript:void(0)">
                                        <h6 class="mb-0 lh-base">  {{ Auth::guard('admin')->user()->name }}</h6>
                                    </a>
                                    <p class="mb-0 fs-13 text-muted"> {{ Auth::guard('admin')->user()->email }}</p>
                                </div>
                            </div>
                            <ul class="list-unstyled mb-1 border-bottom pb-1">
                                <li><a class="dropdown-item" href="pages-profile.html"><i class="bi bi-person me-2"></i> Perfil</a></li>
                               
                            </ul>
                            
                            <ul class="list-unstyled mb-0">
                                
                                <li>
                                    <form method="POST" action="{{ route('admin.logout') }}" class="d-inline" >
                                        @csrf
                                        <button type="submit" class="dropdown-item border-0 bg-transparent p-2 w-100 text-start" style="cursor: pointer; outline: none;">
                                            <i class="bi bi-box-arrow-right me-2"></i> Deslogar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- END Header -->
    
    <!-- Search Modal -->
    <div class="modal fade search-modal" id="searchModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header d-block">
              <div class="form-icon">
                <input type="text" class="form-control form-control-icon" id="searchInputInModal" placeholder="Search" required>
                <div class="search-btn w-44px">
                  <i class="ri-search-line text-muted fs-16"></i>
                </div>
                <button type="button" class="btn-close position-absolute end-0 top-50 translate-middle-y d-inline-block m-0" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            </div>
            <div class="modal-body" data-simplebar id="list-items">
              <ul class="list-unstyled mb-0" id="searchList"></ul>
            </div>
          </div>
        </div>
      </div>    <aside class="pe-app-sidebar" id="sidebar">
        <div class="pe-app-sidebar-logo px-6 d-flex align-items-center position-relative">
            <!--begin::Brand Image-->
            <a href="index.html" class="d-flex align-items-end logo-main">
                <img height="35" width="34" class="logo-dark" alt="Dark Logo" src="{{asset('assets/images/logo-md.png')}}">
                <img height="35" width="34" class="logo-light" alt="Light Logo" src="{{asset('assets/images/logo-md-light.png')}}">
                <h3 class="text-body-emphasis fw-bolder mb-0 ms-1">AMCIG</h3>
            </a>
            <button type="button" id="sidebarDefaultArrow" class="btn btn-sm p-0 fs-16 text-body-emphasis ms-auto float-end d-none icon-hover-btn d-none"><i class="ri-arrow-right-line fs-5"></i></button>
            <!--end::Brand Image-->
        </div>
        <nav class="pe-app-sidebar-menu nav nav-pills" data-simplebar id="sidebar-simplebar">
            <div class="d-flex align-items-start flex-column w-100">
                <ul class="pe-main-menu list-unstyled">
                    <!-- Main Menu -->
                   
                    <li class="pe-slide pe-has-sub">
                        <a href="#!" class="pe-nav-link">
                            <i class="ri-settings-3-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Dashboard</span>
                            <i class="ri-arrow-down-s-line pe-nav-arrow d-none"></i>
                        </a>
                    </li>
                    <li class="pe-slide pe-has-sub">
                        <a href="#collapseAssociados" class="pe-nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseAssociados">
                            <i class="ri-group-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Associados</span>
                            <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                        </a>
                        <ul class="pe-slide-menu collapse" id="collapseAssociados">
                            <li class="pe-slide-item"><a href="{{ route('admin.associados.index') }}" class="pe-nav-link">Cadastrados</a></li>
                            <li class="pe-slide-item"><a href="#" class="pe-nav-link">Aprovação</a></li>
                                </ul>
                            </li>
                    <li class="pe-slide pe-has-sub">
                        <a href="#collapseFinanceiro" class="pe-nav-link" data-bs-toggle="collapse" aria-expanded="false" aria-controls="collapseFinanceiro">
                            <i class="ri-bank-card-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Financeiro</span>
                                    <i class="ri-arrow-down-s-line pe-nav-arrow"></i>
                                </a>
                        <ul class="pe-slide-menu collapse" id="collapseFinanceiro">
                            <li class="pe-slide-item"><a href="#" class="pe-nav-link">Mensalidades</a></li>
                            <li class="pe-slide-item"><a href="#" class="pe-nav-link">Relatorios</a></li>
                                        </ul>
                                    </li>
                    <li class="pe-slide pe-has-sub">
                        <a href="#!" class="pe-nav-link">
                            <i class="ri-settings-3-line pe-nav-icon"></i>
                            <span class="pe-nav-content">Configurações</span>
                            <i class="ri-arrow-down-s-line pe-nav-arrow d-none"></i>
                        </a>
                    </li>
                 
                   
                  
                </ul>
                <!-- Widgets -->
                <div class="sidebar-widget text-center">
                    <img src="{{asset('assets/images/sidebar-widget.png')}}" alt="Widget Image">
                    <p class="text-muted fw-semibold">15 novas solicitações de associado</p>
                    <button class="btn btn-primary rounded-pill w-100">Ver Solicitações</button>
                </div>
            </div>
        </nav>
    </aside>    
    
  
    <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
  
    @yield('content')

  
    <div class="progress-wrap d-flex align-items-center justify-content-center cursor-pointer h-40px w-40px position-fixed" id="progress-scroll">
      <svg class="progress-circle w-100 h-100 position-absolute" viewBox="0 0 100 100">
        <circle cx="50" cy="50" r="45" class="progress" />
      </svg>
      <i class="ri-arrow-up-line fs-16 z-1 position-relative text-primary"></i>
    </div>
    <!-- END scroll top -->    <!-- Begin Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center gap-2">
                <script>document.write(new Date().getFullYear())</script> © AMCIG.
                <div class="text-sm-end d-none d-sm-block">
                    Equipe de Tecnologia AMCIG
                </div>
            </div>
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- End Begin page -->

<!-- JAVASCRIPT -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="{{asset('assets/libs/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/js/scroll-top.init.js')}}"></script>
<!-- Vector map-->
<script src="{{asset('assets/libs/jsvectormap/jsvectormap.min.js')}}"></script>
<script src="{{asset('assets/libs/jsvectormap/maps/world.js')}}"></script>

<script src="{{asset('assets/libs/apexcharts/apexcharts.min.js')}}"></script>

<script src="{{asset('assets/js/dashboard/analytics.init.js')}}"></script>

<!-- App js -->

<!--datatable js-->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script src="{{asset('assets/js/table/datatable.init.js')}}"></script>
<script src="{{asset('assets/js/app.js')}}"></script>

@stack('scripts')

</body>

</html>