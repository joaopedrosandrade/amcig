<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Associados - AMCIG</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4472C4;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #4472C4;
            margin: 0;
            font-size: 24px;
        }
        
        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }
        
        .info-section {
            margin-bottom: 25px;
        }
        
        .info-section h3 {
            color: #4472C4;
            margin: 0 0 10px 0;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        
        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #4472C4;
        }
        
        .stat-label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        
        .filters-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .filters-list li {
            padding: 3px 0;
            border-bottom: 1px dotted #ccc;
        }
        
        .filters-list li:last-child {
            border-bottom: none;
        }
        
        .filters-list strong {
            color: #4472C4;
            min-width: 120px;
            display: inline-block;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 10px;
        }
        
        th {
            background-color: #4472C4;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
        }
        
        td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9ecef;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Estilos específicos para impressão */
        @media print {
            body {
                margin: 0;
                padding: 15px;
                font-size: 11px;
                line-height: 1.3;
            }
            
            .header {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            .info-section {
                margin-bottom: 20px;
            }
            
            .info-section h3 {
                font-size: 14px;
                margin-bottom: 8px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
                margin-bottom: 15px;
            }
            
            .stat-item {
                padding: 8px;
            }
            
            .stat-number {
                font-size: 16px;
            }
            
            .stat-label {
                font-size: 10px;
            }
            
            table {
                font-size: 9px;
                margin-top: 15px;
            }
            
            th, td {
                padding: 4px 3px;
            }
            
            .filters-list li {
                padding: 2px 0;
                font-size: 10px;
            }
            
            .footer {
                margin-top: 20px;
                padding-top: 10px;
                font-size: 9px;
            }
            
            /* Evitar quebras de página em elementos importantes */
            .header, .info-section h3 {
                page-break-after: avoid;
            }
            
            /* Quebrar página se necessário */
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Associados</h1>
        <p>Associação dos Moradores e Comerciantes do Iguape - AMCIG</p>
        <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="info-section">
        <h3>Estatísticas Gerais</h3>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">{{ $totalAssociados }}</div>
                <div class="stat-label">Total de Associados</div>
            </div>
            
            @if($porSexo->count() > 0)
                @foreach($porSexo as $sexo => $count)
                    <div class="stat-item">
                        <div class="stat-number">{{ $count }}</div>
                        <div class="stat-label">{{ ucfirst($sexo) }}</div>
                    </div>
                @endforeach
            @endif
            
            @if($porTipo->count() > 0)
                @foreach($porTipo as $tipo => $count)
                    <div class="stat-item">
                        <div class="stat-number">{{ $count }}</div>
                        <div class="stat-label">{{ ucfirst($tipo) }}</div>
                    </div>
                @endforeach
            @endif
            
            @if($porStatus->count() > 0)
                @foreach($porStatus as $status => $count)
                    <div class="stat-item">
                        <div class="stat-number">{{ $count }}</div>
                        <div class="stat-label">{{ ucfirst($status) }}</div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <!-- Filtros Aplicados -->
    @if(count($filtros) > 0)
    <div class="info-section">
        <h3>Filtros Aplicados</h3>
        <ul class="filters-list">
            @foreach($filtros as $filtro => $valor)
                <li><strong>{{ $filtro }}:</strong> {{ $valor }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Tabela de Associados -->
    <div class="info-section">
        <h3>Lista de Associados</h3>
        
        @if($associados->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Matrícula</th>
                        <th>CPF</th>
                        <th>Idade</th>
                        <th>Sexo</th>
                        <th>Telefone</th>
                        <th>Bairro</th>
                        <th>Rua</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Data Cadastro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($associados as $associado)
                        <tr>
                            <td>{{ $associado->name }}</td>
                            <td>{{ $associado->matricula ?? 'N/A' }}</td>
                            <td>{{ $associado->cpf ?? 'N/A' }}</td>
                            <td class="text-center">{{ $associado->idade ?? 'N/A' }}</td>
                            <td class="text-center">{{ $associado->sexo ? ucfirst($associado->sexo) : 'N/A' }}</td>
                            <td>{{ $associado->telefone ?? 'N/A' }}</td>
                            <td>{{ $associado->bairro ?? 'N/A' }}</td>
                            <td>{{ $associado->logradouro ?? 'N/A' }}</td>
                            <td class="text-center">
                                @switch($associado->tipo_associado)
                                    @case('morador')
                                        Morador
                                        @break
                                    @case('comerciante')
                                        Comerciante
                                        @break
                                    @case('ambos')
                                        Ambos
                                        @break
                                    @default
                                        N/A
                                @endswitch
                            </td>
                            <td class="text-center">
                                @switch($associado->status)
                                    @case('pendente')
                                        <span class="badge badge-warning">Pendente</span>
                                        @break
                                    @case('aprovado')
                                        <span class="badge badge-success">Aprovado</span>
                                        @break
                                    @case('rejeitado')
                                        <span class="badge badge-danger">Rejeitado</span>
                                        @break
                                    @case('desativado')
                                        <span class="badge badge-secondary">Desativado</span>
                                        @break
                                    @default
                                        N/A
                                @endswitch
                            </td>
                            <td class="text-center">{{ $associado->created_at ? $associado->created_at->format('d/m/Y') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #666; font-style: italic;">
                Nenhum associado encontrado com os filtros aplicados.
            </p>
        @endif
    </div>

    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo sistema AMCIG em {{ now()->format('d/m/Y H:i') }}</p>
        <p>Para mais informações, acesse o painel administrativo do sistema.</p>
    </div>
</body>
</html>
