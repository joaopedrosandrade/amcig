<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'FrontController@associadoCreate')->name('associado.index');;
Route::post('/associado/store', 'FrontController@associadoStore')->name('associado.store');
Route::get('/associado/success', 'FrontController@associadoSuccess')->name('associado.success');

// Rota pública para carteirinha virtual
Route::get('/carteirinha/{matricula}', 'CarteirinhaController@show')->name('carteirinha.show');
Route::get('/carteirinha/{matricula}/print', 'CarteirinhaController@print')->name('carteirinha.print');

Auth::routes();

// Rotas para associados logados
Route::middleware(['auth'])->group(function () {
    Route::get('/associado/dashboard', 'AssociadoController@dashboard')->name('associado.dashboard');
    Route::get('/associado/profile', 'AssociadoController@profile')->name('associado.profile');
    Route::put('/associado/profile', 'AssociadoController@updateProfile')->name('associado.profile.update');
});

Route::get('/home', 'HomeController@index')->name('home');

// primeiro esse

//    // ==> acrescentar
//    Route::get('/admin/login', 'Auth\AdminLoginController@index')->name('admin.login');
//    Route::post('/admin/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
//    
//    
//    // ==> troquei de lugar
//    Route::get('/admin', 'AdminController@index')->name('admin.dashboard');


// depois esse
Route::prefix('/admin')->group(function() {
    Route::get('/login', 'Auth\AdminLoginController@index')->name('admin.login');
    Route::post('/login', 'Auth\AdminLoginController@login')->name('admin.login.submit');
    
    // Rotas protegidas por autenticação admin
    Route::middleware(['auth:admin'])->group(function() {
        Route::get('/', 'AdminController@index')->name('admin.dashboard');
        Route::post('/logout', 'Auth\AdminLoginController@logout')->name('admin.logout');
        
        // Rotas para gerenciar associados
        Route::prefix('associados')->group(function() {
            Route::get('/', 'Admin\AssociadoController@index')->name('admin.associados.index');
            Route::get('/data', 'Admin\AssociadoController@data')->name('admin.associados.data');
            Route::get('/show', 'Admin\AssociadoController@show')->name('admin.associados.show');
            Route::post('/update-status', 'Admin\AssociadoController@updateStatus')->name('admin.associados.update-status');
            
            // Rotas para associados pendentes
            Route::get('/pendentes', 'Admin\AssociadoController@pendentes')->name('admin.associados.pendentes');
            Route::get('/pendentes/data', 'Admin\AssociadoController@pendentesData')->name('admin.associados.pendentes.data');
            Route::post('/aprovar', 'Admin\AssociadoController@aprovar')->name('admin.associados.aprovar');
            Route::post('/rejeitar', 'Admin\AssociadoController@rejeitar')->name('admin.associados.rejeitar');
        });
    });
});

// Rotas para associados autenticados
Route::middleware(['auth'])->group(function() {
    Route::get('/minhas-mensalidades', 'AssociadoPagamentoController@index')->name('associado.pagamentos');
    Route::get('/fatura', 'AssociadoPagamentoController@show')->name('associado.fatura');
    Route::post('/atualizar-fatura', 'AssociadoPagamentoController@atualizar')->name('associado.atualizar');
    Route::post('/cancelar-assinatura', 'AssociadoPagamentoController@cancelarAssinatura')->name('associado.cancelar');
});

// Rotas para webhooks (sem middleware de autenticação)
Route::post('/webhook/asaas', 'WebhookController@asaas')->name('webhook.asaas');
Route::get('/webhook/test', 'WebhookController@test')->name('webhook.test');

// Rota para testar conexão com Asaas (apenas para desenvolvimento)
Route::get('/test-asaas', function() {
    $asaasService = new App\Services\AsaasService();
    $result = $asaasService->testConnection();
    return response()->json($result);
})->name('test.asaas');

// Rota para testar criação de cliente no Asaas (apenas para desenvolvimento)
Route::get('/test-create-customer', function() {
    // Buscar um usuário para teste
    $user = App\User::where('status', 'aprovado')->first();
    
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Nenhum usuário aprovado encontrado para teste'
        ]);
    }
    
    $asaasService = new App\Services\AsaasService();
    
    try {
        $result = $asaasService->createCustomer($user);
        return response()->json([
            'success' => true,
            'message' => 'Cliente criado com sucesso',
            'data' => $result
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'user_data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'cpf' => $user->cpf
            ]
        ]);
    }
})->name('test.create.customer');

// Rota para testar validação de CPF (apenas para desenvolvimento)
Route::get('/test-cpf/{cpf}', function($cpf) {
    $asaasService = new App\Services\AsaasService();
    
    try {
        // Usar reflexão para acessar o método privado
        $reflection = new ReflectionClass($asaasService);
        $method = $reflection->getMethod('validarCPF');
        $method->setAccessible(true);
        
        $cpfValidado = $method->invoke($asaasService, $cpf);
        
        return response()->json([
            'success' => true,
            'message' => 'CPF válido',
            'cpf_original' => $cpf,
            'cpf_validado' => $cpfValidado
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'cpf_original' => $cpf
        ]);
    }
})->name('test.cpf');

// Rota para testar criação de assinatura no Asaas (apenas para desenvolvimento)
Route::get('/test-create-subscription/{user_id}', function($user_id) {
    try {
        $user = App\User::find($user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não encontrado']);
        }

        $asaasService = new App\Services\AsaasService();
        
        // Primeiro criar cliente
        $customerData = $asaasService->createCustomer($user);
        $asaasCustomerId = $customerData['id'];
        
        // Depois criar assinatura
        $subscriptionData = $asaasService->createSubscription($user, $asaasCustomerId);
        
        return response()->json([
            'success' => true,
            'customer' => $customerData,
            'subscription' => $subscriptionData
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
})->name('test.create.subscription');

// Rota para testar busca de pagamentos de uma assinatura (apenas para desenvolvimento)
Route::get('/test-subscription-payments/{subscription_id}', function($subscription_id) {
    try {
        $asaasService = new App\Services\AsaasService();
        $payments = $asaasService->getSubscriptionPayments($subscription_id);
        
        return response()->json([
            'success' => true,
            'subscription_id' => $subscription_id,
            'payments' => $payments
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
})->name('test.subscription.payments');








