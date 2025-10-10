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

// Rota home para redirecionamento após login
Route::get('/home', function() {
    return redirect()->route('associado.dashboard');
})->name('home');

// Rotas públicas para carteirinha virtual
Route::get('/carteirinha/{matricula}', 'CarteirinhaController@showByMatricula')->name('carteirinha.show');
Route::get('/carteirinha/{matricula}/print', 'CarteirinhaController@printByMatricula')->name('carteirinha.print');

Auth::routes();

// Rotas para associados logados (movidas para o grupo principal)

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
            Route::get('/detalhes/{id}', 'Admin\AssociadoController@detalhes')->name('admin.associados.detalhes');
            Route::post('/update-status', 'Admin\AssociadoController@updateStatus')->name('admin.associados.update-status');
            Route::post('/reset-password', 'Admin\AssociadoController@resetPassword')->name('admin.associados.reset-password');
            Route::post('/desativar', 'Admin\AssociadoController@desativar')->name('admin.associados.desativar');
            
            // Rotas para associados pendentes
            Route::get('/pendentes', 'Admin\AssociadoController@pendentes')->name('admin.associados.pendentes');
            Route::get('/pendentes/data', 'Admin\AssociadoController@pendentesData')->name('admin.associados.pendentes.data');
            Route::post('/aprovar', 'Admin\AssociadoController@aprovar')->name('admin.associados.aprovar');
            Route::post('/rejeitar', 'Admin\AssociadoController@rejeitar')->name('admin.associados.rejeitar');
            
            // Rotas para relatórios
            Route::get('/relatorios', 'Admin\AssociadoController@relatorios')->name('admin.associados.relatorios');
            Route::post('/relatorios/buscar', 'Admin\AssociadoController@buscarRelatorios')->name('admin.associados.relatorios.buscar');
            Route::post('/relatorios/exportar-excel', 'Admin\AssociadoController@exportarExcel')->name('admin.associados.relatorios.excel');
            Route::post('/relatorios/exportar-pdf', 'Admin\AssociadoController@exportarPdf')->name('admin.associados.relatorios.pdf');
        });

        // Rotas do Sistema Financeiro
        Route::prefix('financeiro')->group(function() {
            Route::get('/', 'Admin\FinanceiroController@index')->name('admin.financeiro.index');
            Route::get('/pagamentos', 'Admin\FinanceiroController@pagamentos')->name('admin.financeiro.pagamentos');
            Route::get('/faturas', 'Admin\FinanceiroController@faturas')->name('admin.financeiro.faturas');
            Route::get('/relatorio', 'Admin\FinanceiroController@relatorio')->name('admin.financeiro.relatorio');
            Route::get('/dados-graficos', 'Admin\FinanceiroController@dadosGraficos')->name('admin.financeiro.dados-graficos');
        });
        
        // Rotas para Fluxo de Caixa
        Route::prefix('fluxo-caixa')->group(function() {
            Route::get('/contas-pagar', 'Admin\FluxoCaixaController@contasPagar')->name('admin.fluxo-caixa.contas-pagar');
            Route::get('/contas-receber', 'Admin\FluxoCaixaController@contasReceber')->name('admin.fluxo-caixa.contas-receber');
        });
        
        // Rotas para gerenciamento de solicitações
        Route::prefix('solicitacoes')->group(function() {
            Route::get('/', 'AdminSolicitacaoController@index')->name('admin.solicitacoes.index');
            Route::get('/dashboard', 'AdminSolicitacaoController@dashboard')->name('admin.solicitacoes.dashboard');
            Route::get('/{id}', 'AdminSolicitacaoController@show')->name('admin.solicitacoes.show');
            Route::post('/{id}/update-status', 'AdminSolicitacaoController@updateStatus')->name('admin.solicitacoes.update-status');
            Route::post('/{id}/assign-admin', 'AdminSolicitacaoController@assignAdmin')->name('admin.solicitacoes.assign-admin');
        });
    });
});

