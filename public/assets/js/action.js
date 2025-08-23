
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos do formulário
        const form = document.querySelector('form');
        const submitBtn = document.querySelector('button[type="submit"]');
        const cepInput = document.getElementById('cep');
        const cpfInput = document.getElementById('cpf');
        const telefoneInput = document.getElementById('telefone');
        const emailInput = document.getElementById('email');
        const nomeInput = document.getElementById('nome');
        const dataNascimentoInput = document.getElementById('dataNascimento');
        const tipoAssociadoInput = document.getElementById('tipoAssociado');
        const senhaInput = document.getElementById('senha');
        const confirmarSenhaInput = document.getElementById('confirmarSenha');
        const aceiteTermosInput = document.getElementById('aceiteTermos');
        
        // Campos do comércio
        const camposComercio = document.getElementById('camposComercio');
        const nomeComercioInput = document.getElementById('nomeComercio');
        const enderecoComercioInput = document.getElementById('enderecoComercio');
        const ramoAtividadeInput = document.getElementById('ramoAtividade');
        
        // Campos de endereço
        const logradouroInput = document.getElementById('logradouro');
        const bairroInput = document.getElementById('bairro');
        const cidadeInput = document.getElementById('cidade');
        const ufInput = document.getElementById('uf');
        
        // Elementos da barra de progresso
        const barraProgresso = document.getElementById('barraProgresso');
        const progressoTexto = document.getElementById('progressoTexto');
        const camposRestantes = document.getElementById('camposRestantes');
        
        // Aplicar máscaras com IMask
        const cpfMask = IMask(cpfInput, {
            mask: '000.000.000-00'
        });
        
        const telefoneMask = IMask(telefoneInput, {
            mask: '(00) 00000-0000'
        });
        
        const cepMask = IMask(cepInput, {
            mask: '00000-000'
        });
        
        // Função para validar CPF
        function validarCPF(cpf) {
            cpf = cpf.replace(/\D/g, '');
            if (cpf.length !== 11) return false;
            
            // Verifica se todos os dígitos são iguais
            if (/^(\d)\1+$/.test(cpf)) return false;
            
            // Validação do primeiro dígito verificador
            let soma = 0;
            for (let i = 0; i < 9; i++) {
                soma += parseInt(cpf.charAt(i)) * (10 - i);
            }
            let resto = 11 - (soma % 11);
            let dv1 = resto < 2 ? 0 : resto;
            
            // Validação do segundo dígito verificador
            soma = 0;
            for (let i = 0; i < 10; i++) {
                soma += parseInt(cpf.charAt(i)) * (11 - i);
            }
            resto = 11 - (soma % 11);
            let dv2 = resto < 2 ? 0 : resto;
            
            return parseInt(cpf.charAt(9)) === dv1 && parseInt(cpf.charAt(10)) === dv2;
        }
        
        // Função para validar email
        function validarEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
        
        // Função para validar senha
        function validarSenha(senha) {
            return senha.length >= 6;
        }
        
        // Função para validar data de nascimento
        function validarDataNascimento(data) {
            const hoje = new Date();
            const dataNasc = new Date(data);
            const idade = hoje.getFullYear() - dataNasc.getFullYear();
            const mes = hoje.getMonth() - dataNasc.getMonth();
            
            if (mes < 0 || (mes === 0 && hoje.getDate() < dataNasc.getDate())) {
                idade--;
            }
            
            return idade >= 18 && idade <= 120;
        }
        
        // Função para validar UF
        function validarUF(uf) {
            const ufs = ['AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'];
            return ufs.includes(uf.toUpperCase());
        }
        
        // Função para controlar exibição dos campos do comércio
        function controlarCamposComercio() {
            const tipoSelecionado = tipoAssociadoInput.value;
            const precisaComercio = tipoSelecionado === 'comerciante' || tipoSelecionado === 'ambos';
            
            if (precisaComercio) {
                camposComercio.classList.remove('d-none');
                // Adicionar validação obrigatória aos campos
                nomeComercioInput.required = true;
                enderecoComercioInput.required = true;
                ramoAtividadeInput.required = true;
            } else {
                camposComercio.classList.add('d-none');
                // Remover validação obrigatória e limpar campos
                nomeComercioInput.required = false;
                enderecoComercioInput.required = false;
                ramoAtividadeInput.required = false;
                nomeComercioInput.value = '';
                enderecoComercioInput.value = '';
                ramoAtividadeInput.value = '';
                // Remover classes de validação
                nomeComercioInput.classList.remove('is-valid', 'is-invalid');
                enderecoComercioInput.classList.remove('is-valid', 'is-invalid');
                ramoAtividadeInput.classList.remove('is-valid', 'is-invalid');
            }
            
            // Atualizar barra de progresso após mudança de tipo
            atualizarBarraProgresso();
        }
        
        // Função para atualizar a barra de progresso
        function atualizarBarraProgresso() {
            const tipoSelecionado = tipoAssociadoInput.value;
            const precisaComercio = tipoSelecionado === 'comerciante' || tipoSelecionado === 'ambos';
            
            // Lista de todos os campos obrigatórios
            const todosCampos = [
                { campo: nomeInput, nome: 'Nome' },
                { campo: cpfInput, nome: 'CPF' },
                { campo: dataNascimentoInput, nome: 'Data de Nascimento' },
                { campo: telefoneInput, nome: 'Telefone' },
                { campo: emailInput, nome: 'Email' },
                { campo: cepInput, nome: 'CEP' },
                { campo: logradouroInput, nome: 'Logradouro' },
                { campo: bairroInput, nome: 'Bairro' },
                { campo: tipoAssociadoInput, nome: 'Tipo de Associado' },
                { campo: senhaInput, nome: 'Senha' },
                { campo: confirmarSenhaInput, nome: 'Confirmar Senha' },
                { campo: aceiteTermosInput, nome: 'Aceite dos Termos' }
            ];
            
            // Adicionar campos do comércio se necessário
            if (precisaComercio) {
                todosCampos.push(
                    { campo: nomeComercioInput, nome: 'Nome do Comércio' },
                    { campo: enderecoComercioInput, nome: 'Endereço do Comércio' },
                    { campo: ramoAtividadeInput, nome: 'Ramo de Atividade' }
                );
            }
            
            // Contar campos preenchidos
            let camposPreenchidos = 0;
            let camposFaltando = [];
            
            todosCampos.forEach(({ campo, nome }) => {
                let valido = false;
                
                if (campo.type === 'checkbox') {
                    valido = campo.checked;
                } else if (campo.type === 'select-one') {
                    valido = campo.value !== '';
                } else if (campo.id === 'cpf') {
                    valido = campo.value.replace(/\D/g, '').length === 11 && validarCPF(campo.value);
                } else if (campo.id === 'telefone') {
                    valido = campo.value.replace(/\D/g, '').length >= 10;
                } else if (campo.id === 'cep') {
                    valido = campo.value.replace(/\D/g, '').length === 8 && validarCepSaoMateus(campo.value);
                } else if (campo.id === 'confirmarSenha') {
                    valido = campo.value === senhaInput.value && campo.value.length > 0;
                } else if (campo.id === 'dataNascimento') {
                    valido = campo.value && validarDataNascimento(campo.value);
                } else if (campo.id === 'email') {
                    valido = campo.value && validarEmail(campo.value);
                } else {
                    valido = campo.value.trim().length > 0;
                }
                
                if (valido) {
                    camposPreenchidos++;
                } else {
                    camposFaltando.push(nome);
                }
            });
            
            // Calcular porcentagem
            const totalCampos = todosCampos.length;
            const porcentagem = Math.round((camposPreenchidos / totalCampos) * 100);
            
            // Atualizar barra de progresso
            barraProgresso.style.width = porcentagem + '%';
            barraProgresso.setAttribute('aria-valuenow', porcentagem);
            progressoTexto.textContent = porcentagem + '%';
            
                     // Atualizar cor da barra baseada na porcentagem
             barraProgresso.className = 'progress-bar';
             if (porcentagem === 100) {
                 barraProgresso.classList.add('bg-success');
                 camposRestantes.textContent = 'Seu cadastro está completo! Clique no botão para finalizar';
             } else if (porcentagem >= 70) {
                 barraProgresso.classList.add('bg-primary');
                 camposRestantes.textContent = 'Você está quase finalizando!';
             } else if (porcentagem >= 40) {
                 barraProgresso.classList.add('bg-primary');
                 camposRestantes.textContent = 'Falta pouco para concluir seu cadastro';
             } else {
                 barraProgresso.classList.add('bg-primary');
                 camposRestantes.textContent = 'Faça parte, seja um associado';
             }
        }
        
        // Função para validar se o CEP é de São Mateus-ES
        function validarCepSaoMateus(cep) {
            const cepLimpo = cep.replace(/\D/g, '');
            const cepNumero = parseInt(cepLimpo);
            
            // Range de CEPs de São Mateus-ES: 29940-000 a 29949-999
            return cepNumero >= 29940000 && cepNumero <= 29949999;
        }
        
        // Função para buscar CEP
        async function buscarCep(cep) {
            try {
                cep = cep.replace(/\D/g, '');
                
                if (cep.length !== 8) {
                    throw new Error('CEP deve ter 8 dígitos');
                }
                
                // Verificar se o CEP é de São Mateus-ES
                if (!validarCepSaoMateus(cep)) {
                    // Mostrar modal de CEP inválido
                    const modal = new bootstrap.Modal(document.getElementById('cepInvalidoModal'));
                    modal.show();
                    
                    // Limpar campos e aplicar validação inválida
                    cepInput.value = '';
                    logradouroInput.value = '';
                    bairroInput.value = '';
                    validarCampo(cepInput, false);
                    validarCampo(logradouroInput, false);
                    validarCampo(bairroInput, false);
                    
                    // Focar no campo CEP para correção
                    cepInput.focus();
                    return;
                }
                
                cepInput.classList.add('is-loading');
                
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                
                if (data.erro) {
                    throw new Error('CEP não encontrado');
                }
                
                // Preenche os campos automaticamente
                logradouroInput.value = data.logradouro || '';
                bairroInput.value = data.bairro || '';
                // cidadeInput.value = data.localidade || ''; // Campo fixo - não alterar
                // ufInput.value = data.uf || ''; // Campo fixo - não alterar
                
                // Valida os campos preenchidos
                validarCampo(logradouroInput, logradouroInput.value.length > 0);
                validarCampo(bairroInput, bairroInput.value.length > 0);
                // validarCampo(cidadeInput, cidadeInput.value.length > 0); // Campo fixo - sempre válido
                // validarCampo(ufInput, validarUF(ufInput.value)); // Campo fixo - sempre válido
                
                cepInput.classList.remove('is-invalid');
                cepInput.classList.add('is-valid');
                
                logradouroInput.focus();
                
            } catch (error) {
                console.error('Erro ao buscar CEP:', error);
                
                cepInput.classList.remove('is-valid');
                cepInput.classList.add('is-invalid');
                
                let errorDiv = cepInput.parentNode.querySelector('.cep-error');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback cep-error';
                    cepInput.parentNode.appendChild(errorDiv);
                }
                
                if (error.message === 'CEP não encontrado') {
                    errorDiv.textContent = 'CEP não encontrado. Verifique e tente novamente.';
                } else if (error.message === 'CEP deve ter 8 dígitos') {
                    errorDiv.textContent = 'CEP deve ter 8 dígitos.';
                } else {
                    errorDiv.textContent = 'Erro ao buscar CEP. Tente novamente.';
                }
                
            } finally {
                cepInput.classList.remove('is-loading');
            }
        }
        
        // Função para validar campo individual
        function validarCampo(input, isValid) {
            input.classList.remove('is-valid', 'is-invalid');
            if (isValid) {
                input.classList.add('is-valid');
            } else {
                input.classList.add('is-invalid');
            }
        }
        
        // Função para verificar se todo o formulário é válido
        function verificarFormularioValido() {
            const tipoSelecionado = tipoAssociadoInput.value;
            const precisaComercio = tipoSelecionado === 'comerciante' || tipoSelecionado === 'ambos';
            
            const camposObrigatorios = [
                nomeInput.value.trim().length > 0,
                cpfInput.value.replace(/\D/g, '').length === 11 && validarCPF(cpfInput.value),
                dataNascimentoInput.value && validarDataNascimento(dataNascimentoInput.value),
                telefoneInput.value.replace(/\D/g, '').length >= 10,
                emailInput.value && validarEmail(emailInput.value),
                cepInput.value.replace(/\D/g, '').length === 8 && validarCepSaoMateus(cepInput.value),
                logradouroInput.value.trim().length > 0,
                bairroInput.value.trim().length > 0,
                // cidadeInput.value.trim().length > 0, // Campo fixo - sempre válido
                // ufInput.value.trim().length === 2 && validarUF(ufInput.value), // Campo fixo - sempre válido
                tipoAssociadoInput.value !== '',
                senhaInput.value && validarSenha(senhaInput.value),
                confirmarSenhaInput.value === senhaInput.value,
                aceiteTermosInput.checked
            ];
            
            // Adicionar validação dos campos do comércio se necessário
            if (precisaComercio) {
                camposObrigatorios.push(
                    nomeComercioInput.value.trim().length > 0,
                    enderecoComercioInput.value.trim().length > 0,
                    ramoAtividadeInput.value !== ''
                );
            }
            
            const formularioValido = camposObrigatorios.every(campo => campo === true);
            submitBtn.disabled = !formularioValido;
            
            // Atualizar barra de progresso
            atualizarBarraProgresso();
            
            return formularioValido;
        }
        
        // Eventos de validação em tempo real
        nomeInput.addEventListener('input', () => {
            validarCampo(nomeInput, nomeInput.value.trim().length > 0);
            verificarFormularioValido();
        });
        
        cpfInput.addEventListener('input', () => {
            const cpfValido = cpfInput.value.replace(/\D/g, '').length === 11 && validarCPF(cpfInput.value);
            validarCampo(cpfInput, cpfValido);
            verificarFormularioValido();
        });
        
        dataNascimentoInput.addEventListener('change', () => {
            validarCampo(dataNascimentoInput, dataNascimentoInput.value && validarDataNascimento(dataNascimentoInput.value));
            verificarFormularioValido();
        });
        
        telefoneInput.addEventListener('input', () => {
            validarCampo(telefoneInput, telefoneInput.value.replace(/\D/g, '').length >= 10);
            verificarFormularioValido();
        });
        
        emailInput.addEventListener('input', () => {
            validarCampo(emailInput, emailInput.value && validarEmail(emailInput.value));
            verificarFormularioValido();
        });
        
        cepInput.addEventListener('blur', () => {
            const cep = cepInput.value;
            if (cep.replace(/\D/g, '').length === 8) {
                buscarCep(cep);
            }
            verificarFormularioValido();
        });
        
        logradouroInput.addEventListener('input', () => {
            validarCampo(logradouroInput, logradouroInput.value.trim().length > 0);
            verificarFormularioValido();
        });
        
        bairroInput.addEventListener('input', () => {
            validarCampo(bairroInput, bairroInput.value.trim().length > 0);
            verificarFormularioValido();
        });
        
        // Campos de cidade e UF são fixos - não precisam de validação
        // cidadeInput.addEventListener('input', () => {
        //     validarCampo(cidadeInput, cidadeInput.value.trim().length > 0);
        //     verificarFormularioValido();
        // });
        
        // ufInput.addEventListener('input', () => {
        //     validarCampo(ufInput, ufInput.value.trim().length === 2 && validarUF(ufInput.value));
        //     verificarFormularioValido();
        // });
        
        tipoAssociadoInput.addEventListener('change', () => {
            validarCampo(tipoAssociadoInput, tipoAssociadoInput.value !== '');
            controlarCamposComercio(); // Controla exibição dos campos do comércio
            verificarFormularioValido();
        });
        
        senhaInput.addEventListener('input', () => {
            validarCampo(senhaInput, senhaInput.value && validarSenha(senhaInput.value));
            verificarFormularioValido();
        });
        
        confirmarSenhaInput.addEventListener('input', () => {
            validarCampo(confirmarSenhaInput, confirmarSenhaInput.value === senhaInput.value);
            verificarFormularioValido();
        });
        
        aceiteTermosInput.addEventListener('change', () => {
            verificarFormularioValido();
        });
        
        // Event listeners para campos do comércio
        nomeComercioInput.addEventListener('input', () => {
            validarCampo(nomeComercioInput, nomeComercioInput.value.trim().length > 0);
            verificarFormularioValido();
        });
        
        enderecoComercioInput.addEventListener('input', () => {
            validarCampo(enderecoComercioInput, enderecoComercioInput.value.trim().length > 0);
            verificarFormularioValido();
        });
        
        ramoAtividadeInput.addEventListener('change', () => {
            validarCampo(ramoAtividadeInput, ramoAtividadeInput.value !== '');
            verificarFormularioValido();
        });
        
        // Inicializar botão como desabilitado
        submitBtn.disabled = true;
        
        // Inicializar barra de progresso
        atualizarBarraProgresso();
        
        // Prevenir envio se não estiver válido
        form.addEventListener('submit', (e) => {
            if (!verificarFormularioValido()) {
                e.preventDefault();
                alert('Por favor, preencha todos os campos obrigatórios corretamente.');
            }
        });
    });
