@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    {{-- <div class="card-header">{{ __('Login') }}</div> --}}

                    <div class="card-body">
                        <div class="brand-logo">
                            <div class="logo">
                                <img src="{{ asset('img/logo1.png') }}" alt="logo" />

                            </div>
                        </div>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="form-group row" style="justify-content: center;">
                              
                                <div class="col-md-6">
                                    <label for="email"
                                    class="">{{ __('E-Mail Address') }}</label>

                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        
                                        value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row" style="justify-content: center;">
                               
                                <div class="col-md-6">
                                    <label for="password"
                                    class="">{{ __('Password') }}</label>

                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="current-password">

                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                          

                           <div class="form-group row mt-4" style="justify-content: center">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Login') }}
                                    </button>
                           </div>
                             
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
