@extends('layouts.admin.app')
@section('content')

<!-- Admin Dashboard  -->
@if (Config::get('auth_detail')['role_id']  ==  config('constants.admin_role_id'))

<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Employee</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $data->total_employee }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-single-02 text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Employee</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $data->total_active_employee }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-basket text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Today Present</p>
                            <h5 class="font-weight-bolder mb-0"> {{ $data->today_present }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-circle-08 text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Today Absent</p>
                            <h5 class="font-weight-bolder mb-0"> {{ $data->today_absent }}
                             </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="fas fa-store text-white opacity-10 mt-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Male</p>
                            <h5 class="font-weight-bolder mb-0"> {{ $data->total_male }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-circle-08 text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Female</p>
                            <h5 class="font-weight-bolder mb-0"> {{ $data->total_female }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-circle-08 text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Today Leave</p>
                            <h5 class="font-weight-bolder mb-0"> {{ $data->today_leave }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                        <i class="ni ni-basket text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card z-index-2">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex inline-block align-items-center justify-content-between">
                            <div class="inline-block">
                                <h6 class="mb-0 ">Employee Present</h6>
                            </div>
                            <div class="d-flex">
                                <select name="userregistors" id="userregistors" class="form-select"
                                    style="margin-right: 10px; padding: 8px 25px">
                                    <option value="week">Last 7 Days</option>
                                    <option value="thismonth">This Month</option>
                                    <option value="lastmonth">Last Month</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <canvas id="chart-line" class="chart-canvas" height="300" width="555"
                                style="display: block; box-sizing: border-box; height: 300px; width: 555px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SuperAdmin Dashboard  -->
@elseif (Config::get('auth_detail')['role_id']  ==  config('constants.superadmin_role_id'))

<div class="row">
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Admin</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $data->total_admin }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-single-02 text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Active Admin</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $data->total_active_admin }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-basket text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Package</p>
                            <h5 class="font-weight-bolder mb-0">
                                {{ $data->total_active_package }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                        <i class="ni ni-single-02 text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6 mb-xl-0 mt-4">
        <div class="card">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Package Revenue</p>
                            <h5 class="font-weight-bolder mb-0">
                             {{ $currency }} {{ $data->total_Package_revenue }}
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                            <i class="ni ni-basket text-lg opacity-10"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
     <div class="col-lg-12">
        <div class="card z-index-2">
            <div class="card h-100">
                <div class="card-header pb-0 p-3">
                    <div class="d-flex inline-block align-items-center justify-content-between">
                        <div class="inline-block">
                            <h6 class="mb-0 ">Package Revenue</h6>
                            <p class="text-sm " style="display:inline-block">
                            </p>
                        </div>
                        <div class="d-flex">
                            <select name="incomeover" id="incomeover" class="form-select" style="margin-right: 10px; padding: 8px 25px">
                                <option value="week">Last 7 Days</option>
                                <option value="thismonth">This Month</option>
                                <option value="lastmonth">Last Month</option>
                                <option value="yeardata">Last Year</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="chart-line2" class="chart-canvas" height="300" width="555" style="display: block; box-sizing: border-box; height: 300px; width: 555px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
     </div>
     </div>
</div>

@endif
@push('dashboard')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Admin Dashboard  -->
<script>
        var ctx2 = document.getElementById("chart-line").getContext("2d");
        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors
        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        var adminincomechat = new Chart(ctx2, {
            type: "line",
            data: {
                labels:'',
                datasets: [{
                        label: "Employee Present",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data:'',
                        maxBarThickness: 6

                    },

                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
        // User Registors Chat
        $(document).ready(function () {
            $('#userregistors').on('change', function () {
                let _token = $('meta[name="csrf-token"]').attr('content');
                var type = $(this).val();
                $.ajax({
                    url: "{{ route('admin.users.userGraphStatus') }}",
                    type: 'post',
                    data: {
                        _token: _token,
                        type: type,
                    },
                    success: function (result) {
                        if (result.status == 1) {
                            adminincomechat.data.labels = result.data['key'];
                            adminincomechat.data.datasets[0].data = result.data[
                                'total_count'];
                            adminincomechat.update();
                            // toastr.success(result.msg);
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            })
        })
        $(function () {
            $('#userregistors').trigger('change');
        });
        

</script>

<!-- SuperAdmin Dashboard  -->
<script>
     var ctx2 = document.getElementById("chart-line2").getContext("2d");
        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors
        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);
        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        var adminincomechat = new Chart(ctx2, {
            type: "line",
            data: {
                labels:'',
                datasets: [
                    {
                        label: "Package Revenue",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data:'',
                        maxBarThickness: 6

                    }

                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Open Sans",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
      // adminincomechat
        $(document).ready(function() {
            $('#incomeover').on('change', function() {
                let _token = $('meta[name="csrf-token"]').attr('content');
                var type = $(this).val();
                $.ajax({
                    url: "{{ route('admin.users.adminGraphStatus') }}",
                    type: 'post',
                    data: {
                        _token: _token,
                        type: type,
                    },
                    success: function(result) {
                   
                        if (result.status == 1) {
                            adminincomechat.data.labels = result.data['key'];
                            adminincomechat.data.datasets[0].data = result.data['admin_amount'];
                            adminincomechat.update();
                            // toastr.success(result.msg);
                        } else {
                            toastr.error(result.msg);
                        }
                    }
                });
            })
        })
        $(function() {
            $('#incomeover').trigger('change');
        });
</script>
@endpush
@endsection
