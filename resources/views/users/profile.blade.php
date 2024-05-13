@extends('layouts.app')


@section('content')
    <div class="container mt-5">
        <div class="row mt-2">

            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <div class="col-12">
                <div class="card">

                    <div class="card-header">
                        <h4 class="mb-0">Manage profile</h4>
                    </div>

                    <form action="{{ route('users.profile.update') }}" method="POST" enctype="multipart/form-data">

                        <div class="card-body">

                            @csrf

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user -> name }}">

                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group pt-3">
                                        <label for="icon">Photo</label>
                                        <img src="{{ $user -> icon ?? asset('storage/default.png') }}" width="64" height="64" class="rounded-circle" alt="">
                                        <input type="file" class="@error('icon') is-invalid @enderror" id="icon" name="icon" accept="image/jpeg, image/png, image/jpg">

                                        @error('icon')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="password">New password</label>
                                        <input type="password" class="form-control @if (session('password_mismatch')) is-invalid @endif @error('password') is-invalid @enderror" id="password" name="password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                        @if (session('password_mismatch'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ session('password_mismatch') }}</strong>
                                        </span>
                                        @endif
                                    </div>

                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm password</label>
                                        <input type="password" class="form-control @if (session('password_mismatch')) is-invalid @endif @error('confirm_password') is-invalid @enderror" id="confirm_password" name="confirm_password">

                                        @error('confirm_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror

                                        @if (session('password_mismatch'))
                                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ session('password_mismatch') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>

        $(document).ready(function() {

        });

    </script>

@endsection
