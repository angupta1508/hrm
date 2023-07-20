@extends('layouts.front-user.app')
@section('content')

<!-- main screen -->
<main class="maindashatten">
  <section class="">
    <div class="search  mx-auto d-flex mb-0 align-items-center">
      <!-- <span class="fa fa-search"></span> -->
      <input placeholder="Type your Keywords to search..." class="d-none d-sm-block rounded-pill px-4 py-4  mb-0">

      <div class="d-flex border border-2 d-none d-sm-block bgtheme ms-auto  settingicon fs-2 text-light">
        Leaves

      </div>

    </div>
    <p class="fw-semibold fs-1 text-center d-block d-sm-none">Leaves</p>

    <div class="mt-2">
      <a href="{{ route('employe-leave.create') }}" class="bgtheme text-light fs-6 mt-0 fw-600 border-0 p-2 rounded-5 text-decoration-none">
        ADD LEAVES <i class="fa-solid fa-plus mx-1"></i></a>
      <div>

        <div class="cardBox mt-2">
          <div class="card text-center bgtheme">
            <p class="fw-600 mb-0 fs-5">Annual Leaves</p>
            <p class="fw-600 mt-1 fs-2"></p>
          </div>
          <div class="card text-center bgtheme">
            <p class="fw-600 mb-0 fs-5">Medical Leaves</p>
            <p class="fw-600 mt-1 fs-2"></p>
          </div>

          <div class="card text-center bgtheme">
            <p class="fw-600 mb-0 fs-5">Paid Leaves</p>
            <p class="fw-600 mt-1 fs-2"></p>
          </div>

          <div class="card text-center bgtheme">
            <p class="fw-600 mb-0 fs-5">Remaining Leaves</p>
            <p class="fw-600 mt-1 fs-2"></p>
          </div>

        </div>

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
            <form action="{{ route('employe-leave.index') }}" method="GET">
              @csrf
              <div class="row border border-2 rounded-2 p-2 align-items-center">

                <div class="col-lg-3 col-xl-4 col-md-6 col-sm-6 col-12 mt-2">

                  <select name="leave_type_id" id="leave_type_id" class="w-100  p-3 rounded-5 bgthemelight border-0 fw-semibold">
                    <option value="">Leave Type</option>
                    @foreach ($leaveTyp as $leave)
                    <option value="{{ $leave->id }}" {{ collect(old('leave_type_id'))->contains($leave->id) ? 'selected' : '' }}>
                      {{ $leave->leave_type }}
                    </option>
                    @endforeach
                  </select>
                </div>

                <div class="col-lg-3 col-xl-4 col-md-6 col-sm-6 col-12 mt-2">
                  {{ Form::select('status', ['' => __('Select Status')] + config('constants.default_leave_status'), isset($filter['status']) ? $filter['status'] : '', ['class' => 'w-100  p-3 rounded-5 bgthemelight border-0 fw-semibold']) }}
                </div>
                <div class="col-lg-3 col-xl-4 col-md-6 col-sm-6 col-12 mt-2">
                  <button type="submit" class="btn w-100 p-3 rounded-5 bgtheme mb-0 button text-light mx-auto approvebtn " name="submit">
                    Search</button>

                </div>
                <div class="col-lg-3 col-xl-4 col-md-6 col-sm-6 col-12 mt-2">
                  <input type="date" name="start_date" class="w-100 p-3  rounded-5 bgthemelight border-0 fw-semibold" autocomplete="off" value="{{ !empty($filter['start_date']) ? $filter['start_date'] : '' }}" placeholder="From Date" />

                </div>
                <div class="col-lg-3 col-xl-4 col-md-6 col-sm-6 col-12 mt-2">
                  <input type="date" name="end_date" class="w-100 p-3 rounded-5 bgthemelight border-0 fw-semibold" autocomplete="off" value="{{ !empty($filter['end_date']) ? $filter['end_date'] : '' }}" placeholder="To Date" />
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
    <div class="col-lg-12 mb-2">
      <div class="table-responsive text-center">
        <table class="table table-striped custom-table mb-0">
          <thead class="bgtheme text-light fw-600 subhead">
            <tr>
              <td>Id</td>
              <td>Approved By</td>
              <td>Leave Type</td>
              <td>Request Date</td>
              <td>From</td>
              <td>To</td>
              <td>No of Days</td>
              <td>Reason</td>
              <td>Status</td>
              <td>Action</td>
            </tr>
          </thead>
          <tbody class="text-dark fw-600 subhead fst-normal">

            @foreach ($leaves as $leav)
            <tr>
              <td class="text-center" style="width:50px;">
                <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
              </td>


              <td class="text-capitalize">
                @if($leav->status == 1)
                <p class="text-xs font-weight-bold mb-0">{{ $leav->author_name }}</p>
                @endif
              </td>

              <td class="text-capitalize">
                <p class="text-xs font-weight-bold mb-0">{{ $leav->leave_type }}</p>
              </td>

              <td class="text-capitalize">
                <p class="text-xs font-weight-bold mb-0">{{ $leav->request_date }}</p>
              </td>
              <td class="text-capitalize">
                <p class="text-xs font-weight-bold mb-0">{{ $leav->request_start_date }}</p>
              </td>

              <td class="text-capitalize">
                <p class="text-xs font-weight-bold mb-0">{{ $leav->request_end_date }}</p>
              </td>

              <td class="text-capitalize">
                @if ($leav->status == 0)
                <p class="text-xs font-weight-bold mb-0">{{ $leav->request_day }}</p>
                @elseif($leav->status == 1)
                <p class="text-xs font-weight-bold mb-0">{{ $leav->approve_day }}</p>
                @endif
              </td>

              <td class="text-capitalize">
                <p class="text-xs font-weight-bold mb-0">{{ $leav->request_remark }}</p>
              </td>

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

              @if($leav->status != 1)
              <td class="text-end">
                <form action="{{ route('employe-leave.destroy', $leav->id) }}" method="Post">
                  <a class="btn btn-primary btn-sm text-white" title="{{ __('Edit') }}" data-toggle="tooltip" href="{{ route('employe-leave.edit', $leav->id) }}">
                    <i class="fas fa-edit"></i>
                  </a>
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-danger btn-sm text-white delete_confirm" title="{{ __('Delete') }}" data-toggle="tooltip"><i class="cursor-pointer fas fa-trash"></i></button>
                </form>
              </td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="mt-2">
        {{ $leaves->appends($filter)->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </div>
</main>


</div>

</div>
</div>
</div>

@endsection('content')