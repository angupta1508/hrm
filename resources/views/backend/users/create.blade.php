@extends('layouts.admin.app')
@section('content')
<div class="card card-default">
    <form method="POST" enctype="multipart/form-data"
        action="{{ Request::routeIs('admin.' . $routeSlug . '.create') ? route('admin.' . $routeSlug . '.store') : route('admin.users.store') }}">
        @csrf
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex flex-row justify-content-between">
                    <div>
                        <h5 class="mb-0">
                            {{__('Add New User')}}
                        </h5>
                    </div> 
                </div>
            </div>
            <div class="card-body">

                <div class="row">
                    @if($routeId == config('constants.admin_role_id'))
                        <div class="col-md-12">
                            <label for="role_id" class="form-label">{{__('Package')}}</label>
                            <div class="">
                                <select class="form-select select2" id="package" name="package" aria-label="package"
                                    aria-describedby="package">
                                    <option value="">{{__('Please Select Package')}}</option>
                                    @foreach($package as $pack)
                                        <option value="{{ $pack->package_uni_id }}"
                                            {{ collect(old('package'))->contains($pack->package_uni_id) ? 'selected' : '' }}>
                                            {{ $pack->name }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4">
                        @if(!empty($routeId))
                            <input type="hidden" value="{{ $routeId }}" name="role_id">
                        @else
                            <label for="role_id" class="form-label">{{__('Role')}}</label>
                            <div class="">
                                {{ Form::select('role_id', ['' => __('Select Role')] + $role_list, old('role_id'), ['class' => 'form-select']) }}
                                @error('role_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-4">
                        <label for="name" class="form-label">{{__('Name')}}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{__('Name')}}" name="name" id="name"
                                aria-label="Name" aria-describedby="name" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="email" class="form-label">{{__('Email')}}</label>
                        <div class="">
                            <input type="email" class="form-control" placeholder="{{__('Email')}}" name="email" id="email"
                                aria-label="Email" aria-describedby="email-addon"
                                value="{{ old('email') }}">
                            @error('email')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    @if($routeId == config('constants.admin_role_id'))
                        <div class="col-md-4">
                            <label for="company_code" class="form-label">{{__('Company Code')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Company Code')}}" name="company_code"
                                    id="company_code" aria-label="company_code" aria-describedby="company_code-addon"
                                    value="{{ old('company_code') }}">
                                @error('company_code')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4">
                        <label for="username" class="form-label">{{__('Username')}}</label>
                        <div class="">
                            <input type="username" class="form-control" placeholder="{{__('Username')}}" name="username"
                                id="username" aria-label="Username" aria-describedby="username-addon"
                                value="{{ old('username') }}">
                            @error('username')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="mobile" class="form-label">{{__('Mobile')}}</label>
                        <div class="">
                            <input type="text" class="form-control intlinput" placeholder="{{__('Mobile')}}" name="mobile"
                                id="mobile" value="{{ old('mobile') }}">
                            @error('mobile')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="password" class="form-label">{{__('Password')}}</label>
                        <div class="">
                            <input type="password" class="form-control" placeholder="{{__('Password')}}" name="password"
                                id="password" aria-label="Password" aria-describedby="password-addon">
                            @error('password')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="gender" class="form-label">{{__('Gender')}}</label>
                        <div class="">
                            {{ Form::select('gender', ['' =>__('Select Gender')] + getListTranslate(config('constants.gender_list')), old('gender'), ['class' => 'form-select']) }}
                            @error('gender')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4"> 
                        <label for="country_id" class="form-label">{{__('Country')}}</label>
                        <div>
                            {{ Form::select('country_id', ['' => __('Select Country')] + $country_list, old('country_id'), ['class' => 'formcol-md-4-select select2 country_dropdown']) }}
                            @error('country_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        
                        <label for="state_id" class="form-label">{{__('State')}}</label>
                        <div>
                            {{ Form::select('state_id', ['' => __('Select State')] + $state_list, old('state_id'), ['class' => 'form-select select2 state_dropdown']) }}
                            @error('state_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror   
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label for="city_id" class="form-label">{{__('City')}}</label>
                        <div>
                            {{ Form::select('city_id', ['' => __('Select City')] + $city_list, old('city_id'), ['class' => 'form-select select2 city_dropdown']) }}
                            <div id="loader-icon">
                                <div class="lds-ellipsis">
                                    <div></div>
                                </div>
                            </div>
                            @error('city_id')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div
                        class={{ $routeId == config('constants.admin_role_id') ? 'col-md-4' : 'col-md-6 mt-2' }}>
                        <label for="address" class="form-label">{{__('Address')}}</label>
                        <div class="">
                            <input type="text" class="form-control" placeholder="{{__('Address')}}" name="address" id="address"
                                aria-label="address" aria-describedby="address-addon"
                                value="{{ old('address') }}">
                            @error('address')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div
                        class="{{ $routeId == config('constants.admin_role_id') ? 'col-md-4' : 'col-md-6 mt-2' }}">
                        <label for="pincode" class="form-label">{{__('Pincode')}}</label>
                        <div class="">
                            <input type="number" class="form-control" placeholder="{{__('Pincode')}}" name="pincode" id="pincode"
                                value="{{ old('pincode') }}">
                            @error('pincode')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label for="profile_image" class="form-label">{{__('Images')}}  </label>
                        <div class="">
                            <input type="file" class="form-control" placeholder="{{__('profile_image')}} " name="profile_image"
                                id="profile_image">
                            @error('profile_image')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            @if($routeId == config('constants.employee_role_id'))
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">
                            {{__('Employee Profile')}}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="father_name" class="form-label">{{__('Father Name')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Father Name')}}" name="father_name"
                                    id="father_name" aria-label="father_name" aria-describedby="father_name"
                                    value="{{ old('father_name') }}">
                                @error('father_name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-4">
                            <label for="mother_name" class="form-label">{{__('Mother Name')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Mother Name')}}" name="mother_name"
                                    id="mother_name" aria-label="mother_name" aria-describedby="mother_name"
                                    value="{{ old('mother_name') }}">
                                @error('mother_name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="alternate_mobile" class="form-label">{{__('Emergency Contact')}} </label>
                            <div class="">
                                <input type="text" class="form-control intlinput" placeholder="{{__('Emergency Contact')}} "
                                    name="alternate_mobile" id="alternate_mobile" aria-label="alternate_mobile"
                                    aria-describedby="alternate_mobile"
                                    value="{{ old('alternate_mobile') }}">
                                @error('alternate_mobile')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="dob" class="form-label">{{__('DOB')}}</label>
                            <div class="">
                                <input type="date" class="form-control" placeholder="{{__('DOB')}}" name="dob" id="dob"
                                    aria-label="dob" aria-describedby="dob" value="{{ old('dob') }}">
                                @error('dob')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="religion">{{__('Religion')}}</label>
                            <div class="">
                                <select class="form-select select2" id="religion" name="religion" aria-label="religion"
                                    aria-describedby="religion">
                                    <option value="">{{__('Please Select Religion')}}</option>
                                    <option value="hindu"
                                        {{ old('religion') == 'hindu' ? 'selected' : '' }}>
                                        {{__('Hindu')}}
                                    </option>
                                    <option value="muslim"
                                        {{ old('religion') == 'muslim' ? 'selected' : '' }}>
                                        {{__('Muslim')}}
                                    </option>
                                    <option value="christian"
                                        {{ old('religion') == 'christian' ? 'selected' : '' }}>
                                        {{__('Christian')}}
                                    </option>
                                    <option value="sikh"
                                        {{ old('religion') == 'sikh' ? 'selected' : '' }}>
                                        {{__('Sikh')}}
                                    </option>
                                    <option value="buddhist"
                                        {{ old('religion') == 'buddhist' ? 'selected' : '' }}>
                                        {{__('Buddhist')}}
                                    </option>
                                    <option value="jain"
                                        {{ old('religion') == 'jain' ? 'selected' : '' }}>
                                        {{__('Jain')}}
                                    </option>
                                    <option value="other"
                                        {{ old('religion') == 'other' ? 'selected' : '' }}>
                                        {{__('Other')}}
                                    </option>
                                </select>
                                @error('religion')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="marital_status">{{__('Marital Status')}}</label>
                            <div class="">
                                <select class="form-select select2" id="marital_status" name="marital_status"
                                    aria-label="marital_status" aria-describedby="marital_status">
                                    <option value="">{{__('Please Select Marital Status')}}</option>
                                    <option value="single"
                                        {{ old('marital_status') == 'single' ? 'selected' : '' }}>
                                        {{__('Single')}}
                                    </option>
                                    <option value="married"
                                        {{ old('marital_status') == 'married' ? 'selected' : '' }}>
                                        {{__('Married')}}
                                    </option>
                                    <option value="divorced"
                                        {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>
                                        {{__('Divorced')}}
                                    </option>
                                    <option value="widowed"
                                        {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>
                                        {{__('Widowed')}}
                                    </option>
                                    <option value="other"
                                        {{ old('marital_status') == 'other' ? 'selected' : '' }}>
                                        {{__('Other')}}
                                    </option>
                                </select>
                                @error('marital_status')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="spouse_name" class="form-label">{{__('Supouse Name')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Supouse Name')}}" name="spouse_name"
                                    id="spouse_name" aria-label="spouse_name" aria-describedby="spouse_name"
                                    value="{{ old('spouse_name') }}">
                                @error('spouse_name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="anniversary_date" class="form-label">{{__('Date of Marriage')}}</label>
                            <div class="">
                                <input type="date" class="form-control" placeholder="{{__('Date of Marriage')}}"
                                    name="anniversary_date" id="anniversary_date" aria-label="anniversary_date"
                                    aria-describedby="anniversary_date"
                                    value="{{ old('anniversary_date') }}">
                                @error('anniversary_date')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="aadhaar_no" class="form-label">{{__('Aadhaar No')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Aadhaar No')}}" name="aadhaar_no"
                                    id="aadhaar_no" aria-label="aadhaar_no" aria-describedby="aadhaar_no"
                                    value="{{ old('aadhaar_no') }}">
                                @error('aadhaar_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="pan_no" class="form-label">{{__('Pan No')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Pan No')}}" name="pan_no" id="pan_no"
                                    aria-label="pan_no" aria-describedby="pan_no"
                                    value="{{ old('pan_no') }}">
                                @error('pan_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="driving_license_no" class="form-label">{{__('Driving License No.')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Driving License No.')}}"
                                    name="driving_license_no" id="driving_license_no" aria-label="driving_license_no"
                                    aria-describedby="driving_license_no"
                                    value="{{ old('driving_license_no') }}">
                                @error('driving_license_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="passport_no" class="form-label">{{__('Passport No')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Passport No')}}" name="passport_no"
                                    id="passport_no" aria-label="passport_no" aria-describedby="passport_no"
                                    value="{{ old('passport_no') }}">
                                @error('passport_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="employee_code" class="form-label">{{__('Employee Code')}} </label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Employee Code')}}" name="employee_code"
                                    id="employee_code" aria-label="employee_code" aria-describedby="employee_code"
                                    value="{{ old('employee_code') }}">
                                @error('employee_code')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="machine_code" class="form-label">{{__('Machine Code')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Machine Code')}}" name="machine_code"
                                    id="machine_code" aria-label="machine_code" aria-describedby="machine_code"
                                    value="{{ old('machine_code') }}">
                                @error('machine_code')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="company_id">{{__('Company')}}</label>
                            <div class="">
                                {{ Form::select('company_id', ['' => __('Select Company')] + $companies, old('company_id'), ['class' => 'form-select select2']) }}
                                @error('company_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="department_id">{{__('Department')}}</label>
                            <div class="">
                                {{ Form::select('department_id', ['' => __('Select Department')] + $departments, old('department_id'), ['class' => 'form-select select2']) }}
                                @error('department_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="location_id">{{__('Location')}}</label>
                            <div class="">
                                {{ Form::select('location_id[]', $locations, old('location_id'), ['class' => 'form-select select2', 'multiple']) }}
                                @error('location_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="is_tracking_on">{{__('Location Tracking')}}</label>
                            <div class="">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input mt-2" type="checkbox" role="switch" name="is_tracking_on"
                                        value="1"
                                        {{ old('is_tracking_on') == '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                            @error('is_tracking_on')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror

                        </div>

                        <div class="col-md-4">
                            <label for="designation_id">{{__('Designation')}}</label>
                            <div class="">
                                {{ Form::select('designation_id', ['' => __('Select Designation')] + $designation, old('designation_id'), ['class' => 'form-select select2']) }}
                                @error('designation_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="shift_id">{{__('Shift')}}</label>
                            <div class="">
                                {{ Form::select('shift_id', ['' => __('Select Shift')] + $shifts, old('shift_id'), ['class' => 'form-select select2']) }}


                                @error('shift_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="policy_id">{{__('Policy')}}</label>
                            <div class="">
                                {{ Form::select('policy_id', ['' => __('Select Policy')] + $policy, old('policy_id'), ['class' => 'form-select select2']) }}


                                @error('policy_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="hire_date" class="form-label">{{__('Hire Date')}}</label>
                            <div class="">
                                <input type="date" class="form-control" placeholder="{{__('Hire Date')}}" name="hire_date"
                                    id="hire_date" aria-label="hire_date" aria-describedby="hire_date"
                                    value="{{ old('hire_date') }}">
                                @error('hire_date')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="joined_date" class="form-label">{{__('Joined Date')}}</label>
                            <div class="">
                                <input type="date" class="form-control" placeholder="{{__('Joined Date')}}" name="joined_date"
                                    id="joined_date" aria-label="joined_date" aria-describedby="joined_date"
                                    value="{{ old('joined_date') }}">
                                @error('joined_date')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="education_qualification" class="form-label">{{__('Education Qualification')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Education Qualification')}}"
                                    name="education_qualification" id="education_qualification"
                                    aria-label="education_qualification" aria-describedby="education_qualification"
                                    value="{{ old('education_qualification') }}">
                                @error('education_qualification')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="technical_qualification" class="form-label">{{__('Technical Qualification')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('Technical Qualification')}}"
                                    name="technical_qualification" id="technical_qualification"
                                    aria-label="technical_qualification" aria-describedby="technical_qualification"
                                    value="{{ old('technical_qualification') }}">
                                @error('technical_qualification')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div class="col-md-4">
                            <label for="authorised_person_id">{{__('Reporting Manager')}}</label>
                            <div class="">
                                {{ Form::select('authorised_person_id', ['' => __('Select Manager')] + $managers, old('authorised_person_id'), ['class' => 'form-select select2']) }}

                                </select>
                                @error('authorised_person_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="is_manager">{{__('Is Manager')}}</label>
                            <div class="">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input mt-2" type="checkbox" role="switch" name="is_manager"
                                        value="1"
                                        {{ old('is_manager') == '1' ? 'checked' : '' }}>
                                </div>
                            </div>
                            @error('is_manager')
                                <p class="text-danger text-xs mt-2">{{ $message }}</p>
                            @enderror

                        </div>

                        <div class="col-md-4">
                            <label for="contract_type">{{__('Contract Type')}}</label>
                            <div class="">
                                <select class="form-select select2" id="contract_type" name="contract_type"
                                    aria-label="contract_type" aria-describedby="contract_type">
                                    <option value="0"
                                        {{ old('contract_type') == '0' ? 'selected' : '' }}>
                                        {{__('Temporary')}}
                                    </option>
                                    <option value="1"
                                        {{ old('contract_type') == '1' ? 'selected' : '' }}>
                                        {{__('Permanent')}}
                                    </option>
                                </select>
                                @error('contract_type')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="pf_status">{{__('PF Eligibility')}}</label>
                            <div class="">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input mt-2" type="checkbox" role="switch" name="pf_status"
                                        value="1"
                                        {{ old('pf_status') == '1' ? 'checked' : '' }}>
                                </div>
                                @error('pf_status')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="pf_no" class="form-label">{{__('PF No')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('PF No')}}" name="pf_no" id="pf_no"
                                    aria-label="pf_no" aria-describedby="pf_no"
                                    value="{{ old('pf_no') }}">
                                @error('pf_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="uan_no" class="form-label">{{__('UAN No')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('UAN No')}}" name="uan_no" id="uan_no"
                                    aria-label="uan_no" aria-describedby="uan_no"
                                    value="{{ old('uan_no') }}">
                                @error('uan_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="vpf" class="form-label">{{__('VPF')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('VPF')}}" name="vpf" id="vpf"
                                    aria-label="vpf" aria-describedby="vpf" value="{{ old('vpf') }}">
                                @error('vpf')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="vpf_value" class="form-label">{{__('VPF Value')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('VPF Value')}}" name="vpf_value"
                                    id="vpf_value" aria-label="vpf_value" aria-describedby="vpf_value"
                                    value="{{ old('vpf_value') }}">
                                @error('vpf_value')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="eps_status">{{__('ESIC Eligibility')}}</label>
                            <div class="">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input mt-2" type="checkbox" role="switch" value="1"
                                        name="eps_status"
                                        {{ old('eps_status') == '1' ? 'checked' : '' }}>
                                </div>
                                @error('eps_status')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="esic_no" class="form-label">{{__('ESIC No')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('ESIC No')}}" name="esic_no"
                                    id="esic_no" aria-label="esic_no" aria-describedby="esic_no"
                                    value="{{ old('esic_no') }}">
                                @error('esic_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="eps_no" class="form-label">{{__('EPS No')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('EPS No')}}" name="eps_no" id="eps_no"
                                    aria-label="eps_no" aria-describedby="eps_no"
                                    value="{{ old('eps_no') }}">
                                @error('eps_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="eps_option" class="form-label">{{__('EPS Option')}}</label>
                            <div class="">
                                <input type="text" class="form-control" placeholder="{{__('EPS Option')}}" name="eps_option"
                                    id="eps_option" aria-label="eps_option" aria-describedby="eps_option"
                                    value="{{ old('eps_option') }}">
                                @error('eps_option')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="weekly_holiday" class="form-label">{{__('Weekly Holiday')}}</label>
                            <div class="">
                                {{ Form::select('weekly_holiday', ['' => __('Select Weekly Holiday')] + getListTranslate(config('constants.default_weekly_holiday')), null , ['class' => 'form-select']) }}
                                @error('weekly_holiday')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-end m-4">
                <a href="{{ Request::routeIs('admin.' . $routeSlug . '.create') ? route('admin.' . $routeSlug . '.index') : route('admin.users.index') }}"
                    type="button" name="button"
                    class="btn btn-light m-0">{{ __('BACK TO LIST') }}</a>
                <button type="submit" name="button"
                    class="btn bg-gradient-primary m-0 ms-2">{{ __('SUBMIT') }}</button>
            </div>
        </div>


    </form>
</div>
@endsection
