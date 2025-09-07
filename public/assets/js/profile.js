/*
 * Profile Form Validation and Interaction Script
 * Específico para o formulário de perfil do associado
 */

document.addEventListener('DOMContentLoaded', function() {
    // Verificar se estamos na página de perfil
    const profileForm = document.querySelector('form[action*="profile.update"]');
    
    if (!profileForm) {
        console.log('Profile.js: Formulário de perfil não encontrado. Script não será executado.');
        return;
    }
    
    console.log('Profile.js: Inicializando validações do formulário de perfil...');
    
    // Elementos específicos do formulário de perfil
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const telefoneInput = document.getElementById('telefone');
    const cepInput = document.getElementById('cep');
    const logradouroInput = document.getElementById('logradouro');
    const numeroInput = document.getElementById('numero');
    const bairroInput = document.getElementById('bairro');
    const cidadeInput = document.getElementById('cidade');
    const ufInput = document.getElementById('uf');
    const complementoInput = document.getElementById('complemento');
    
    // Campos específicos para comerciantes
    const nomeComercioInput = document.getElementById('nome_comercio');
    const enderecoComercioInput = document.getElementById('endereco_comercio');
    const ramoAtividadeInput = document.getElementById('ramo_atividade');
    
    // Aplicar máscaras usando jQuery Mask (já incluído no layout)
    if (telefoneInput) {
        $(telefoneInput).mask('(00) 00000-0000');
    }
    
    if (cepInput) {
        $(cepInput).mask('00000-000');
    }
    
    // Função para validar campos individuais
    function validarCampo(input, isValid) {
        if (!input) return;
        
        input.classList.remove('is-valid', 'is-invalid');
        if (isValid) {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
        }
    }
    
    // Função para validar email
    function validarEmail(email) {
        if (!email || email.trim().length === 0) {
            return false;
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email.trim());
    }
    
    // Busca de endereço por CEP
    if (cepInput) {
        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            
            if (cep.length === 8) {
                console.log('Profile.js: Buscando CEP:', cep);
                
                // Adicionar indicador de carregamento
                cepInput.classList.add('is-loading');
                
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            if (logradouroInput) logradouroInput.value = data.logradouro || '';
                            if (bairroInput) bairroInput.value = data.bairro || '';
                            if (cidadeInput) cidadeInput.value = data.localidade || '';
                            if (ufInput) ufInput.value = data.uf || '';
                            
                            // Validar campos preenchidos
                            validarCampo(logradouroInput, logradouroInput.value.length > 0);
                            validarCampo(bairroInput, bairroInput.value.length > 0);
                            validarCampo(cidadeInput, cidadeInput.value.length > 0);
                            validarCampo(ufInput, ufInput.value !== '');
                            
                            validarCampo(cepInput, true);
                            
                            console.log('Profile.js: CEP encontrado e campos preenchidos');
                        } else {
                            validarCampo(cepInput, false);
                            console.log('Profile.js: CEP não encontrado');
                        }
                    })
                    .catch(error => {
                        console.error('Profile.js: Erro ao buscar CEP:', error);
                        validarCampo(cepInput, false);
                    })
                    .finally(() => {
                        cepInput.classList.remove('is-loading');
                    });
            } else {
                validarCampo(cepInput, false);
            }
        });
    }
    
    // Validação em tempo real para campos obrigatórios
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            validarCampo(this, this.value.trim().length > 0);
        });
    }
    
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function() {
            const telefoneValido = this.value.replace(/\D/g, '').length >= 10;
            validarCampo(this, telefoneValido);
        });
    }
    
    if (logradouroInput) {
        logradouroInput.addEventListener('input', function() {
            validarCampo(this, this.value.trim().length > 0);
        });
    }
    
    if (numeroInput) {
        numeroInput.addEventListener('input', function() {
            validarCampo(this, this.value.trim().length > 0);
        });
    }
    
    if (bairroInput) {
        bairroInput.addEventListener('input', function() {
            validarCampo(this, this.value.trim().length > 0);
        });
    }
    
    if (cidadeInput) {
        cidadeInput.addEventListener('input', function() {
            validarCampo(this, this.value.trim().length > 0);
        });
    }
    
    // Validação para campos de comércio (se existirem)
    if (nomeComercioInput) {
        nomeComercioInput.addEventListener('input', function() {
            validarCampo(this, this.value.trim().length > 0);
        });
    }
    
    if (enderecoComercioInput) {
        enderecoComercioInput.addEventListener('input', function() {
            validarCampo(this, this.value.trim().length > 0);
        });
    }
    
    if (ramoAtividadeInput) {
        ramoAtividadeInput.addEventListener('change', function() {
            validarCampo(this, this.value !== '');
        });
    }
    
    // Validação do formulário antes do envio
    profileForm.addEventListener('submit', function(e) {
        let formValido = true;
        const camposObrigatorios = [];
        
        // Verificar campos obrigatórios
        if (nameInput && nameInput.value.trim().length === 0) {
            validarCampo(nameInput, false);
            camposObrigatorios.push('Nome');
            formValido = false;
        }
        
        if (telefoneInput && telefoneInput.value.replace(/\D/g, '').length < 10) {
            validarCampo(telefoneInput, false);
            camposObrigatorios.push('Telefone');
            formValido = false;
        }
        
        if (cepInput && cepInput.value.replace(/\D/g, '').length !== 8) {
            validarCampo(cepInput, false);
            camposObrigatorios.push('CEP');
            formValido = false;
        }
        
        if (logradouroInput && logradouroInput.value.trim().length === 0) {
            validarCampo(logradouroInput, false);
            camposObrigatorios.push('Logradouro');
            formValido = false;
        }
        
        if (numeroInput && numeroInput.value.trim().length === 0) {
            validarCampo(numeroInput, false);
            camposObrigatorios.push('Número');
            formValido = false;
        }
        
        if (bairroInput && bairroInput.value.trim().length === 0) {
            validarCampo(bairroInput, false);
            camposObrigatorios.push('Bairro');
            formValido = false;
        }
        
        if (cidadeInput && cidadeInput.value.trim().length === 0) {
            validarCampo(cidadeInput, false);
            camposObrigatorios.push('Cidade');
            formValido = false;
        }
        
        if (ufInput && ufInput.value === '') {
            validarCampo(ufInput, false);
            camposObrigatorios.push('Estado');
            formValido = false;
        }
        
        // Verificar campos de comércio se necessário
        if (nomeComercioInput && nomeComercioInput.required && nomeComercioInput.value.trim().length === 0) {
            validarCampo(nomeComercioInput, false);
            camposObrigatorios.push('Nome do Comércio');
            formValido = false;
        }
        
        if (enderecoComercioInput && enderecoComercioInput.required && enderecoComercioInput.value.trim().length === 0) {
            validarCampo(enderecoComercioInput, false);
            camposObrigatorios.push('Endereço do Comércio');
            formValido = false;
        }
        
        if (ramoAtividadeInput && ramoAtividadeInput.required && ramoAtividadeInput.value === '') {
            validarCampo(ramoAtividadeInput, false);
            camposObrigatorios.push('Ramo de Atividade');
            formValido = false;
        }
        
        if (!formValido) {
            e.preventDefault();
            console.log('Profile.js: Formulário inválido. Campos faltando:', camposObrigatorios);
            alert('Por favor, preencha todos os campos obrigatórios corretamente.');
        } else {
            console.log('Profile.js: Formulário válido. Enviando...');
        }
    });
    
    console.log('Profile.js: Script inicializado com sucesso!');
});
