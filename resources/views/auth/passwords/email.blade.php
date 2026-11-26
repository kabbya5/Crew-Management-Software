@extends('layouts.app')

@section('content')

<div style="max-width:500px;margin:50px auto;">
    <div class="card p-4 shadow-sm">

        <h2 class="mb-2">Reset Password</h2>

        <p class="text-muted">
            Submit your password reset request.
            Your password will only be changed after admin approval.
        </p>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.request.submit') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text"
                       name="phone_number"
                       class="form-control"
                       value="{{ old('phone_number') }}"
                       placeholder="Enter your phone number"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Crew ID</label>
                <input type="text"
                       name="crew_id"
                       class="form-control"
                       value="{{ old('crew_id') }}"
                       placeholder="Enter your Crew ID"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">New Password</label>
                <input type="password"
                       name="new_password"
                       class="form-control"
                       placeholder="Enter new password"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password"
                       name="new_password_confirmation"
                       class="form-control"
                       placeholder="Confirm new password"
                       required>
            </div>

            <button type="submit" class="btn btn-primary w-100">
                Submit Reset Request
            </button>

        </form>

    </div>
</div>

@endsection