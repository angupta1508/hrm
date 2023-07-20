@extends('layouts.front-user.app')
@section('content')

<!-- main screen -->
<main class="maindashatten">
  <section class="">
    <div class="search  mx-auto d-flex mb-0 align-items-center">
      <!-- <span class="fa fa-search"></span> -->
      <input placeholder="Type your Keywords to search..." class="d-none d-sm-block rounded-pill px-4 py-4  mb-0">

      <div class="d-flex border border-2 d-none d-sm-block bgtheme ms-auto  settingicon fs-2 text-light">
        Regularisition

      </div>

    </div>
    <p class="fw-semibold fs-1 text-center d-block d-sm-none">Regularisition</p>

    <a href="{{ route('attendance-regularise.create') }}" class="text-decoration-none"> <button class="bgtheme text-light fs-6 mt-0 fw-600 border-0 p-2 rounded-5">ADD Manual Attendence<i class="fa-solid fa-plus mx-1"></i></button></a>

  </section>

  <!--filter start from here-->
  <div class="col-sm-12  my-3">
    <div class="accordion" id="accordionExamplewallet">
      <div class="accordion-item border-0">
        <h2 class="accordion-header" id="headingOnewallet">
          <button class="accordion-button text-black bgthemelight rounded-4 border fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOnewallet" aria-expanded="true" aria-controls="collapseOnewallet">
            <i class="fa-solid fa-filter mx-1 themeclr"></i><b class="themeclr fw-semibold"> Filter</b>
          </button>
        </h2>
        <div id="collapseOnewallet" class="accordion-collapse collapse @if (!empty($filter)) show @endif" aria-labelledby="headingOnewallet" data-bs-parent="#accordionExamplewallet">
          <div class="accordion-body">
        
            <form action="{{ route('attendance-regularise.index') }}" method="GET">
              @csrf
              <div class="row border border-2 rounded-2 p-2 align-items-center">
                <div class="col-lg-3 col-xl-6 col-md-6 col-sm-6 col-12 mt-2">
                  <input type="date" name="from_time" class="w-100 p-3  rounded-5 bgthemelight border-0 fw-semibold" autocomplete="off" value="{{ !empty($filter['from_time']) ? $filter['from_time'] : '' }}" placeholder="From Date" />
                </div>
                <div class="col-lg-3 col-xl-6 col-md-6 col-sm-6 col-12 mt-2">
                  <input type="date" name="to_time" class="w-100 p-3 rounded-5 bgthemelight border-0 fw-semibold" autocomplete="off" value="{{ !empty($filter['to_time']) ? $filter['to_time'] : '' }}" placeholder="To Date" />
                </div>

                <div class="col-lg-3 col-xl-6 col-md-6 col-sm-6 col-12 mt-2">
                  {{ Form::select('status', ['' => __('Select Status')] + config('constants.default_leave_status'), isset($filter['status']) ? $filter['status'] : '', ['class' => 'w-100  p-3 rounded-5 bgthemelight border-0 fw-semibold']) }}
                </div>
                <div class="col-lg-3 col-xl-6 col-md-6 col-sm-6 col-12 mt-2">
                  <button type="submit" class="btn w-100 p-3 rounded-5 bgtheme mb-0 button text-light mx-auto approvebtn " name="submit">
                    Search</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!--table start from here-->
  <div class="row">
    <div class="col-lg-12 mb-2 ">
      <div class="table-responsive text-center">
        <table class="table table-striped custom-table mb-0 ">
          <thead class="bgtheme text-light fw-600 subhead">
            <tr>
              <td>S.No</td>
              <td>Approved By</td>
              <td>Attendence Date</td>
              <td>Punch In Time</td>
              <td>Punch Out Time</td>
              <td>Reason</td>
              <td>Status</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody class="text-dark fw-600 subhead fst-normal">
            @foreach ($attendance as $leav)
            <tr class="text-dark">
            <td>{{ ++$i }}</td>
              <td class="text-truncate">
                <ul class="list-unstyled order-list">
                  <li class="team-member team-member-sm">
                    @if($leav->status == 1)
                    <p class="text-xs font-weight-bold">{{ $leav->author_name }}</p>
                    @endif
                  </li>
                </ul>
              </td>

              <td>{{ $leav->attendance_date  }}</td>
              <td>{{ $leav->from_time  }}</td>
              <td>{{ $leav->to_time  }}</td>

              <td>{{ $leav->request_remark }}</td>

              <td class="text-center">
                <p class="text-xs font-weight-bold mb-0">
                  @if ($leav->status == 0)
                  <span class="updateStatus badge bg-info text-white">Pending</span>
                  @elseif($leav->status == 1)
                  <span class="badge badge-pill  bg-success">Approve</span>
                  @elseif($leav->status == 2)
                  <span class="badge badge-pill bg-danger">Cancel</span>
                  @endif
                </p>
              </td>

              <td class="text-center">
                <form action="{{ route('attendance-regularise.destroy', $leav->id) }}" method="Post">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-danger btn-sm text-white delete_confirm" title="{{ __('Delete') }}" data-toggle="tooltip"><i class="cursor-pointer fas fa-trash"></i></button>
                </form>
              </td>

            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-3 px-3">
        {{ $attendance->appends($filter)->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</main>


@endsection