 <!--Footer-->
 <footer class="main2">
 <style>
        #map {
            height: 350px;
        }
    </style>
    <!--Suscribe section-->
    <section  class="upfoot container d-flex flex-column mt-0 pt-0 rounded-4 bg-light">
    <img src="{{ asset('assets/front/img/Group 60 (1).png') }}" class="footimg " alt="" srcset="">
    <div class="indexflex-content row">
   
      <div class="col-xl-6 col-lg-12 d-none d-md-block">
       <p class="themeclr px-5 pt-5 fw-semibold fs-5">Overall, an HRM Mobile App provides employees with a user-friendly and accessible platform to manage various HR-related tasks, improving efficiency, transparency, and employee satisfaction within the organization.
       </p>
      </div>
      <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12">
        <p class="themeclr px-5 pt-5 fw-semibold fs-5 align-items-center d-flex"><i class="fa-solid fa-clock themeclr mx-2 fs-4"></i>Keep Up To Date in every topic and get updates</p>
        <button class="text-light border border-2 py-2 px-4 fs-4 rounded-5 bgtheme float-right mx-5"><i class="fa-solid fa-paper-plane text-light mx-2 fs-4"></i>Subscribe Now</button>
      </div>
    </div>
 
    <img src="{{ asset('assets/front/img/Group 60 (1).png') }}" class="footimg2 ms-auto" alt="" srcset=""></div>
  </section>

    <div class="footerontent mx-auto mt-5">
      <div class="row ">

        <div class="col-lg-3 col-md-6 col-sm-12 pt-5">
          <img src="{{asset('assets/front/img/hrm LOGO WHITWE 1.png')}}" alt="">
          <p class="text-light fontw subhead pt-2">
            Hrm Mobile App (Human Resource Management Mobile App) is a comprehensive one-stop solution designed to streamline various HR processes and provide convenience for employees within an organization. It aims to centralize HR functions and empower employees with self-service capabilities, enabling them to access and manage their HR-related information anytime
          </p>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12 sideborder  pt-5">
          <p class="text-light fs-4 mx-2">Contact Us</p>
          <div class="pt-2">
            <a href="" class="text-decoration-none">
              <p class="text-light subhead text-start fontw"> <i
                  class="fa-solid fa-envelope fs-5 text-light text-start mx-2"></i><?php $email = Config::get('email'); ?>
                  {{ $email }}
              </p>
            </a>
            <a href="" class="text-decoration-none">
              <p class="text-light subhead text-start fontw"><i
                  class="fa-solid fa-phone fs-5 text-light text-start mx-2"></i><?php $mob = Config::get('mobile_no'); ?>
                    {{ $mob }}
              </p>
            </a>
            <a href="" class="text-decoration-none">
              <p class="text-light subhead fontw"><i
                  class="fa-solid fa-location-dot fs-5 text-light text-start mx-2"></i><?php $address = Config::get('address'); ?>
                      {{ $address }}
              </p>
            </a>
            <div class="text-center">
              <i class="fa-brands fa-instagram themeclr bg-light p-1 fs-4 mx-3"></i>
              <i class="fa-brands fa-twitter themeclr bg-light p-1 fs-4 mx-3"></i>
              <i class="fa-brands fa-facebook themeclr bg-light p-1 fs-4 mx-3"></i>
            </div>

          </div>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12  sideborder  pt-5">
          <p class="text-light fs-4 mx-2">About Us</p>
          <p class="text-light fontw subhead pt-2"> In publishing and graphic
            design, Lorem ipsum is a
            placeholder text
          </p>
        </div>


        <div class="col-lg-3 col-md-6 col-sm-12  sideborder  pt-5">
          <p class="text-light fs-4 mx-2" >Office location</p>
          <div id="map" ></div>
          <a  href=""></a>
        </div>


      </div>


    </div>

  </footer>

  <!--subFooter-->
  <div class="subfooter">
    <div class="d-flex justify-content-between py-3 mx-2">
      <span class="themeclr fw-600 subhead col-4">© {{ date('Y') }} <a href=" {{ url('/') }}" target="_blank"> Synilogictech</a>. All rights reserved. </span>
      <a href="{{ url('page/terms-condition') }}" class="col-4 text-decoration-none text-center"><span class="themeclr fw-600 subhead">Terms &amp; Condition</span></a>
      <a href="{{ url('page/privacy-policy') }}" class="col-4 text-decoration-none text-end"><span class="themeclr fw-600 subhead">Privacy Policies</span></a>
    </div>
  </div>
  
  
