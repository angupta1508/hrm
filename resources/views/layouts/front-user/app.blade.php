<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" , content="@yield('meta_description')">
    <meta name="keywords" , content="@yield('meta_kewords')">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ url(config('constants.setting_image_path') . config('website_favicon')) }}">
    <link rel="icon" type="image/png" href="{{ url(config('constants.setting_image_path') . config('website_favicon')) }}">
    <title>
        {{ Config::get('company_name') }} : @yield('meta_kewords')
    </title>
    <!--animation-->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <!--bootstrapcss-->

    <!--fancy box-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" type="text/css" media="screen" />
    <!--fancy box-->

    <!-- select2 -->
    <link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/247b574028.js" crossorigin="anonymous"></script>


    <!-- CSS Files -->
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/front/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/toastr/toastr.css') }}" rel="stylesheet" />

    <link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/front/css/style.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha256-4+XzXVhsDmqanXGHaHvgh1gMQKX40OUvDEBTu8JcmNs=" crossorigin="anonymous"></script>
    {{-- <script src="{{ asset('js/share.js') }}"></script> --}}
</head>

<body data-theme="light" class="g-sidenav-show {{ Request::is('admin/register') || Request::is('admin/login') || Request::is('admin/forgot-password') ? 'login-signup-page' : '' }} {{ \Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '') }} ">
    @include('layouts.front-user.nav')
    @include('layouts.front-user.alert_message')
    @yield('content')
    @include('layouts.front-user.footer')
    <!-- jQuery -->
    <script src="{{ asset('assets/front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('assets/js/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/front/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/index.js') }}"></script>
    <!-- Page specific script -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="{{ asset('plugins/toastr/toastr.js') }}"></script>
    <link href="{{ asset('plugins/summernote/summernote.css') }}" rel="stylesheet">

    <!--fancy box-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js">
    </script>
    <!--fancy box-->


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        // function openClass(evt, className) {
        //     var i, tabcontent, tablinks;
        //     tabcontent = document.getElementsByClassName("tabcontent");
        //     for (i = 0; i < tabcontent.length; i++) {
        //         tabcontent[i].style.display = "none";
        //     }
        //     tablinks = document.getElementsByClassName("tablinks");
        //     for (i = 0; i < tablinks.length; i++) {
        //         tablinks[i].className = tablinks[i].className.replace(" active", "");
        //     }
        //     document.getElementById(className).style.display = "block";
        //     evt.currentTarget.className += " active";
        // }

        // document.getElementById("defaultOpen").click();

        // document.addEventListener("DOMContentLoaded", function(event) {

        //     function OTPInput() {
        //         const inputs = document.querySelectorAll('#otp > *[id]');
        //         for (let i = 0; i < inputs.length; i++) {
        //             inputs[i].addEventListener('keydown', function(event) {
        //                 if (event.key === "Backspace") {
        //                     inputs[i].value = '';
        //                     if (i !== 0) inputs[i - 1].focus();
        //                 } else {
        //                     if (i === inputs.length - 1 && inputs[i].value !== '') {
        //                         return true;
        //                     } else if (event.keyCode > 47 && event.keyCode < 58) {
        //                         inputs[i].value = event.key;
        //                         if (i !== inputs.length - 1) inputs[i + 1].focus();
        //                         event.preventDefault();
        //                     } else if (event.keyCode > 64 && event.keyCode < 91) {
        //                         inputs[i].value = String.fromCharCode(event.keyCode);
        //                         if (i !== inputs.length - 1) inputs[i + 1].focus();
        //                         event.preventDefault();
        //                     }
        //                 }
        //             });
        //         }
        //     }
        //     OTPInput();
        // });


        // const btn = document.querySelector(".sidecircle");
        // const toggle = document.querySelector(".sidetoggle");
        // const sidebox = document.querySelector(".sidebox");
        // btn.onclick = function() {
        //     if (toggle.className == "sidetoggle") {
        //         toggle.className = "sidetoggleOpen";
        //         sidebox.className = 'sideboxOpen'
        //     } else {
        //         toggle.className = "sidetoggle";
        //         sidebox.className = 'sidebox'

        //     }
        // }
    </script>
    <script type="text/javascript">
        //tree_label/
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip()
            $('.select2').select2()
            $(".datepicker").datepicker({
                dateFormat: 'dd-mm-yy'
            });

            $(".tree_label").click(function(event) {
                $(this).find('.tree_icon').toggleClass("fa-folder fa-folder-open");
            })

            var searchIDs;
            $(".tree_input").change(function(event) {
                searchIDs = $("input.tree_input:checkbox:checked").map(function() {
                    return this.id;
                }).get(); // <----
                sessionStorage.setItem("searchIDs", searchIDs); //store colors
            });


            $('.table-responsive').on('hide.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "auto");
            })
            $('.table-responsive').on('show.bs.dropdown', function() {
                $('.table-responsive').css("overflow", "inherit");
            });

            searchIDs = sessionStorage.getItem("searchIDs");
            if (searchIDs) {
                $(searchIDs.split(',')).map(function(key, value) {
                    $('.tree_label[for="' + value + '"]').trigger('click')
                });
            }


            $('.stateee').on('change', function() {
                var state_id = this.value;
                $("#city_id").html('');
                $.ajax({
                    url: "{{ route('getCity') }}",
                    type: "POST",
                    data: {
                        state_id: state_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(result) {
                        $.each(result.cities, function(key, value) {
                            $("#city_id").append('<option value="' + value.id +
                                '">' + value.city_name + '</option>');
                        });
                    }
                });
            });
            $('.stateee').trigger('change');

            // delete script
            $('.delete_confirm').click(function(event) {
                var form = $(this).closest("form");
                console.log(form)
                event.preventDefault();
                Swal.fire({
                    title: `Are you sure you want to delete this record?`,
                    text: "If you delete this, it will be gone forever.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });




            // User Infomation hover
            $(document).on('mouseenter', ".user_info", function() {

                let ele = $(this);
                var user_uni_id = $(this).data('user_uni_id');
                $.ajax({
                    url: "{{ route('getUserDetail') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user_uni_id: user_uni_id,
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $('.table-responsive').css("overflow", "inherit");
                    },
                    success: function(result) {

                        if (result.status == 1) {

                            var html = '';

                            html += '<b>Name:</b> ' + result.data.name + ' <br>'
                            html += '<b>Email:</b> ' + result.data.email + '<br>'
                            html += '<b>Mobile:</b> ' + result.data.phone + '<br>'
                            html += '<b>User Id:</b> ' + result.data.user_uni_id + '<br>'
                            html += '<b>Balance:</b> ' + result.data.wallet_balance + '<br>'

                            if (ele.find('.user_info_tooltip').length > 0) {
                                ele.find('.user_info_tooltip').html(html)
                            } else {
                                ele.append('<span class="user_info_tooltip">' + html +
                                    '</span>');
                            }
                            // toastr.success(result.msg);

                        } else {
                            toastr.error(result.msg);
                        }
                    }

                });
            });

            // User Infomation out
            $(document).on('mouseout', ".user_info", function() {
                $('.table-responsive').removeAttr("style");
                $('.user_info_tooltip').css("display", "none");
                // $('.table-responsive').css("overflow", "auto");
            });
        })
        var input = document.querySelector(".intlinput");
        // var input = document.querySelector(".intlinput");
        if (input) {
            window.intlTelInput(input, {
                autoPlaceholder: "off",
                dropdownContainer: document.body,
                excludeCountries: ["us"],
                formatOnDisplay: false,
                geoIpLookup: function(callback) {
                    $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
                hiddenInput: "phone",
                nationalMode: false,
                placeholderNumberType: "MOBILE",
                preferredCountries: ["in"],
                separateDialCode: true,
                utilsScript: "{{ asset('assets/js/utils.js') }}"
            });
        }
        var input = document.querySelector(".vendorintlinput");
        // var input = document.querySelector(".intlinput");
        if (input) {
            window.intlTelInput(input, {
                autoPlaceholder: "off",
                dropdownContainer: document.body,
                excludeCountries: ["us"],
                formatOnDisplay: false,
                geoIpLookup: function(callback) {
                    $.get("http://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
                hiddenInput: "phone",
                nationalMode: false,
                placeholderNumberType: "MOBILE",
                preferredCountries: ["in"],
                separateDialCode: true,
                utilsScript: "{{ asset('assets/js/utils.js') }}"
            });
        }
    </script>


    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        $(document).ready(function() {
            $('.package_buy').on('click', function() {
                let _token = $('meta[name="csrf-token"]').attr('content');
                var package_uni_id = $(this).data('package_uni_id');
                var type = $(this).data('type');
                $.ajax({
                    url: "{{ route('packagebuy') }}",
                    type: 'post',
                    data: {
                        _token: _token,
                        package_uni_id: package_uni_id,
                        type: type,
                    },
                    success: function(result) {
                        if (result.status == 1) {
                            if (result.type == 1) {
                                payment(result.data);
                            } else {
                                toastr.success(result.msg)
                            }
                        } else {
                            toastr.error(result.msg)
                        }
                    }
                });
            })

            function payment(resp) {
                console.log(resp.order_id);
                // alert(resp.razorpayId);
                var options = {
                    "key": resp.razorpayId,
                    "amount": resp.amount, // Example: 2000 paise = INR 20
                    "name": "{{ !empty(getSettingData('company_name', config('constants.superadmin_role_id'), 'val')) ? getSettingData('company_name', config('constants.superadmin_role_id'), 'val') : '' }}",
                    "description": "{{ !empty(getSettingData('about', config('constants.superadmin_role_id'), 'val')) ? getSettingData('about', config('constants.superadmin_role_id'), 'val') : '' }}",

                    "image": resp.logo, // COMPANY LOGO
                    "handler": function(response) {
                        console.log(response);
                        var razorpay_id = response.razorpay_payment_id;
                        var order_id = resp.order_id;
                        var pay_method = response.method;
                        var jsondata = {
                            package_uni_id: resp.package_uni_id,
                            duration: resp.duration,
                            number: "{{ !empty(Config::get('auth_detail')['mobile']) ? Config::get('auth_detail')['mobile'] : '' }}",
                            amount: resp.amount,
                            razorpay_id: razorpay_id,
                            pay_method: pay_method,
                            order_id: order_id
                        }

                        $.ajax({
                            type: 'POST',
                            url: "{{ route('payment') }}",
                            dataType: "json",
                            data: jsondata,
                            success: function(result) {
                                if (result.status == 1) {
                                    toastr.success(result.msg)
                                } else {
                                    toastr.error(result.msg)
                                }
                            }
                        });
                    },
                    "prefill": {
                        "name": "{{ !empty(Config::get('auth_detail')['name']) ? Config::get('auth_detail')['name'] : '' }}", // pass customer name
                        // "email": resp.data.email,// customer email
                        "email": "{{ !empty(Config::get('auth_detail')['email']) ? Config::get('auth_detail')['email'] : '' }}", // customer email
                        "contact": "{{ !empty(Config::get('auth_detail')['mobile']) ? Config::get('auth_detail')['mobile'] : '' }}" //customer phone no.
                    },
                    "notes": {
                        "address": "{{ !empty(Config::get('auth_detail')['address']) ? Config::get('auth_detail')['address'] : '' }}" //customer address 
                    },
                    "theme": {
                        "color": "#15b8f3" // screen color
                    }
                };
                console.log(options);
                var rzp1 = new window.Razorpay(options);

                rzp1.open();

            }

        })
    </script>

    <script>
        function previewImage(fileClass, imgClass) {
            $(document).on('change', fileClass, function(e) {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $(imgClass).attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            });
        }
    </script>

    {{-- javascript code --}}
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyDzltJV1UcXAkH-mMzzG0OwBiNH9iWba2s&libraries=places"></script>
    <script>
        $(document).ready(function() {
            $("#lat_area").addClass("d-none");
            $("#long_area").addClass("d-none");
        });


        function initialize() {
            var autocomplete = [];
            var input = document.getElementsByClassName('autocomplete');
            for (var i = 0; i < input.length; i++) {
                autocomplete[input[i].id] = new google.maps.places.Autocomplete(input[i]);
            }
            $(document).on('click', '.autocomplete', function() {
                var id = $(this).attr('id');
                var coordinates = '#boy_coordinates';
                if (id == 'girl_location') {
                    coordinates = '#girl_coordinates';
                }

                autocomplete[id].addListener('place_changed', function() {
                    var place = this.getPlace();
                    var val = place.geometry['location'].lat() + ',' + place.geometry['location'].lng();
                    $(coordinates).val(val);
                    $('#coordinates').val(val);
                    $('#latitude').val(place.geometry['location'].lat());
                    $('#longitude').val(place.geometry['location'].lng());
                    $.each(place.address_components, function(key, value) {
                        if (Object.values(value.types).indexOf('administrative_area_level_1') > -
                            1) {
                            $('.state').val(value.long_name);
                        } else if (Object.values(value.types).indexOf(
                                'administrative_area_level_3') > -
                            1) {
                            $('.city').val(value.long_name);
                        } else if (Object.values(value.types).indexOf('country') > -1) {
                            $('.country').val(value.long_name);
                        }
                    });
                });
            });
            const myLatLng = {
                lat: {
                    {
                        !empty(Config::get('company_lat')) ? Config::get('company_lat') : '00'
                    }
                },
                lng: {
                    {
                        !empty(Config::get('company_long')) ? Config::get('company_long') : '00'
                    }
                }
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: myLatLng,
            });

            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "{{ Config::get('company_name') }}",
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
    <script>
        $(document).on('click', '.not_set', function(e) {
            toastr.info("Astrologer not set prices");
        });
        $(document).on('click', '.call-with-exotel', function(e) {
            let _token = $('meta[name="csrf-token"]').attr('content');
            var customer_id = $(this).data('customer_id');
            var astrologer_id = $(this).data('astrologer_id');
            var apikey = $(this).data('apikey');
            if (apikey != '') {
                $.ajax({
                    type: "POST",
                    url: "{{ url('api/startVoiceCallExotel') }}",
                    data: {
                        api_key: apikey,
                        astrologer_id: astrologer_id,
                        user_uni_id: customer_id,
                        _token: _token
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.status == 1) {
                            toastr.success(response.msg)
                        } else {
                            toastr.error(response.msg)
                        }
                    }
                })
            } else {
                toastr.error("Please login in panel")
            }
        });
    </script>
    <!-- new added Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper(".mySwiper", {
            slidesPerView: 4,
            spaceBetween: 40,
            slidesPerGroup: 1,
            loop: true,
            fade: true,
            grabCursor: true,
            loopFillGroupWithBlank: true,
            autoplay: {
                delay: 2300,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                275: {
                    slidesPerView: 1,
                    spaceBetween: 20,
                },
                460: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                868: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1000: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                1250: {
                    slidesPerView: 4,
                    spaceBetween: 30,
                },

            },

        });
    </script>



    @stack('front')


    <script>
        AOS.init();
    </script>

    <script>
        $(document).ready(function() {
            $(".gallery a").fancybox();
        });
    </script>


</body>

</html>