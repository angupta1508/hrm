@extends('errors.minimal')

@section('title', __('Server Error'))
@section('code', '3001')
@section('message', __('Server Error'))
{{-- @section('message', __($exception->getMessage())) --}}

