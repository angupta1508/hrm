<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="apple-touch-icon" sizes="76x76"
        href="{{ url(config('constants.setting_image_path') . config('website_favicon')) }}">
    <link rel="icon" type="image/png"
        href="{{ url(config('constants.setting_image_path') . config('website_favicon')) }}">
    <title>
        {{ Config::get('company_name') }}
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <script src="{{ asset('plugins/fontawesome-free/js/all.min.js') }}" crossorigin="anonymous">
    </script>
    <!-- dropzonejs -->
    <link href="{{ asset('plugins/dropzone/min/dropzone.min.css') }}" rel="stylesheet">
    <!-- select2 -->
    <link href="{{ asset('plugins/select2/css/select2.min.css') }}" rel="stylesheet">



    <link href="{{ asset('plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet">



    <!-- CSS Files -->
    <!--<link href="{{ asset('plugins/lightbox/lightbox.min.css') }}" rel="stylesheet" >-->
    <link href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.3') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/tree.css') }}" rel="stylesheet">
    <!-- <link href="{{ asset('plugins/fancybox/fancybox4.min.css') }}" rel="stylesheet" /> -->
    <link href="{{ asset('plugins/toastr/toastr.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css" />

    <!--fancy box-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css"
        type="text/css" media="screen" />
    <!--fancy box-->

    <script type="text/javascript">
        var backArrow = "{{ asset('assets/img/back-arrow.svg') }}"
        var no_found = "{{ asset('front/img/no-found.jpg') }}"
        var alreadt = []

        function onImgError(ele, fileName) {
            if (alreadt.includes(fileName)) {
                ele.src = no_found;
            } else {
                ele.src = fileName;
                alreadt.push(fileName)
            }
            //                console.log(fileName)
            ele.src = no_found;
        }

    </script>
</head>

