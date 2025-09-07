# Configuração de Email - AMCIG

## Configuração do SendGrid

Para configurar o envio de emails usando SendGrid, você precisa adicionar as seguintes variáveis ao seu arquivo `.env`:

```env
# Configurações de Email
MAIL_DRIVER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=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
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@amcig.com.br
MAIL_FROM_NAME="AMCIG - Associação de Moradores e Comerciantes"

# SendGrid API Key
SENDGRID_API_KEY=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
```

## Funcionalidades Implementadas

### 1. Template de Email
- **Arquivo**: `resources/views/emails/associado-welcome.blade.php`
- **Características**:
  - Design responsivo
  - Identidade visual da AMCIG
  - Cores: azul (#007bff, #0056b3)
  - Logo da associação
  - Informações do associado
  - Status do cadastro

### 2. Classe Mailable
- **Arquivo**: `app/Mail/AssociadoWelcomeMail.php`
- **Função**: Gerencia o envio do email de boas-vindas

### 3. Evento e Listener
- **Evento**: `app/Events/AssociadoCadastrado.php`
- **Listener**: `app/Listeners/EnviarEmailAssociadoCadastrado.php`
- **Função**: Dispara automaticamente o email quando um associado é cadastrado

### 4. Integração no Controller
- **Arquivo**: `app/Http/Controllers/FrontController.php`
- **Modificação**: Adicionado disparo do evento após criação do usuário

## Como Testar

### 1. Teste via Comando Artisan
```bash
php artisan test:email-associado seu-email@exemplo.com
```

### 2. Teste via Cadastro Real
1. Acesse a página de cadastro de associado
2. Preencha o formulário com dados válidos
3. O email será enviado automaticamente após o cadastro

## Estrutura dos Arquivos Criados/Modificados

```
app/
├── Mail/
│   └── AssociadoWelcomeMail.php          # Classe para envio de email
├── Events/
│   └── AssociadoCadastrado.php           # Evento disparado no cadastro
├── Listeners/
│   └── EnviarEmailAssociadoCadastrado.php # Listener que envia o email
├── Console/Commands/
│   └── TestEmailAssociado.php            # Comando para teste
└── Http/Controllers/
    └── FrontController.php                # Modificado para disparar evento

resources/views/emails/
└── associado-welcome.blade.php            # Template do email

config/
└── services.php                           # Modificado para incluir SendGrid
```

## Configurações do SendGrid

- **Servidor SMTP**: smtp.sendgrid.net
- **Portas disponíveis**: 
  - 25, 587 (para conexões não criptografadas/TLS)
  - 465 (para conexões SSL)
- **Usuário**: apikey
- **Senha**: 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

## Próximos Passos

1. **Configurar domínio**: Para melhorar a entrega, configure um domínio personalizado no SendGrid
2. **Monitoramento**: Implementar logs de envio de email
3. **Templates adicionais**: Criar templates para outros tipos de comunicação
4. **Fila de emails**: Implementar queue para envio em background

## Troubleshooting

### Email não enviado
1. Verifique as configurações do `.env`
2. Confirme se a API key do SendGrid está correta
3. Verifique os logs do Laravel: `storage/logs/laravel.log`

### Template não renderizado
1. Limpe o cache: `php artisan view:clear`
2. Verifique se o arquivo existe em `resources/views/emails/`

### Evento não disparado
1. Limpe o cache de configuração: `php artisan config:clear`
2. Verifique se o EventServiceProvider está registrado corretamente
