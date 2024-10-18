@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="container-fluid">
        <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved0.jpg'); background-position-y: 50%;">
            <span class="mask bg-gradient-primary opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="../assets/img/bruce-mars.jpg" alt="..." class="w-100 border-radius-lg shadow-sm">
                        <a href="javascript:;" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2">
                            <i class="fa fa-pen top-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Image"></i>
                        </a>
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->username }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ auth()->user()->position }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('Profile Information') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('user-profile.update') }}" method="POST" role="form text-left">
                    @csrf
                    @if($errors->any())
                        <div class="mt-3  alert alert-primary alert-dismissible fade show" role="alert">
                            <span class="alert-text text-white">{{ $errors->first() }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="m-3 alert alert-success alert-dismissible fade show" id="alert-success" role="alert">
                            <span class="alert-text text-white">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif

                    <!-- Username -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="form-control-label">{{ __('Username') }}</label>
                                <input class="form-control @error('username') border border-danger rounded-3 @enderror" type="text" placeholder="Username" id="username" name="username" value="{{ auth()->user()->username }}">
                                @error('username')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">{{ __('Email') }}</label>
                                <input class="form-control @error('email') border border-danger rounded-3 @enderror" type="email" placeholder="@example.com" id="email" name="email" value="{{ auth()->user()->email }}">
                                @error('email')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- HP (Phone) -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hp" class="form-control-label">{{ __('Phone') }}</label>
                                <input class="form-control @error('hp') border border-danger rounded-3 @enderror" type="tel" placeholder="Phone" id="hp" name="hp" value="{{ auth()->user()->hp }}">
                                @error('hp')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-control-label">{{ __('New Password') }}</label>
                                <input class="form-control @error('password') border border-danger rounded-3 @enderror" type="password" placeholder="Password" id="password" name="password">
                                @error('password')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-control-label">{{ __('Confirm Password') }}</label>
                                <input class="form-control" type="password" placeholder="Confirm Password" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>

                    <!-- Submit button -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Save Changes' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
