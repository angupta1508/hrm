@extends('layouts.front-user.app')
@section('content')

<main class="maindashemp">
  <section class="ms-3 d-none d-sm-block ">
    <div class="search  mx-auto d-flex mb-0">
      <!-- <span class="fa fa-search"></span> -->
      <input placeholder="Type your Keywords to search..." class="rounded-pill px-4 py-4  mb-0">

      <div class="d-flex border border-2 bgtheme ms-auto  settingicon fs-2 text-light">
        Employees
      </div>
    </div>
  </section>
  <p class="fw-semibold fs-1 text-center d-block d-sm-none">Employees</p>
  <!--filter start from here-->
  <div class="col-sm-12  my-3">
    <div class="accordion" id="accordionExamplewallet">
      <div class="accordion-item border-0">
        <h2 class="accordion-header" id="headingOnewallet">
          <button class="accordion-button text-black bgthemelight rounded-4 border fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOnewallet" aria-expanded="true" aria-controls="collapseOnewallet">
            <i class="fa-solid fa-filter mx-1 themeclr"></i><b class="themeclr fw-semibold"> Filter</b>
          </button>
        </h2>
        <div id="collapseOnewallet" class="accordion-collapse collapse" aria-labelledby="headingOnewallet" data-bs-parent="#accordionExamplewallet">
          <div class="accordion-body">
     
              <form action="{{ url('getUserData') }}"  method="GET">
                @csrf

                <div class="row  border border-2 rounded-2 p-2 align-items-center">

                  <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-12 mt-2">
                    <input type="text" id="search" name="search" class="p-3 rounded-5 w-100 bgthemelight  border-0 fw-semibold" value="{{ !empty($_GET['search']) ? $_GET['search'] : '' }}" autocomplete="off" placeholder="Employee ID" aria-expanded="false" aria-haspopup="true">
                  </div>

                  <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-12 mt-2">
                    <select name="shift_id" id="shift_id" class="p-3 rounded-5 w-100 bgthemelight border-0 fw-semibold">
                      <option value="">Please Select Shift</option>
                      @foreach ($shiftType as $shift)
                      <option value="{{ !empty($shift->shift_id) ? $shift->shift_id : '' }}" {{ (old('shift_id', !empty($filter_array['shift_id']) ? $filter_array['shift_id'] : '') == $shift->shift_id) ? 'selected' : '' }}>
                        {{ $shift->shift_name }}
                      </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-12 mt-2">
                    <select name="designation_id" id="designation_id" class="p-3 rounded-5 w-100 bgthemelight border-0 fw-semibold">
                      <option value="">Please Select Designation</option>
                      @foreach ($thismodel as $designation)
                      <option value="{{ !empty($designation->designation_id) ? $designation->designation_id : '' }}" {{ old('designation_id', !empty($filter_array['designation_id']) ? $filter_array['designation_id'] : '') == $designation->designation_id ? 'selected' : '' }}>
                        {{ $designation->name }}
                      </option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-lg-3 col-xl-3 col-md-6 col-sm-6 col-12 mt-2">
                    <button type="submit" id="filterdata" class="btn w-100 p-3 rounded-5 bgtheme mb-0 button text-light mx-auto approvebtn " name="submit">
                      Search</button>
                  </div>
                </div>
              </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="cardBox bgtheme" id="userHtml">
  </div>
  <div id="loader-icon">
    <div class="lds-ellipsis">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
    </div>
  </div>
</main>


<script>
  $(document).ready(function() {
    var offset = 0;
    var isAjaxRun = 0
    var isAjaxEnd = 0
    window.onload = getUser(0);
    $(document).on('click', '#filterdata', function(e) {
      e.preventDefault();
      offset = 0;
      getUser(1);
    })

    $(window).scroll(function() {
      if ($(window).scrollTop() == $(document).height() - ($(window).height())) {
        if (isAjaxRun == 0 && isAjaxEnd == 0) {
          getUser(0);
        }
      }
    });

    // $('#reset').click(function() { //button reset event click
    //   location.reload();
    // });

    // $('#userHtml').empty();

    function getUser(isNew = 0) {
      var search = $("#search").val();
      var shift_id = $('#shift_id').val();
      var designation_id = $('#designation_id').val();
      $.ajax({
        url: "{{ route('getUserData') }}",
        type: "POST",
        data: {
          offset: offset,
          search: search,
          shift_id: shift_id,
          designation_id: designation_id

        },
        dataType: 'json',
        beforeSend: function() {
          isAjaxRun = 1
          $('#loader-icon').show();
        },
        complete: function() {
          $('#loader-icon').hide();
        },
        success: function(result) {
          if (result.status == 1) {
            if (result.response.length == 0) {
              isAjaxEnd = 1
            }
            var html = '';
            isAjaxRun = 0

            if (isNew == 1) {
              $('#userHtml').html('');
              $('#userHtml').html(result.response);
              $('.btn-close').trigger('click');
            } else {
              $('#userHtml').append(result.response);
            }
            offset = result.offest
          } else {
            toastr.error(result.msg)
          }
        }


      });
    }
  });
</script>







<!-- new added Swiper JS -->




@endsection