<body
    class="g-sidenav-show bg-gray-100 {{ Request::is('admin/register') || Request::is('admin/login') || Request::is('admin/forgot-password') ? 'login-signup-page' : '' }} {{ \Request::is('rtl') ? 'rtl' : (Request::is('virtual-reality') ? 'virtual-reality' : '') }} ">
    @auth
        @include('layouts.admin.sidebar')
        <main
            class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg {{ Request::is('rtl') ? 'overflow-hidden' : '' }}">
            @include('layouts.admin.nav')
            <div class="container-fluid py-4 min-height-vh-100">
                @include('layouts.alert_message')
                @yield('content')
                @include('layouts.admin.footer')
            </div>
        </main>


    @endauth
    @guest
        @yield('content')

        @include('layouts.admin.footer')
    @endguest




    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <!--   Core JS Files   -->

    <script src="{{ asset('plugins/summernote/summernote-lite.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}">
    </script>
    {{-- <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}">
    </script> --}}
    <script src="{{ asset('plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    {{-- <script src="{{ asset('plugins/moment.min.js') }}"
    type="text/javascript"></script> --}}

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.3') }}"></script>
    <!-- <script src="{{ asset('plugins/fancybox/fancybox4.min.js') }}"></script> -->
    <script src="{{ asset('plugins/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset('assets/js/intlTelInput-jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/chartjs.min.js') }}"></script>



    <!--fancy box-->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js">
    </script>
 


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    </script>
    <script type="text/javascript">
        $(document).on('click', '.toggle-password', function () {

            $(this).toggleClass("fa-eye fa-eye-slash");

            var input = $("#password");
            input.attr('type') === 'password' ? input.attr('type', 'text') : input.attr('type', 'password')
        });

        //tree_label/
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip()
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: 'dd M, yyyy'
            })
            $('.summernote').summernote({
                height: 100
            });

            $(".datepick").datepicker({
                viewMode: 'years',
                format: 'MM yyyy'
            });

            $(".tree_label").click(function (event) {
                $(this).find('.tree_icon').toggleClass("fa-folder fa-folder-open");
            })

            var searchIDs;
            $(".tree_input").change(function (event) {
                searchIDs = $("input.tree_input:checkbox:checked").map(function () {
                    return this.id;
                }).get(); // <----
                sessionStorage.setItem("searchIDs", searchIDs); //store colors
            });


            $('.table-responsive').on('hide.bs.dropdown', function () {
                $('.table-responsive').css("overflow", "auto");
            })
            $('.table-responsive').on('show.bs.dropdown', function () {
                $('.table-responsive').css("overflow", "inherit");
            });

            searchIDs = sessionStorage.getItem("searchIDs");
            if (searchIDs) {
                $(searchIDs.split(',')).map(function (key, value) {
                    $('.tree_label[for="' + value + '"]').trigger('click')
                });
            }
            $('.country_dropdown').on('change', function () {
                var country_id = this.value;
                $(".state_dropdown").html('<option value="">Select State</option>');
                $.ajax({
                    url: "{{ route('getState') }}",
                    type: "POST",
                    data: {
                        country_id: country_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function (result) { 
                        $.each(result.states, function (key, value) {

                            $(".state_dropdown").append('<option value="' + key +
                                '">' + value + '</option>');
                        });
                    }
                });
            });
            var isAjaxRun = 0
            var isAjaxEnd = 0
            $('.state_dropdown').on('change', function () {
                var state_id = this.value;
                $(".city_dropdown").html('<option value="">Select City</option>');
                $.ajax({
                    url: "{{ route('getCity') }}",
                    type: "POST",
                    data: {
                        state_id: state_id,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        isAjaxRun = 1
                        $('#loader-icon').show();
                    },
                    complete: function () {
                        $('#loader-icon').hide();
                    },
                    success: function (result) {
                        if (result.cities.length == 0) {
                            isAjaxEnd = 1
                        }
                        isAjaxRun = 0
                        $.each(result.cities, function (key, value) {
                            $(".city_dropdown").append('<option value="' + key +
                                '">' + value + '</option>');
                        });
                    }
                });
            });
            // $('.stateee').trigger('change');
            // delete script
            $('.delete_confirm').click(function (event) {
                var form = $(this).closest("form");
                console.log(form)
                event.preventDefault();
                Swal.fire({
                    title: "{{ __(`Are you sure you want to delete this record?`) }}",
                    text: "{{ __('If you delete this, it will be gone forever.') }}",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "{{ __('Yes, delete it!') }}",
                    cancelButtonText: "{{ __('No, cancel!')}}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });

            // User Infomation out
            $(document).on('mouseout', ".user_info", function () {
                $('.table-responsive').css("overflow", "auto");
            });

            // User Information hover
            $(document).on('mouseenter', ".user_info", function () {
                let ele = $(this);
                var user = $(this).data('user_uni_id');
                $.ajax({
                    url: "{{ route('admin.users.getUserDetail') }}",
                    type: 'post',
                    data: {
                        _token: '{{ csrf_token() }}',
                        user: user,
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        $('.table-responsive').css("overflow", "inherit");
                    },
                    success: function (result) {
                        // console.log(result)
                        if (result.status == 1) {
                            var html = '';
                            html += '<b>Name:</b> ' + result.data.name + ' <br>';
                            html += '<b>UserName:</b> ' + result.data.email + '<br>';
                            html += '<b>Mobile:</b> ' + result.data.mobile + '<br>';
                            html += '<b>Email:</b> ' + result.data.email + '<br>';
                            html += '<b>Company:</b> ' + result.data.company_name + '<br>';
                            html += '<b>Department:</b> ' + result.data.
                            department_name + '<br>';
                            html += '<b>Designation:</b> ' + result.data.designation_name +'<br>';
                            html += '<b>Shift:</b> ' + result.data.shift_name + '<br>';
                            html += '<b>Location:</b> ' + result.data.location_name +'<br>';

                            if (ele.find('.user_info_tooltip').length > 0) {
                                ele.find('.user_info_tooltip').html(html);
                            } 
                            else 
                            {
                                ele.append('<span class="user_info_tooltip">' + html +
                                    '</span>');
                            }
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            })


            var input = document.querySelectorAll(".intlinput");

            if (input.length > 0) {
                for (var i = 0; i < input.length; i++) {
                    window.intlTelInput(input[i], {
                        autoPlaceholder: "off",
                        dropdownContainer: document.body,
                        formatOnDisplay: false,
                        geoIpLookup: function (callback) {
                            $.get("http://ipinfo.io", function () {}, "jsonp").always(function (
                                resp) {
                                var countryCode = (resp && resp.country) ? resp.country :
                                    "";
                                callback(countryCode);
                            });
                        },
                        hiddenInput: input[i].name,
                        nationalMode: false,
                        placeholderNumberType: "MOBILE",
                        preferredCountries: ["in"],
                        separateDialCode: true,
                        utilsScript: "{{ asset('assets/js/utils.js') }}"
                    });
                }
            }
        });

    </script>

    <script src="https://maps.google.com/maps/api/js?key=AIzaSyDzltJV1UcXAkH-mMzzG0OwBiNH9iWba2s&libraries=places">
    </script>

    <script>
        $(document).ready(function () {
            $("#lat_area").addClass("d-none");
            $("#long_area").addClass("d-none");
        });

    </script>

    <script>
        google.maps.event.addDomListener(window, 'load', initialize);

        function initialize() {
            var input = document.getElementById('autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.addListener('place_changed', function () {
                var place = autocomplete.getPlace();
                $.each(place.address_components, function (key, value) {
                    if (Object.values(value.types).indexOf('administrative_area_level_1') > -1) {
                        $('.state').val(value.long_name);
                    } else if (Object.values(value.types).indexOf('administrative_area_level_3') > -1) {
                        $('.city').val(value.long_name);
                    } else if (Object.values(value.types).indexOf('country') > -1) {
                        $('.country').val(value.long_name);
                    }
                });
                $('#latitude').val(place.geometry['location'].lat());
                $('#longitude').val(place.geometry['location'].lng());
                // --------- show lat and long ---------------
                // $("#lat_area").removeClass("d-none");
                // $("#long_area").removeClass("d-none");
            });
        }

    </script>

    <script>
        function previewImage(fileClass, imgClass) {
            $(document).on('change', fileClass, function (e) {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $(imgClass).attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });
        }

    </script>

    <script>
        $(document).ready(function () {
            $(".gallery a").fancybox();
        });

    </script>


    @stack('dashboard')


    <!-- Page specific script -->
</body>

</html>