// Rotas para associados autenticados
Route::prefix('associado')->middleware(['auth'])->group(function() {
    // Dashboard e perfil
    Route::get('/dashboard', 'AssociadoController@dashboard')->name('associado.dashboard');
    Route::get('/profile', 'AssociadoController@profile')->name('associado.profile');
    Route::put('/profile', 'AssociadoController@updateProfile')->name('associado.profile.update');
    
    // Mensalidades e pagamentos
    Route::get('/minhas-mensalidades', 'AssociadoPagamentoController@index')->name('associado.pagamentos');
    Route::get('/historico-pagamentos', 'AssociadoPagamentoController@historico')->name('associado.historico-pagamentos');
    Route::get('/fatura', 'AssociadoPagamentoController@show')->name('associado.fatura');
    Route::get('/pagamento', 'AssociadoPagamentoController@pagamento')->name('associado.pagamento');
    
    // Rotas para faturas
    Route::get('/pagar-fatura/{id}', 'AssociadoPagamentoController@pagarFatura')->name('associado.pagar-fatura');
    Route::get('/ver-fatura/{id}', 'AssociadoPagamentoController@verFatura')->name('associado.ver-fatura');
    
    // Rotas para atualização de faturas
    Route::post('/atualizar-fatura', 'AssociadoPagamentoController@atualizar')->name('associado.atualizar-fatura');
    Route::get('/atualizar-fatura/{id}', 'AssociadoPagamentoController@atualizarFatura')->name('associado.atualizar-fatura-direta');
    
    // Rotas para QR Code PIX
    Route::post('/buscar-qr-code-pix', 'AssociadoPagamentoController@buscarQrCodePix')->name('associado.buscar-qr-code-pix');
    
    // Rotas para pagamentos
    Route::post('/verificar-pagamento', 'AssociadoPagamentoController@verificarPagamento')->name('associado.verificar-pagamento');
    Route::get('/primeira-fatura-atraso', 'AssociadoPagamentoController@primeiraFaturaAtraso')->name('associado.primeira-fatura-atraso');
    
    // Rotas para cancelamento
    Route::post('/cancelar-assinatura', 'AssociadoPagamentoController@cancelarAssinatura')->name('associado.cancelar');
    Route::get('/cancelar-assinatura', 'AssociadoPagamentoController@cancelarAssinaturaView')->name('associado.cancelar-view');
    
    // Rotas para solicitações
    Route::get('/solicitacoes', 'AssociadoSolicitacaoController@index')->name('associado.solicitacoes.index');
    Route::get('/solicitacoes/nova', 'AssociadoSolicitacaoController@create')->name('associado.solicitacoes.create');
    Route::post('/solicitacoes', 'AssociadoSolicitacaoController@store')->name('associado.solicitacoes.store');
    Route::get('/solicitacoes/{id}', 'AssociadoSolicitacaoController@show')->name('associado.solicitacoes.show');
    Route::post('/solicitacoes/{id}/cancelar', 'AssociadoSolicitacaoController@cancel')->name('associado.solicitacoes.cancel');
    
    // Rotas para perfil
    Route::get('/perfil', 'AssociadoProfileController@index')->name('associado.perfil');
    Route::post('/perfil/foto', 'AssociadoProfileController@updatePhoto')->name('associado.perfil.foto');
    Route::delete('/perfil/foto', 'AssociadoProfileController@removePhoto')->name('associado.perfil.foto.remove');
});

// Rotas públicas para carteirinha (acesso sem autenticação)
Route::get('/associado/carteirinha/{matricula}', 'CarteirinhaController@showByMatricula')->name('associado.carteirinha');
Route::get('/associado/carteirinha/{matricula}/print', 'CarteirinhaController@printByMatricula')->name('associado.carteirinha.print');

// Rotas para webhooks (sem middleware de autenticação)
Route::post('/webhook/asaas', 'WebhookController@asaas')->name('webhook.asaas');
Route::get('/webhook/test', 'WebhookController@test')->name('webhook.test');
Route::post('/webhook/simulate-pix', 'WebhookController@simulatePixPayment')->name('webhook.simulate-pix');

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

