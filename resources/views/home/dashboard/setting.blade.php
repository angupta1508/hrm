@extends('layouts.front-admin.app')

@section('content')
    <div class="col-lg-8 me-auto card border packround py-4 rounded-5 gap-4 b-5">
        <div class="card-body pb-0">
            @if (!empty($no_change_msg))
                <div class="text-center bg-primary p-2">
                    <h6 class="text-white">{{ $no_change_msg }}</h6>
                </div>
            @endif
            <div class="table-responsive p-0">
                <form action="{{ route('settingsaved') }}" method="POST" enctype='multipart/form-data'>
                    @csrf
                    <input type="hidden" name="setting_type" value="{{ old('setting_type', $setting_type) }}">

                    <table class="table align-items-center mb-0">

                        <tbody>
                            @foreach ($settings as $setting)
                                <tr>
                                    <td class="text-center" style="width:50px;">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $setting->setting_label }}
                                        </p>
                                    </td>
                                    <td class="text-left">

                                        @if ($setting->input_type == 'file')
                                            <div class="row">
                                                <div class="col-8">
                                                    <input type="file" class="form-control"
                                                        placeholder="{{ $setting->setting_label }}"
                                                        name="setting[{{ $setting->setting_name }}]"
                                                        id="setting_{{ $setting->setting_name }}"
                                                        value="{{ old($setting->setting_name, $setting->setting_value) }}" {{ empty($setting->setting_value) ? 'required' : '' }}>
                                                </div>
                                                <div class="col-4">
                                                    <img src="{{ url(config('constants.setting_image_path') . $setting->setting_value) }}"
                                                        style="height: 50px; width: auto;">
                                                </div>
                                            </div>
                                        @elseif($setting->input_type == 'textarea')
                                            <textarea type="text" class="form-control" placeholder="{{ $setting->setting_label }}"
                                                name="setting[{{ $setting->setting_name }}]" id="setting_{{ $setting->setting_name }}">{{ old($setting->setting_name, $setting->setting_value) }}</textarea>
                                        @elseif($setting->input_type == 'slider')
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input updateStatus" type="checkbox" role="switch"
                                                    name="setting[{{ $setting->setting_name }}]" value="1"
                                                    {{ old($setting->setting_name, $setting->setting_value) == 1 ? 'checked' : '' }} required>
                                            </div>
                                        @else
                                            <input type="text" class="form-control"
                                                placeholder="{{ $setting->setting_name }}"
                                                name="setting[{{ $setting->setting_name }}]"
                                                id="setting_{{ $setting->setting_name }}"
                                                value="{{ old($setting->setting_name, $setting->setting_value) }}" required>
                                        @endif
                                        @error($setting->setting_name)
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-end mb-4">
                        <button class="border-0 py-2 bgtheme text-light subhead fw-600 rounded-4 px-5 ms-auto">Save
                            Setting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
