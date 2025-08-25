@extends('layouts.admin')

@section('content')
<main class="app-wrapper">
    <div class="container-fluid">

        <div class="main-breadcrumb d-flex align-items-center my-3 position-relative">
            <h2 class="breadcrumb-title mb-0 flex-grow-1 fs-14">Painel Administrativo AMCIG</h2>
            <div class="flex-shrink-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-end mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dados</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4>Dados da Associação</h4>
                        
                    </div>
                    <div class="card-body">
                        <div class="row gy-6">
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-primary bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-user-star-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="20">117</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Associados Ativos</p>
                                        <div class="mt-3">
                                            <a href="#" class="btn btn-sm btn-primary">Ver Detalhes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-primary bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-time-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="1">17</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Associados aguardando aprovação</p>
                                        <div class="mt-3">
                                            <a href="#" class="btn btn-sm btn-primary">Detalhes</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-primary bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-home-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="500">100</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Moradores Associados</p>
                                        <div class="mt-3">
                                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-xl-3">
                                <div class="card border shadow-none mb-0">
                                    <div class="card-body">
                                        <div class="h-50px w-50px position-relative d-flex justify-content-center align-items-center text-primary bg-light-subtle rounded-2 fs-4">
                                            <i class="ri-store-line"></i>
                                        </div>
                                        <h2 class="mt-8 mb-2 fs-24 fw-semibold">
                                            <span class="counter-value" data-target="400">15</span>
                                        </h2>
                                        <p class="mb-0 text-truncate fs-16 mb-1">Comerciantes Associados</p>
                                        <div class="mt-3">
                                         
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
           
         
        </div>
       
    </div><!--End container-fluid-->
</main><!--End app-wrapper-->
@endsection
