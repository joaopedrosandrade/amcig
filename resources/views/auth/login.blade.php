<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <title>Login - AMCIG</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta content="Login de Associados AMCIG" name="description" />
  <meta content="AMCIG" name="author" />
  
  <!-- layout setup -->
  <script type="module" src="{{asset('assets/js/layout-setup.js')}}"></script>
  
  <!-- App favicon -->
  <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}">  <!-- Simplebar Css -->
  <link rel="stylesheet" href="{{asset('assets/libs/simplebar/simplebar.min.css')}}">
  <!-- Swiper Css -->
  <link href="{{asset('assets/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
  <!-- Nouislider Css -->
  <link href="{{asset('assets/libs/nouislider/nouislider.min.css')}}" rel="stylesheet">
  <!-- Bootstrap Css -->
  <link href="{{asset('assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css">
  <!--icons css-->
  <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css">
  <!-- App Css-->
  <link href="{{asset('assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css">

</head>

<body>
<!-- START -->


<div class="container">
  <div class="row justify-content-center align-items-center min-vh-100 pt-10 pb-10">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
      <div class="card mx-xxl-8 shadow-none">
                  <div class="card-body p-8">
            <h3 class="fw-medium text-center">Área do Associado</h3>
            
            <div class="text-center mb-4">
              <img src="{{asset('assets/images/logos.png')}}" alt="Logo AMCIG" class="img-fluid" style="max-height: 100px;">
            </div>
       
            <!--<p class="text-center  mb-0 ms-1">AMCIG - Associação de Moradores e Comerciantes da Ilha de Guriri</p> -->
          <p class="mb-8 text-muted text-center">Faça o seu login abaixo</p>
          
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          
          <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="mb-4">
              <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
              <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email" name="email" value="{{ old('email') }}" placeholder="Digite seu email" required autocomplete="email" autofocus>
              @if ($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
              @endif
            </div>
            <div class="mb-4">
              <label for="password" class="form-label">Senha <span class="text-danger">*</span></label>
              <div class="position-relative">
                <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password" name="password" placeholder="Digite sua senha" required autocomplete="current-password">
                <button type="button" class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted toggle-password" id="toggle-password" data-target="password"><i class="ri-eye-off-line align-middle"></i></button>
                @if ($errors->has('password'))
                  <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                @endif
              </div>
            </div>
            <div class="my-6">
              <div class="d-flex justify-content-between align-items-center">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>
                <div class="form-text">
                  <a href="{{ route('password.request') }}" class="link">Esqueceu sua senha?</a>
                </div>
              </div>
            </div>
            <div>
              <button type="submit" class="btn btn-primary w-100 mb-4" id="loginBtn">Acessar</button>
              
            </div>
          </form>
          <p class="text-center mt-6 mb-0 text-muted fs-13">Não é associado? <a href="{{ url('/') }}" class="link fw-semibold">Cadastre-se aqui</a></p>
        </div>
      </div>
      <p class="position-relative text-center fs-13 mb-0">©
        <script>document.write(new Date().getFullYear())</script> AMCIG - Associação de Moradores e Comerciantes da Ilha de Guriri
      </p>
    </div>
  </div>
</div>

<!-- JAVASCRIPT -->
<script src="{{asset('assets/libs/swiper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/js/scroll-top.init.js')}}"></script>
<script src="{{asset('assets/js/auth/auth.init.js')}}"></script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    
    // Toggle para mostrar/ocultar senha
    const togglePassword = document.getElementById('toggle-password');
    const passwordField = document.getElementById('password');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        const icon = this.querySelector('i');
        if (type === 'password') {
            icon.className = 'ri-eye-off-line align-middle';
        } else {
            icon.className = 'ri-eye-line align-middle';
        }
    });
    
    // Validação do formulário
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value.trim();
        
        if (!email || !password) {
            e.preventDefault();
            return false;
        }
        
        // Desabilita o botão para evitar múltiplos envios
        loginBtn.disabled = true;
        loginBtn.innerHTML = '<i class="ri-loader-4-line me-2"></i>Acessando...';
    });
    

});
</script>
</body>

</html>