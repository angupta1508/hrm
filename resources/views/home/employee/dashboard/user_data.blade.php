
@foreach ($users as $value)
<div class="card text-center">
  <div class="text-end dropstart">
    <button class="btn text-bg-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-ellipsis-v"></i>
    </button>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="{{ route('getUserLocation', $value->id) }}">
      <i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp; Location
      </a></li>
      <li><a class="dropdown-item" href="{{ route('userDetail', $value->id) }}">
      <i class="fa fa-user" aria-hidden="true"></i>&nbsp; View Profile
      </a></li>
    </ul>
  </div>
  <div class="card__border">
    <img class="empcard__img rounded-circle" src="{{ $value->profile_image  }}" alt="card image empimg">
  </div>
  <h3 class="empcard__name themeclr mb-0 mt-2 fs-5 fw-semibold">{{$value->name}}</h3>
  <span class="card__profession themeclr">{{$value->designation}}</span>
</div>

@endforeach   