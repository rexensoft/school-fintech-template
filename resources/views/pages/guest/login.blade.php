@extends('layouts.guest')
@section('title', 'Login')
@section('head')
<style>
    body{
        height: 100vh;
        background-color: var(--bs-primary);
        background-size: cover;
        background-position: center;
        backdrop-filter: brightness(80%);
    }

    .bg-login-image{
        padding-top: 50%;
        background-color: var(--bs-primary);
        background-image: url("{{ asset('assets/img/background/login.svg') }}");
    }
</style>
@endsection
@section('content')
<x-view main="center" cross="start" class="pt-5 p-4">
    <x-view class="bg-white rounded-3" style="width: 900px!important">
        <x-view class="w-50 d-none d-lg-block bg-login-image"/>
        <x-view class="p-4 p-md-5" direction="column">
            <x-text value="Login" color="gray-900" class="h4 mb-4 text-center"/>

            @if($errors->any())
            <x-alert color="danger" :value="$errors" />
            @endif

            <x-form action="/login" class="user">
                <x-input type="email" name="email" placeholder="Email" value="{{ old('email') }}" class="form-control-user mb-3" required />
                <x-input type="password" name="password" placeholder="Password" class="form-control-user mb-3" required />
                <x-input type="checkbox" name="remember" label="Remember Me" class="mb-3" />
                <x-button type="submit" block value="Login" class="btn-primary btn-user"/>
            </x-form>

            @if(Route::has('password.request'))
            <x-text :href="route('password.request')" value="Forgot Password" class="mb-3 text-center" />
            @endif

            @if (Route::has('register'))
            <x-text :href="route('register')" value="Create an Account!" class="mb-3 text-center" />
            @endif
        </x-view>
    </x-view>
</x-view>
@endsection
