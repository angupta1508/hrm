  <!--Header-->
  <header class="mynav">
      <nav class="navbar navbar-expand-lg">
          <div class="container">
              <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center" class="me-auto">
                  <img src="{{ asset('assets/front/img/hrm LOGO1 1.png') }}" class="brand" alt="" srcset="">
              </a>
              <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse"
                  data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                  aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon bg-light"></span>
              </button>
              <div class="collapse navbar-collapse" id="navbarSupportedContent">

                  <ul class="navbar-nav  mb-lg-0  fw-bolder ms-auto">
                      <li class="nav-item ">
                          <a class="nav-link fs-5 fw-bold me-4 {{ Route::is('home') ? 'active ' : '' }}"
                              aria-current="page" href="{{ route('home') }}">Home</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link fs-5 fw-bold me-4 {{ Route::is('about') ? 'active ' : '' }}" href="{{ route('about') }}">About</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link fs-5 fw-bold me-4" href="#service">Service</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link fs-5 fw-bold me-4" href="#pricing">Pricing</a>
                      </li>
                      <li class="nav-item">
                          <a class="nav-link fs-5 fw-bold me-4 {{ Route::is('contact') ? 'active ' : '' }}" href="{{ route('contact') }}">Contact</a>
                      </li>
                  </ul>
                  @if (Auth::guard('front-admin')->check())
                      <div class="dropdown">
                          <a class="dropdown-toggle nav-item nav-link" id="dropdownMenuButton1"
                              data-bs-toggle="dropdown" aria-expanded="false">
                              {{ !empty(Auth::guard('front-admin')->user()->name) ? Auth::guard('front-admin')->user()->name : 'Unknown' }}
                          </a>
                          <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                              <li>
                                  <a href="{{ route('admindashboard') }}"
                                      class="text-start dropdown-item text-decoration-none  pt-2 ps-2 rounded-top-1">
                                      My Account
                                  </a>
                              </li>
                              <li>
                                  <a href="{{ route('userlogout') }}"
                                      class="text-start dropdown-item text-decoration-none  pt-2 ps-2 rounded-top-1">
                                      Sign Out
                                  </a>
                              </li>
                          </ul>
                      </div>
                  @else
                      <a href="#loginTab" class="sidecircle toggle"><span  class="text-start text-dark text-decoration-none  pt-2  fs-5 ps-2">Login</span></a>
                  @endif
              </div>
          </div>
      </nav>
  </header>