// Rota para testar criação de primeira cobrança (apenas para desenvolvimento)
Route::get('/test-create-first-payment/{user_id}', function($user_id) {
    try {
        $user = App\User::find($user_id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Usuário não encontrado']);
        }

        $asaasService = new App\Services\AsaasService();
        
        // Primeiro criar cliente
        $customerData = $asaasService->createCustomer($user);
        $asaasCustomerId = $customerData['id'];
        
        // Criar primeira cobrança diretamente
        $reflection = new ReflectionClass($asaasService);
        $method = $reflection->getMethod('createFirstPayment');
        $method->setAccessible(true);
        
        $firstPaymentData = $method->invoke($asaasService, $user, $asaasCustomerId, $user->getMonthlyValue());
        
        return response()->json([
            'success' => true,
            'customer' => $customerData,
            'first_payment' => $firstPaymentData
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
})->name('test.create.first.payment');

// Rota para testar pagamento (apenas para desenvolvimento)
Route::get('/test-pagamento/{invoice_id}', function($invoice_id) {
    try {
        $invoice = App\Invoice::find($invoice_id);
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Fatura não encontrada']);
        }

        return response()->json([
            'success' => true,
            'invoice' => [
                'id' => $invoice->id,
                'value' => $invoice->value,
                'status' => $invoice->status,
                'due_date' => $invoice->due_date,
                'pix_qr_code' => $invoice->pix_qr_code ? 'Disponível' : 'Não disponível',
                'pix_copy_paste' => $invoice->pix_copy_paste ? 'Disponível' : 'Não disponível'
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
})->name('test.pagamento');

// Rota para testar faturas (apenas para desenvolvimento)
Route::get('/test-faturas', function() {
    $faturas = \App\Invoice::all();
    echo '<h2>Faturas no Banco de Dados</h2>';
    echo '<p>Total: ' . $faturas->count() . '</p>';
    
    foreach($faturas as $fatura) {
        echo '<div style="border: 1px solid #ccc; margin: 10px; padding: 10px;">';
        echo '<strong>ID:</strong> ' . $fatura->id . '<br>';
        echo '<strong>Asaas Payment ID:</strong> ' . $fatura->asaas_payment_id . '<br>';
        echo '<strong>Status:</strong> ' . $fatura->status . '<br>';
        echo '<strong>Valor:</strong> ' . $fatura->formatted_value . '<br>';
        echo '<strong>QR Code:</strong> ' . ($fatura->pix_qr_code ? 'SIM' : 'NÃO') . '<br>';
        echo '<strong>PIX Copy:</strong> ' . ($fatura->pix_copy_paste ? 'SIM' : 'NÃO') . '<br>';
        echo '</div>';
    }
    
    return response('Verifique o código fonte da página');
})->name('test.faturas');

// Rota para testar busca de pagamento no Asaas (apenas para desenvolvimento)
Route::get('/test-asaas-payment/{payment_id}', function($payment_id) {
    try {
        $asaasService = new \App\Services\AsaasService();
        $paymentData = $asaasService->getPayment($payment_id);
        
        return response()->json([
            'success' => true,
            'payment_id' => $payment_id,
            'data' => $paymentData,
            'has_pix' => isset($paymentData['pixTransaction']),
            'pix_qr_code' => $paymentData['pixTransaction']['qrCode'] ?? null,
            'pix_copy_paste' => $paymentData['pixTransaction']['payload'] ?? null
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'payment_id' => $payment_id,
            'error' => $e->getMessage()
        ]);
    }
})->name('test.asaas.payment');

// Rota para atualizar fatura existente com dados do PIX (apenas para desenvolvimento)
Route::get('/fix-invoice-pix/{invoice_id}', function($invoice_id) {
    try {
        $invoice = \App\Invoice::findOrFail($invoice_id);
        $asaasService = new \App\Services\AsaasService();
        
        Log::info('Tentando atualizar fatura com dados do PIX', [
            'invoice_id' => $invoice->id,
            'asaas_payment_id' => $invoice->asaas_payment_id
        ]);
        
        $paymentData = $asaasService->getPayment($invoice->asaas_payment_id);
        
        if (isset($paymentData['pixTransaction'])) {
            $invoice->update([
                'pix_qr_code' => $paymentData['pixTransaction']['qrCode'] ?? null,
                'pix_copy_paste' => $paymentData['pixTransaction']['payload'] ?? null,
                'invoice_url' => $paymentData['invoiceUrl'] ?? null,
                'asaas_data' => $paymentData
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Fatura atualizada com dados do PIX',
                'invoice_id' => $invoice->id,
                'has_qr_code' => !empty($paymentData['pixTransaction']['qrCode']),
                'has_pix_copy' => !empty($paymentData['pixTransaction']['payload'])
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Dados do PIX não disponíveis no Asaas',
                'payment_data' => $paymentData
            ]);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
})->name('fix.invoice.pix');

// Rota para testar configuração da API do Asaas (apenas para desenvolvimento)
Route::get('/test-asaas-config', function() {
    try {
        $asaasService = new \App\Services\AsaasService();
        
        // Testar conexão básica
        $testResult = $asaasService->testConnection();
        
        return response()->json([
            'success' => true,
            'message' => 'Conexão com Asaas funcionando',
            'test_result' => $testResult,
            'base_url' => config('app.asaas_base_url', 'https://sandbox.asaas.com/api/v3'),
            'api_key_preview' => substr(config('app.asaas_api_key', ''), 0, 20) . '...'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'base_url' => config('app.asaas_base_url', 'https://sandbox.asaas.com/api/v3'),
            'api_key_preview' => substr(config('app.asaas_api_key', ''), 0, 20) . '...'
        ]);
    }
})->name('test.asaas.config');

// Rota para tentar novamente buscar dados do PIX (apenas para desenvolvimento)
Route::get('/retry-pix/{invoice_id}', function($invoice_id) {
    try {
        $invoice = \App\Invoice::findOrFail($invoice_id);
        $asaasService = new \App\Services\AsaasService();
        
        Log::info('Tentando novamente buscar dados do PIX', [
            'invoice_id' => $invoice->id,
            'asaas_payment_id' => $invoice->asaas_payment_id
        ]);
        
        // Aguardar um pouco mais
        sleep(3);
        
        $paymentData = $asaasService->getPayment($invoice->asaas_payment_id);
        
        if (isset($paymentData['pixTransaction']) && $paymentData['pixTransaction'] !== null) {
            $invoice->update([
                'pix_qr_code' => $paymentData['pixTransaction']['qrCode'] ?? null,
                'pix_copy_paste' => $paymentData['pixTransaction']['payload'] ?? null,
                'invoice_url' => $paymentData['invoiceUrl'] ?? null,
                'asaas_data' => $paymentData
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Dados do PIX encontrados e atualizados!',
                'invoice_id' => $invoice->id,
                'has_qr_code' => !empty($paymentData['pixTransaction']['qrCode']),
                'has_pix_copy' => !empty($paymentData['pixTransaction']['payload']),
                'pix_data' => $paymentData['pixTransaction']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Dados do PIX ainda não disponíveis no Asaas. Tente novamente em alguns minutos.',
                'payment_status' => $paymentData['status'] ?? 'unknown',
                'pix_transaction' => $paymentData['pixTransaction']
            ]);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
})->name('retry.pix');

// Rota para verificar status de pagamento manualmente (apenas para desenvolvimento)
Route::get('/check-payment-status/{invoice_id}', function($invoice_id) {
    try {
        $invoice = \App\Invoice::findOrFail($invoice_id);
        $asaasService = new \App\Services\AsaasService();
        
        Log::info('Verificando status do pagamento manualmente', [
            'invoice_id' => $invoice->id,
            'asaas_payment_id' => $invoice->asaas_payment_id
        ]);
        
        $paymentData = $asaasService->getPayment($invoice->asaas_payment_id);
        
        // Verificar se o pagamento foi confirmado
        $pago = in_array($paymentData['status'], ['CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'RECEIVED_WITH_OVERDUE']);
        
        if ($pago) {
            // Atualizar fatura local
            $invoice->update([
                'status' => $paymentData['status'],
                'payment_date' => $paymentData['paymentDate'] ? \Carbon\Carbon::parse($paymentData['paymentDate']) : null,
                'pix_qr_code' => $paymentData['pixTransaction']['qrCode'] ?? null,
                'pix_copy_paste' => $paymentData['pixTransaction']['payload'] ?? null,
                'asaas_data' => $paymentData
            ]);
            
            // Criar registro de pagamento se necessário
            if (!\App\Payment::where('asaas_payment_id', $paymentData['id'])->exists()) {
                \App\Payment::create([
                    'invoice_id' => $invoice->id,
                    'user_id' => $invoice->user_id,
                    'asaas_payment_id' => $paymentData['id'],
                    'value' => $paymentData['value'],
                    'payment_date' => \Carbon\Carbon::parse($paymentData['paymentDate']),
                    'status' => $paymentData['status'],
                    'payment_method' => $paymentData['billingType'] ?? 'PIX',
                    'description' => $paymentData['description'] ?? null,
                    'asaas_data' => $paymentData
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Pagamento confirmado e atualizado!',
                'invoice_id' => $invoice->id,
                'status' => $paymentData['status'],
                'payment_date' => $paymentData['paymentDate']
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Pagamento ainda não foi confirmado',
                'status' => $paymentData['status'],
                'payment_data' => $paymentData
            ]);
        }
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
})->name('check.payment.status');

// Rota para forçar atualização do pagamento (apenas para desenvolvimento)
Route::get('/force-update-payment/{invoice_id}', function($invoice_id) {
    try {
        $invoice = \App\Invoice::findOrFail($invoice_id);
        $asaasService = new \App\Services\AsaasService();
        
        Log::info('Forçando atualização do pagamento', [
            'invoice_id' => $invoice->id,
            'asaas_payment_id' => $invoice->asaas_payment_id
        ]);
        
        $paymentData = $asaasService->getPayment($invoice->asaas_payment_id);
        
        // Forçar atualização independente do status
        $invoice->update([
            'status' => $paymentData['status'],
            'payment_date' => $paymentData['paymentDate'] ? \Carbon\Carbon::parse($paymentData['paymentDate']) : null,
            'pix_qr_code' => $paymentData['pixTransaction']['qrCode'] ?? null,
            'pix_copy_paste' => $paymentData['pixTransaction']['payload'] ?? null,
            'asaas_data' => $paymentData
        ]);
        
        // Criar registro de pagamento se necessário
        if (!\App\Payment::where('asaas_payment_id', $paymentData['id'])->exists()) {
            \App\Payment::create([
                'invoice_id' => $invoice->id,
                'user_id' => $invoice->user_id,
                'asaas_payment_id' => $paymentData['id'],
                'value' => $paymentData['value'],
                'payment_date' => \Carbon\Carbon::parse($paymentData['paymentDate']),
                'status' => $paymentData['status'],
                'payment_method' => $paymentData['billingType'] ?? 'PIX',
                'description' => $paymentData['description'] ?? null,
                'asaas_data' => $paymentData
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Pagamento atualizado com sucesso!',
            'invoice_id' => $invoice->id,
            'status' => $paymentData['status'],
            'payment_date' => $paymentData['paymentDate'],
            'updated_fields' => [
                'status' => $paymentData['status'],
                'payment_date' => $paymentData['paymentDate'],
                'has_pix_data' => !empty($paymentData['pixTransaction'])
            ]
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
})->name('force.update.payment');

// Rotas administrativas para configurações
Route::prefix('admin/configuracoes')->middleware(['auth:admin'])->group(function() {
    Route::get('/', 'Admin\ConfiguracaoController@index')->name('admin.configuracoes.index');
    Route::put('/{id}', 'Admin\ConfiguracaoController@update')->name('admin.configuracoes.update');
    Route::post('/{id}/toggle', 'Admin\ConfiguracaoController@toggle')->name('admin.configuracoes.toggle');
    Route::post('/inicializar', 'Admin\ConfiguracaoController@inicializar')->name('admin.configuracoes.inicializar');
});

// Rotas administrativas para parcerias
Route::prefix('admin/parcerias')->middleware(['auth:admin'])->group(function() {
    Route::get('/', 'Admin\ParceriaController@index')->name('admin.parcerias.index');
    Route::get('/create', 'Admin\ParceriaController@create')->name('admin.parcerias.create');
    Route::post('/store', 'Admin\ParceriaController@store')->name('admin.parcerias.store');
    Route::get('/{id}', 'Admin\ParceriaController@show')->name('admin.parcerias.show');
    Route::get('/{id}/edit', 'Admin\ParceriaController@edit')->name('admin.parcerias.edit');
    Route::put('/{id}', 'Admin\ParceriaController@update')->name('admin.parcerias.update');
    Route::delete('/{id}', 'Admin\ParceriaController@destroy')->name('admin.parcerias.destroy');
    Route::post('/{id}/toggle-status', 'Admin\ParceriaController@toggleStatus')->name('admin.parcerias.toggle-status');
    Route::post('/{id}/toggle-destaque', 'Admin\ParceriaController@toggleDestaque')->name('admin.parcerias.toggle-destaque');
});

// Rota de teste temporária (sem middleware)
Route::post('/test-parceria', 'Admin\ParceriaController@store')->name('test.parceria');

// Rotas públicas para associados verem parcerias
Route::prefix('associado/parcerias')->middleware(['auth'])->group(function() {
    Route::get('/', 'ParceriaController@index')->name('parcerias.index');
    Route::get('/{id}', 'ParceriaController@show')->name('parcerias.show');
    Route::get('/categoria/{categoria}', 'ParceriaController@categoria')->name('parcerias.categoria');
});

// Rotas administrativas para eventos
Route::prefix('admin/eventos')->middleware(['auth:admin'])->group(function() {
    Route::get('/', 'Admin\EventoController@index')->name('admin.eventos.index');
    Route::get('/create', 'Admin\EventoController@create')->name('admin.eventos.create');
    Route::post('/store', 'Admin\EventoController@store')->name('admin.eventos.store');
    Route::get('/{id}', 'Admin\EventoController@show')->name('admin.eventos.show');
    Route::get('/{id}/edit', 'Admin\EventoController@edit')->name('admin.eventos.edit');
    Route::put('/{id}', 'Admin\EventoController@update')->name('admin.eventos.update');
    Route::delete('/{id}', 'Admin\EventoController@destroy')->name('admin.eventos.destroy');
    
    // Funcionalidades específicas
    Route::post('/{id}/gerar-link', 'Admin\EventoController@gerarLinkPresenca')->name('admin.eventos.gerar-link');
    Route::post('/{id}/toggle-lista', 'Admin\EventoController@toggleListaPresenca')->name('admin.eventos.toggle-lista');
    Route::get('/{id}/presencas', 'Admin\EventoController@presencas')->name('admin.eventos.presencas');
    Route::get('/{id}/exportar-presencas', 'Admin\EventoController@exportarPresencas')->name('admin.eventos.exportar-presencas');
});

// Rotas públicas para lista de presença
Route::prefix('eventos')->group(function() {
    Route::get('/presenca/{link}', 'EventoPresencaController@show')->name('evento.presenca');
    Route::post('/presenca/{link}', 'EventoPresencaController@store')->name('evento.presenca.store');
    Route::post('/buscar-usuario', 'EventoPresencaController@buscarUsuario')->name('evento.buscar-usuario');
    Route::post('/verificar-duplicacao', 'EventoPresencaController@verificarDuplicacao')->name('evento.verificar-duplicacao');
});

