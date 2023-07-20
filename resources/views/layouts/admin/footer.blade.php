<footer class="footer py-3  ">
    <div class="container">
        <div class="row">
            <div class="col-8 mx-auto text-center mt-1">

                <p class="mb-0 text-secondary">
                    ©{{ date('Y') }} <a href="{{ url('/') }}" target="_blank"> {{ config('company_name') }}</a>.
                    All rights reserved. Powered by <a href="{{ url('/') }}" target="_blank">
                        {{ config('footer') }}</a>
                </p>



                
            </div>

        </div>
    </div>
</footer>
