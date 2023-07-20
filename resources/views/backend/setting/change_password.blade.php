@extends('layouts.admin.app')
@section('content')
<div>
   <div class="row">
      <div class="col-12">
         <div class="card mb-4 mx-4">
            <div class="card-header pb-0">
               <div class="d-flex flex-row justify-content-between">
                  <div>
                     <h5 class="mb-0">Change password</h5> 
                  </div>
                  
               </div>
            </div>
            <div class="card-body pb-0">
               <div class="table-responsive p-0">
                  <form action="{{ route('admin.setting.updateMulti') }}" method="POST">
                     @csrf
                    
                     <table class="table align-items-center mb-0">
                        <tbody>
                           @foreach ($settings as $setting)
                           <tr>
                              <td class="text-center" style="width:50px;">
                                 <p class="text-xs font-weight-bold mb-0">{{ ucwords(trans(str_replace('_', ' ', $setting->setting_name))) }}</p>
                              </td>
                              <td class="text-left">
                                 @if( $setting->input_type =="file" )  
                                 <input type="file" class="form-control" placeholder="{{ $setting->setting_name }}" name="setting[{{ $setting->id }}][{{ $setting->setting_name }}]" id="setting_{{ $setting->setting_name }}" value="{{ old($setting->setting_name, $setting->setting_value) }}">
                                 @else
                                 <input type="text" class="form-control" placeholder="{{ $setting->setting_name }}" name="setting[{{ $setting->id }}][{{ $setting->setting_name }}]" id="setting_{{ $setting->setting_name }}" value="{{ old($setting->setting_name, $setting->setting_value) }}">
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
                        <button type="submit" name="button" class="btn bg-gradient-primary m-0 ms-2">Save Setting</button>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@push('dashboard')
@endpush
@endsection