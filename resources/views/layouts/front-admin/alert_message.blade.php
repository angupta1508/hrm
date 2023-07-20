<script>
    $(document).ready(function() {
        var success = "{{ \Session::has('success') }}";
        var error = "{{ \Session::has('error') }}";
        if (success != '') {
            var successHtml = "{{ \Session::get('success') }}";
            toastr.success(successHtml)
        }

        if (error != '') {
            var errorHtml = "<strong>Whoops!</strong> " + "{{ \Session::get('error') }}";
            toastr.error(errorHtml)
        };
        @if (count($errors) > 0)
            var multiErrorHtml = '';
            multiErrorHtml +=
                '<div class="text-white"><strong>Whoops!</strong> There were some problems with your input.<br><br><ul>';
            @foreach ($errors->all() as $error)
                multiErrorHtml += '<li> {{ $error }}  </li>';
            @endforeach
            multiErrorHtml += '</ul></div>';
            toastr.error(multiErrorHtml)
        @endif
    });
</script>
