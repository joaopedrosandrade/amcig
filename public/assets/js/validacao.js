document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formAssociado');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Função para limpar mensagens de erro
    function limparErros() {
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
            el.classList.remove('show');
        });
        document.querySelectorAll('.form-control, .form-select').forEach(el => {
            el.classList.remove('is-invalid');
        });
    }
    
    // Função para mostrar erro em um campo específico
    function mostrarErro(campo, mensagem) {
        const input = document.getElementById(campo);
        const errorDiv = document.getElementById(campo + '-error');
        
        if (input && errorDiv) {
            input.classList.add('is-invalid');
            errorDiv.textContent = mensagem;
            errorDiv.classList.add('show');
        }
    }
    
    // Toggle para mostrar/ocultar campos do comércio
    const tipoAssociado = document.getElementById('tipoAssociado');
    const camposComercio = document.getElementById('camposComercio');
    
    tipoAssociado.addEventListener('change', function() {
        if (this.value === 'comerciante' || this.value === 'ambos') {
            camposComercio.classList.remove('d-none');
            // Torna os campos obrigatórios
            document.getElementById('nomeComercio').required = true;
            document.getElementById('enderecoComercio').required = true;
            document.getElementById('ramoAtividade').required = true;
        } else {
            camposComercio.classList.add('d-none');
            // Remove a obrigatoriedade
            document.getElementById('nomeComercio').required = false;
            document.getElementById('enderecoComercio').required = false;
            document.getElementById('ramoAtividade').required = false;
        }
    });
    
    // Máscaras para CPF e telefone
    if (typeof IMask !== 'undefined') {
        // Máscara para CPF
        IMask(document.getElementById('cpf'), {
            mask: '000.000.000-00'
        });
        
        // Máscara para telefone
        IMask(document.getElementById('telefone'), {
            mask: '(00) 00000-0000'
        });
        
        // Máscara para CEP
        IMask(document.getElementById('cep'), {
            mask: '00000-000'
        });
    }
    
    // Envio do formulário
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Limpa erros anteriores
        limparErros();
        
        // Desabilita o botão para evitar múltiplos envios
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Enviando...';
        
        // Coleta os dados do formulário
        const formData = new FormData(form);
        
        // Envia via AJAX
        const actionUrl = form.getAttribute('data-action');
        fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Sucesso
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: data.message || 'Cadastro realizado com sucesso!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redireciona para a página de sucesso
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '/associado/success';
                    }
                });
            } else {
                // Erro de validação
                if (data.errors) {
                    // Mostra erros nos campos específicos
                    Object.keys(data.errors).forEach(field => {
                        const mensagem = data.errors[field][0];
                        
                        // Mapeia campos especiais
                        let campoId = field;
                        if (field === 'nome_comercio') campoId = 'nomeComercio';
                        if (field === 'endereco_comercio') campoId = 'enderecoComercio';
                        if (field === 'ramo_atividade') campoId = 'ramoAtividade';
                        if (field === 'data_nascimento') campoId = 'dataNascimento';
                        if (field === 'senha_confirmation') campoId = 'confirmarSenha';
                        if (field === 'aceiteTermos') campoId = 'aceiteTermos';
                        
                        mostrarErro(campoId, mensagem);
                    });
                    
                    // Mostra mensagem geral de erro
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro de Validação',
                        text: 'Por favor, corrija os erros indicados nos campos.',
                        confirmButtonText: 'OK'
                    });
                } else {
                    // Outro tipo de erro
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: data.message || 'Ocorreu um erro ao processar sua solicitação.',
                        confirmButtonText: 'OK'
                    });
                }
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: 'Ocorreu um erro inesperado. Tente novamente.',
                confirmButtonText: 'OK'
            });
        })
        .finally(() => {
            // Reabilita o botão
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Cadastrar como Associado';
        });
    });
    
    // Toggle para mostrar/ocultar senha
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const input = document.getElementById(target);
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            } else {
                input.type = 'password';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            }
        });
    });
});