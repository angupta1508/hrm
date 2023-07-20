@extends('layouts.admin.app')

@section('content')
    <main class="main-content mt-0">
        <section>
            <div class="page-header min-vh-75">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                            <div class="card">
                                <div class="card-header pb-0 text-center bg-transparent">
                                    <img class="h-10" height="100" src="{{ URL::to('/assets/img/logo2.png') }}">
                                    <h5 class="font-weight-bolder  text-info text-gradient">Recharge Your Wallet</h5>

                                </div>
                                {{-- action="{{ route('admin.recharge.store') }}" --}}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="role_id" class="form-label">Package</label>
                                            <div class="">
                                                <select class="form-select" id="package" name="package"
                                                    aria-label="package" aria-describedby="package">
                                                    <option value="">Please Select Package</option>
                                                    @foreach ($package as $pack)
                                                        <option value="{{ $pack->package_uni_id }}"
                                                            {{ collect(old('package'))->contains($pack->id) ? 'selected' : '' }}>
                                                            {{ $pack->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('role_id')
                                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="phone" class="mt-4">Phone</label>
                                            <div class="">
                                                <input type="number" class="form-control phone" placeholder="Phone"
                                                    name="phone" id="phone" aria-label="Phone"
                                                    aria-describedby="phone-addon" value="{{ old('phone') }}">
                                                @error('phone')
                                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="d-flex justify-content-end mt-4">
                                            <button
                                                class="btn bg-gradient-primary  updateStatus m-0 ms-2 text-uppercase">Recharge</button>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        @include('layouts.alert_message')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @push('dashboard')
        <script>
            $(document).ready(function() {
                $('.updateStatus').on('click', function() {
                    // alert('dfsd');
                    let package = $('#package').val();
                    let phone = $('#phone').val();
                    $.ajax({
                        url: "{{ route('admin.recharge.store') }}",
                        type: 'post',
                        data: {
                            package: package,
                            phone: phone,
                        },
                        success: function(result) {
                            if (result.status == 1) {
                                payment(result.data);
                                // console.log(result);
                            }else{
                               alert(result.msg); 
                            }
                        }
                    });
                })
            })


            function payment(resp) {
                console.log(resp.order_id);
                // alert(resp.razorpayId);
                var options = {
                    "key": resp.razorpayId,
                    "amount": resp.amount, // Example: 2000 paise = INR 20
                    "name": "Synilogic Tech",
                    "description": "Synilogic Tech is a website development company",
                    "image": "{{ URL::to('/assets/img/logo2.png') }}", // COMPANY LOGO
                    "handler": function(response) {
                        console.log(response);
                        var razorpay_id = response.razorpay_payment_id;
                        var order_id = resp.order_id;
                        var pay_method = response.method;
                        var jsondata = {
                            package_id: resp.package_id,
                            duration: resp.duration,
                            number: resp.phone,
                            amount: resp.amount,
                            razorpay_id: razorpay_id,
                            pay_method: pay_method,
                            order_id: order_id
                        }
                        
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('admin.recharge.payment') }}",
                            dataType: "json",
                            data: jsondata,
                            success: function(response) {
                                if (response.status == 1) {
                                    alert(response.msg);
                                    window.location = '{{ route('admin.login') }}';
                                } else {
                                    alert("Server not responding");
                                }
                            }
                        });
                    },
                    "prefill": {
                        // "name": "ABC", // pass customer name
                        // "email": resp.data.email,// customer email
                        "email": resp.email,// customer email
                        "contact": resp.phone //customer phone no.
                    },
                    "notes": {
                        "address": "address" //customer address 
                    },
                    "theme": {
                        "color": "#15b8f3" // screen color
                    }
                };
                var rzp1 = new window.Razorpay(options);

                rzp1.open();

            }
        </script>
    @endpush
@endsection
