@if (\Session::has('success'))
<div class="alert alert-success alert-dismissible text-white">
    <p>{{ \Session::get('success') }}</p>
</div><br />
@endif
@if (\Session::has('error'))
<div class="alert alert-danger alert-dismissible text-white">
    <strong>Whoops!</strong> {{ \Session::get('error') }}
</div>
@endif
@if (count($errors) > 0)
<div class="alert alert-danger alert-dismissible text-white">
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif