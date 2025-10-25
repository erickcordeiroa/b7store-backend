<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boas-vindas</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .logo {
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
            margin: 0;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            color: #333333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .text {
            color: #666666;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .button:hover {
            opacity: 0.9;
        }
        .features {
            background-color: #f9f9f9;
            padding: 30px;
            margin: 30px 0;
            border-radius: 8px;
        }
        .feature-item {
            margin-bottom: 15px;
            padding-left: 25px;
            position: relative;
        }
        .feature-item:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #667eea;
            font-weight: bold;
            font-size: 18px;
        }
        .footer {
            background-color: #333333;
            color: #ffffff;
            padding: 30px;
            text-align: center;
            font-size: 14px;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #ffffff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 class="logo">{{ config('app.name', 'Sua Empresa') }}</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <h2 class="greeting">Olá, {{ $user->name }}! 👋</h2>
            
            <p class="text">
                É com grande prazer que damos as boas-vindas à nossa plataforma! 
                Estamos muito felizes em tê-lo(a) conosco.
            </p>

            <p class="text">
                Sua conta foi criada com sucesso e você já pode começar a explorar 
                todos os recursos disponíveis.
            </p>

            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="button">Acessar Minha Conta</a>
            </div>

            <!-- Features -->
            <div class="features">
                <h3 style="color: #333333; margin-top: 0;">O que você pode fazer:</h3>
                <div class="feature-item">Acesso completo a todos os recursos da plataforma</div>
                <div class="feature-item">Suporte dedicado sempre que precisar</div>
                <div class="feature-item">Atualizações e novidades em primeira mão</div>
                <div class="feature-item">Comunidade ativa de usuários</div>
            </div>

            <p class="text">
                Se você tiver alguma dúvida ou precisar de ajuda, não hesite em 
                entrar em contato conosco. Nossa equipe está pronta para ajudá-lo(a)!
            </p>

            <p class="text" style="margin-top: 30px;">
                <strong>Atenciosamente,</strong><br>
                Equipe {{ config('app.name', 'Sua Empresa') }}
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="social-links">
                <a href="#">Facebook</a> | 
                <a href="#">Twitter</a> | 
                <a href="#">Instagram</a>
            </div>
            
            <p style="margin: 10px 0;">
                {{ config('app.name') }} - Todos os direitos reservados © {{ date('Y') }}
            </p>
            
            <p style="margin: 10px 0; font-size: 12px; color: #999999;">
                Você está recebendo este email porque se cadastrou em nossa plataforma.<br>
                <a href="#">Cancelar inscrição</a>
            </p>
        </div>
    </div>
</body>
</html>