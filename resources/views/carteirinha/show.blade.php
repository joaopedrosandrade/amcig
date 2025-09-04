<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteirinha Virtual - {{ $user->name }} - AMCIG</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .carteirinha-container {
            display: flex;
            gap: 30px;
            max-width: 1000px;
            width: 100%;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .carteirinha {
            width: 450px;
            height: 280px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            border: 1px solid #e9ecef;
        }
        
        .carteirinha::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="watermark" patternUnits="userSpaceOnUse" width="25" height="25"><text x="12.5" y="12.5" font-size="10" fill="rgba(0,0,0,0.02)" text-anchor="middle">AMCIG</text></pattern></defs><rect width="100" height="100" fill="url(%23watermark)"/></svg>');
            pointer-events: none;
        }
        
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 15px 20px;
            text-align: center;
            position: relative;
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .logo {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-weight: bold;
            color: #007bff;
            font-size: 16px;
        }
        
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }
        
        .titulo {
            font-size: 18px;
            font-weight: bold;
        }
        
        .subtitulo {
            font-size: 11px;
            opacity: 0.9;
            line-height: 1.2;
        }
        
        .content {
            padding: 20px;
            position: relative;
            z-index: 1;
            display: flex;
            gap: 20px;
            height: calc(100% - 80px);
        }
        
        .foto-area {
            width: 80px;
            height: 100px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .foto-area i {
            font-size: 40px;
            color: #6c757d;
        }
        
        .info-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .info-rows {
            flex-grow: 1;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
            min-width: 60px;
        }
        
        .info-value {
            text-align: right;
            color: #212529;
            flex-grow: 1;
            margin-left: 10px;
            word-break: break-word;
        }
        
        .matricula {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-top: 15px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            letter-spacing: 2px;
            color: #007bff;
            border: 2px solid #e9ecef;
        }
        
        .token {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 8px;
            opacity: 0.6;
            max-width: 20px;
            word-break: break-all;
            color: #6c757d;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            line-height: 1.2;
        }
        
        .verso-content {
            padding: 20px;
            text-align: center;
            position: relative;
            z-index: 1;
            height: calc(100% - 60px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .verso-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #495057;
        }
        
        .qr-code {
            width: 100px;
            height: 100px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .qr-code i {
            font-size: 50px;
            color: #6c757d;
        }
        
        .barcode {
            width: 200px;
            height: 40px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 4px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        
        .barcode::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 10px;
            right: 10px;
            height: 2px;
            background: repeating-linear-gradient(
                to right,
                #000 0px,
                #000 2px,
                transparent 2px,
                transparent 4px
            );
            transform: translateY(-50%);
        }
        
        .validade {
            font-size: 12px;
            color: #6c757d;
            margin-top: 10px;
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            opacity: 0.03;
            pointer-events: none;
            z-index: 0;
            color: #000;
        }
        
        .verso-header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 15px 20px;
            text-align: center;
        }
        
        .verso-header h3 {
            font-size: 16px;
            margin: 0;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .carteirinha-container {
                max-width: none;
                gap: 20px;
            }
            
            .carteirinha {
                box-shadow: none;
                border: 2px solid #ccc;
            }
        }
        
        @media (max-width: 768px) {
            .carteirinha-container {
                flex-direction: column;
                align-items: center;
            }
            
            .carteirinha {
                width: 380px;
                height: 240px;
            }
            
            .content {
                padding: 15px;
                gap: 15px;
            }
            
            .foto-area {
                width: 60px;
                height: 80px;
            }
            
            .info-row {
                font-size: 12px;
                margin-bottom: 8px;
            }
            
            .matricula {
                font-size: 16px;
                padding: 10px;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="carteirinha-container">
        <!-- Frente da Carteirinha -->
        <div class="carteirinha">
            <div class="watermark">AMCIG</div>
            
            <div class="header">
                <div class="logo-area">
                    <div class="logo">
                        <img src="{{ asset('assets/images/logo-md.png') }}" alt="AMCIG Logo">
                    </div>
                    <div>
                        <div class="titulo">AMCIG</div>
                        <div class="subtitulo">Associação de Moradores e Comerciantes da Ilha de Guriri</div>
                    </div>
                </div>
            </div>
            
            <div class="content">
                <div class="foto-area">
                    <i class="fas fa-user"></i>
                </div>
                
                <div class="info-area">
                    <div class="info-rows">
                        <div class="info-row">
                            <span class="info-label">Nome:</span>
                            <span class="info-value">{{ $user->name }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">Tipo:</span>
                            <span class="info-value">{{ ucfirst($user->tipo_associado) }}</span>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">CPF:</span>
                            <span class="info-value">{{ $user->cpf }}</span>
                        </div>
                        
                       
                    </div>
                    
                    <div class="matricula">
                        {{ $user->matricula }}
                    </div>
                </div>
                
                <div class="token">
                    {{ substr($token, -16) }}
                </div>
            </div>
        </div>
        
        <!-- Verso da Carteirinha -->
        <div class="carteirinha">
            <div class="watermark">AMCIG</div>
            
            <div class="verso-header">
                <h3>  {{ $user->matricula }}</h3>
            </div>
            
            <div class="verso-content">
                <div>
                    <div class="qr-code">
                        <i class="fas fa-qrcode"></i>
                    </div>
                    
                    <div class="barcode"></div>
                </div>
                
                <div class="validade">
                    Válida até: {{ $user->data_aprovacao ? $user->data_aprovacao->addYear()->format('d/m/Y') : date('Y') }}
                </div>
                
                <div class="token">
                    {{ substr($token, -16) }}
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Adiciona funcionalidade de impressão
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
        
        // Adiciona funcionalidade de download como PDF (se disponível)
        function downloadAsPDF() {
            window.print();
        }
        
        // Adiciona marca d'água dinâmica
        function addDynamicWatermark() {
            const watermarks = document.querySelectorAll('.watermark');
            const timestamp = new Date().toISOString();
            
            watermarks.forEach(watermark => {
                watermark.textContent = `AMCIG ${timestamp.slice(0, 10)}`;
            });
        }
        
        // Executa quando a página carrega
        window.addEventListener('load', function() {
            addDynamicWatermark();
        });
    </script>
</body>
</html>
