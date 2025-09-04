<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carteirinha - {{ $user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: white;
            padding: 20px;
        }
        
        .carteirinha-simples {
            width: 500px;
            height: 320px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden;
            margin: 0 auto;
            border: 2px solid #e9ecef;
        }
        
        .carteirinha-simples::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="watermark" patternUnits="userSpaceOnUse" width="20" height="20"><text x="10" y="10" font-size="8" fill="rgba(0,0,0,0.02)" text-anchor="middle">AMCIG</text></pattern></defs><rect width="100" height="100" fill="url(%23watermark)"/></svg>');
            pointer-events: none;
        }
        
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        
        .logo-area {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-weight: bold;
            color: #007bff;
            font-size: 18px;
        }
        
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }
        
        .titulo {
            font-size: 22px;
            font-weight: bold;
        }
        
        .subtitulo {
            font-size: 13px;
            opacity: 0.9;
            line-height: 1.2;
        }
        
        .content {
            padding: 25px;
            position: relative;
            z-index: 1;
            display: flex;
            gap: 25px;
            height: calc(100% - 100px);
        }
        
        .foto-area {
            width: 100px;
            height: 120px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .foto-area i {
            font-size: 50px;
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
            margin-bottom: 12px;
            font-size: 14px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #495057;
            min-width: 70px;
        }
        
        .info-value {
            text-align: right;
            color: #212529;
            flex-grow: 1;
            margin-left: 15px;
            word-break: break-word;
        }
        
        .matricula {
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            letter-spacing: 3px;
            color: #007bff;
            border: 2px solid #e9ecef;
        }
        
        .token {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            opacity: 0.7;
            max-width: 25px;
            word-break: break-all;
            color: #6c757d;
            writing-mode: vertical-rl;
            text-orientation: mixed;
            line-height: 1.2;
        }
        
        .qr-code {
            width: 120px;
            height: 120px;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px;
        }
        
        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .barcode {
            width: 250px;
            height: 50px;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 4px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 5px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            font-weight: bold;
            color: #000;
            letter-spacing: 3px;
        }
        
        .barcode::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 10px;
            right: 10px;
            height: 3px;
            background: repeating-linear-gradient(
                to right,
                #000 0px,
                #000 3px,
                transparent 3px,
                transparent 6px
            );
            transform: translateY(-50%);
        }
        
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            opacity: 0.02;
            pointer-events: none;
            z-index: 0;
            color: #000;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .carteirinha-simples {
                box-shadow: none;
                border: 2px solid #ccc;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="carteirinha-simples">
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
                    
                    <div class="info-row">
                        <span class="info-label">Membro desde:</span>
                        <span class="info-value">{{ $user->data_aprovacao ? $user->data_aprovacao->format('d/m/Y') : $user->created_at->format('d/m/Y') }}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="info-label">Cidade:</span>
                        <span class="info-value">{{ $user->cidade }}/{{ $user->uf }}</span>
                    </div>
                </div>
                
                <div class="matricula">
                    {{ $user->matricula }}
                </div>
                
                <div class="qr-code">
                    {!! $qrCode !!}
                </div>
                
                <div class="barcode">
                    {{ $barcode }}
                </div>
            </div>
            
            <div class="token">
                {{ substr($token, 0, 25) }}...
            </div>
        </div>
    </div>
    
    <script>
        // Auto-print quando a página carrega
        window.addEventListener('load', function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        });
        
        // Adiciona marca d'água dinâmica
        function addDynamicWatermark() {
            const watermark = document.querySelector('.watermark');
            const timestamp = new Date().toISOString();
            watermark.textContent = `AMCIG ${timestamp.slice(0, 10)}`;
        }
        
        addDynamicWatermark();
    </script>
</body>
</html>
