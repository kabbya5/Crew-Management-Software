
@extends('layouts.app')

@section('content')

<div style="max-width:500px;margin:0 auto;">
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

        <form action="{{ route('password.request.submit') }}"
              method="POST"
              id="resetPasswordForm">

            @csrf

            {{-- Phone Number --}}
            <div class="mb-3">
                <label class="form-label">Phone Number</label>

                <input type="tel"
                       id="phone"
                       class="form-control"
                       placeholder="Enter phone number"
                       autocomplete="tel"
                       required>

                <input type="hidden"
                       name="phone_number"
                       id="phone_number"
                       value="{{ old('phone_number') }}">

                <small id="phoneError" class="text-danger"></small>
            </div>

            {{-- New Password --}}
            <div class="mb-3">
                <label class="form-label">New Password</label>

                <input type="password"
                       name="new_password"
                       id="new_password"
                       class="form-control"
                       placeholder="Enter new password"
                       required>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">
                <label class="form-label">Confirm New Password</label>

                <input type="password"
                       name="new_password_confirmation"
                       id="confirm_password"
                       class="form-control"
                       placeholder="Confirm new password"
                       required>
            </div>

            <small id="passwordError" class="text-danger d-block mb-3"></small>

            <button type="submit" class="btn btn-primary w-100">
                Submit Reset Request
            </button>

        </form>

    </div>
</div>

@endsection


{{-- ================= PHONE CSS ================= --}}
@section('link')

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">

<style>
    .iti {
        width: 100%;
    }

    .iti input {
        width: 100%;
    }

    #phoneError,
    #passwordError {
        font-size: 13px;
    }
</style>

@endsection


{{-- ================= JAVASCRIPT ================= --}}
@section('js')

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const phoneInput = document.querySelector('#phone');
    const hiddenPhone = document.querySelector('#phone_number');
    const form = document.querySelector('#resetPasswordForm');

    const phoneError = document.querySelector('#phoneError');
    const passwordError = document.querySelector('#passwordError');

    const newPassword = document.querySelector('#new_password');
    const confirmPassword = document.querySelector('#confirm_password');

    // =============================
    // PHONE INITIALIZATION
    // =============================

    const iti = window.intlTelInput(phoneInput, {
        initialCountry: 'bd',
        separateDialCode: true,
        nationalMode: true,
        countrySearch: true,
        preferredCountries: ['bd', 'us', 'gb', 'sa', 'ae', 'in'],
        allowDropdown: true,

        loadUtils: () => import(
            'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js'
        )
    });

    // Restore old phone number
    if (hiddenPhone.value) {
        iti.setNumber(hiddenPhone.value);
    }

    // =============================
    // FORM SUBMIT VALIDATION
    // =============================

    form.addEventListener('submit', function (e) {

        phoneError.textContent = '';
        passwordError.textContent = '';

        // Phone validation
        if (!iti.isValidNumber()) {
            e.preventDefault();

            phoneError.textContent = 'Please enter a valid phone number.';
            phoneInput.focus();

            return;
        }

        // Password length validation
        if (newPassword.value.length < 8) {
            e.preventDefault();

            passwordError.textContent =
                'Password must contain at least 8 characters.';

            newPassword.focus();

            return;
        }

        // Confirm password validation
        if (newPassword.value !== confirmPassword.value) {
            e.preventDefault();

            passwordError.textContent =
                'New password and confirmation do not match.';

            confirmPassword.focus();

            return;
        }

        // Get full international phone number
        hiddenPhone.value = iti.getNumber();

    });

});
</script>

@endsection
