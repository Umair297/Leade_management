@extends('home')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
              <div class="row g-6">
                 <!-- Hero: Start -->
      <section id="hero-animation">
        <div id="landingHero" class="section-py landing-hero position-relative">
          <img
            src="public/assets/img/front-pages/backgrounds/hero-bg.png"
            alt="hero background"
            class="position-absolute top-0 start-50 translate-middle-x object-fit-cover w-100 h-100"
            data-speed="1" />
          <div class="container">
            <div class="hero-text-box text-center position-relative">
              <h1 class="text-primary hero-title display-6 fw-extrabold" style="color: #008B98 !important;">
                Wellcome
              </h1>
              <h2 class="hero-sub-title h6 mb-6">
    Production-ready & easy-to-use Solutions by Leade<br class="d-none d-lg-block" />
    designed for Reliability and Customizability.
</h2>

            </div>
            <div id="heroDashboardAnimation" class="hero-animation-img">
              <a href="">
                <div id="heroAnimationImg" class="position-relative hero-dashboard-img" style="display: flex; justify-content: center;">
                  <img
                    src="public/assets/img/illustrations/page-misc-under-maintenance.png"
                    alt="hero dashboard"
                    class="animation-img" style="width: 400px;" />
                
                </div>
              </a>
            </div>
          </div>
        </div>
        <div class="landing-hero-blank"></div>
      </section>
      <!-- Hero: End -->
              </div>
            </div>
            @endsection