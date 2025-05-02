<!doctype html>

<html
  lang="en"
  class="light-style layout-wide customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="public/assets/"
  data-template="vertical-menu-template"
  data-style="light">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login - Lead</title>

    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="public/assets/img/branding/leade.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
      rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="public/assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="public/assets/vendor/fonts/tabler-icons.css" />
    <link rel="stylesheet" href="public/assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->

    <link rel="stylesheet" href="public/assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="public/assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />

    <link rel="stylesheet" href="public/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="public/assets/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="public/assets/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="public/assets/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="public/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="public/assets/vendor/js/helpers.js"></script>
    <script src="public/assets/vendor/js/template-customizer.js"></script>

    <script src="public/assets/js/config.js"></script>
  </head>

<style>
  .d-grid,.btn:hover{
    background-color: #008B98!important;
  }
  @media screen and (max-width: 767px) {
    img[src="public/assets/img/branding/leade.png"] {
      display: none;
    }
  }
</style>

  <body>
    <!-- Content -->

    <div class="authentication-wrapper authentication-cover">
      <!-- Logo -->
      
      <img src="public/assets/img/branding/leade.png" alt="Logo" style="width: 170px; height: auto;
      margin-left: 20px; margin-top: 20px;">

        <!-- <span class="app-brand-text demo text-heading fw-bold">Vuexy</span> -->
     
      <!-- /Logo -->
      <div class="authentication-inner row m-0">
        <!-- /Left Text -->
        <div class="d-none d-lg-flex col-lg-8 p-0">
          <div class="auth-cover-bg auth-cover-bg-color d-flex justify-content-center align-items-center">
            <img
              src="../../assets/img/illustrations/auth-login-illustration-light.png"
              alt="auth-login-cover"
              class="my-5 auth-illustration"
              data-app-light-img="illustrations/auth-login-illustration-light.png"
              data-app-dark-img="illustrations/auth-login-illustration-dark.png" />

            <img
              src="public/assets/img/illustrations/bg-shape-image-light.png"
              alt="auth-login-cover"
              class="platform-bg"
              data-app-light-img="illustrations/bg-shape-image-light.png"
              data-app-dark-img="illustrations/bg-shape-image-dark.png" />
          </div>
        </div>
        <!-- /Left Text -->

        <!-- Login -->
        <div class="d-flex col-12 col-lg-4 align-items-center authentication-bg p-sm-12 p-6">
          <div class="w-px-400 mx-auto mt-12 pt-5">
            <h4 class="mb-1">Welcome to Grupo Rosolsa! 👋</h4>
            <p class="mb-6">Please sign-in to your account and start the adventure</p>

            <form method="POST" action="{{ route('login') }}" class="p-4 border rounded shadow-sm">
    @csrf

    <!-- Email Field -->
    <div class="mb-3">
        <label for="email" class="form-label">{{ __('Email Address') }}</label>
        <input id="email" type="email" 
               class="form-control @error('email') is-invalid @enderror" 
               name="email" value="{{ old('email') }}" required 
               autocomplete="email" autofocus>
        @error('email')
            <div class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="mb-3">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input id="password" type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" required autocomplete="current-password">
        @error('password')
            <div class="invalid-feedback">
                <strong>{{ $message }}</strong>
            </div>
        @enderror
    </div>

    <!-- Remember Me Checkbox -->
    <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">
            {{ __('Remember Me') }}
        </label>
    </div>

    <!-- Submit Button -->
    <div class="d-grid mb-3">
    <button type="submit" class="btn btn-primary btn-block" style="background-color: #008B98;">
        {{ __('Login') }}
    </button>
</div>

    <!-- Additional Links -->
    
</form>

          
        <!-- /Login -->
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="public/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="public/assets/vendor/libs/popper/popper.js"></script>
    <script src="public/assets/vendor/js/bootstrap.js"></script>
    <script src="public/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="public/assets/vendor/libs/hammer/hammer.js"></script>
    <script src="public/assets/vendor/libs/i18n/i18n.js"></script>
    <script src="public/assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="public/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="public/assets/vendor/libs/@form-validation/popular.js"></script>
    <script src="public/assets/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="public/assets/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->
    <script src="public/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="public/assets/js/pages-auth.js"></script>
  </body>
</html>
