<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Detalhes do Associado</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Informações Pessoais</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Nome:</td>
                                <td>{{ $associado->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Email:</td>
                                <td>{{ $associado->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">CPF:</td>
                                <td>{{ $associado->cpf ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Data de Nascimento:</td>
                                <td>{{ $associado->data_nascimento ? $associado->data_nascimento->format('d/m/Y') : 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Telefone:</td>
                                <td>{{ $associado->telefone ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Tipo de Associado:</td>
                                <td>
                                    @php
                                        $tipos = [
                                            'morador' => 'Morador',
                                            'comerciante' => 'Comerciante',
                                            'ambos' => 'Morador e Comerciante'
                                        ];
                                    @endphp
                                    {{ $tipos[$associado->tipo_associado] ?? 'Não definido' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Status:</td>
                                <td>
                                    @php
                                        $status = $associado->status ?? 'pendente';
                                        $badges = [
                                            'aprovado' => 'success',
                                            'ativo' => 'success',
                                            'inativo' => 'danger',
                                            'pendente' => 'warning',
                                            'suspenso' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$status] ?? 'warning' }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Endereço</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">CEP:</td>
                                <td>{{ $associado->cep ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Logradouro:</td>
                                <td>{{ $associado->logradouro ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Número:</td>
                                <td>{{ $associado->numero ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Complemento:</td>
                                <td>{{ $associado->complemento ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Bairro:</td>
                                <td>{{ $associado->bairro ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Cidade:</td>
                                <td>{{ $associado->cidade ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">UF:</td>
                                <td>{{ $associado->uf ?? 'Não informado' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if(in_array($associado->tipo_associado, ['comerciante', 'ambos']))
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Informações do Comércio</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Nome do Comércio:</td>
                                <td>{{ $associado->nome_comercio ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Endereço do Comércio:</td>
                                <td>{{ $associado->endereco_comercio ?? 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Ramo de Atividade:</td>
                                <td>{{ $associado->ramo_atividade ?? 'Não informado' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-muted mb-3">Informações do Sistema</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-semibold">Data de Cadastro:</td>
                                <td>{{ $associado->created_at ? $associado->created_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : 'Não informado' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Última Atualização:</td>
                                <td>{{ $associado->updated_at ? $associado->updated_at->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') : 'Não informado' }}</td>
                            </tr>
                            @if($associado->data_aprovacao)
                            <tr>
                                <td class="fw-semibold">Data de Aprovação:</td>
                                <td>{{ $associado->data_aprovacao->setTimezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
