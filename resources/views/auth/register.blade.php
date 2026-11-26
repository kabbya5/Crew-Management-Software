
@extends('layouts.app')

@section('content')

<div style="max-width:1020px; margin:0 auto">

    <div class="card">

        <div class="eyebrow">For seafarers</div>

        <h2>Create your crew account</h2>

        <p>
            Register to manage your CV, renew documents and
            submit updates for office review.
        </p>

        {{-- Registration Form --}}
        <form id="registerForm"
              action="{{ route('register') }}"
              method="POST">

            @csrf

            {{-- Name --}}
            <label for="phone">
                FUll Name
            </label>

            <input
                name="name"
                type="text"
                value="{{ old('name') }}"
                placeholder="Enter Full Name"
                required
            >

            @error('name')
                <p class="bad">{{ $message }}</p>
            @enderror

            {{-- Phone Number --}}
            <label for="phone">
                WhatsApp / mobile number *
            </label>

            <input
                id="phone"
                name="phone"
                type="tel"
                value="{{ old('phone') }}"
                placeholder="Enter mobile number"
                autocomplete="tel"
                required
            >

            @error('phone')
                <p class="bad">{{ $message }}</p>
            @enderror


            {{-- Password --}}
            <label for="password" style="margin-top:12px">
                Create password (minimum 10 characters) *
            </label>

            <input
                id="password"
                name="password"
                type="password"
                placeholder="Create password"
                minlength="10"
                autocomplete="new-password"
                required
            >

            @error('password')
                <p class="bad">{{ $message }}</p>
            @enderror


            {{-- Confirm Password --}}
            <label for="password_confirmation" style="margin-top:12px">
                Confirm password *
            </label>

            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                placeholder="Confirm password"
                minlength="10"
                autocomplete="new-password"
                required
            >


            {{-- Consent --}}
            <div style="margin-top:16px">

                <label style="display:flex; align-items:flex-start; gap:10px">

                    <input
                        type="checkbox"
                        name="consent"
                        value="1"
                        style="width:auto; margin-top:4px"
                        {{ old('consent') ? 'checked' : '' }}
                        required
                    >

                    <span>
                        I agree to submit my career information
                        for recruitment review.
                    </span>

                </label>

                @error('consent')
                    <p class="bad">{{ $message }}</p>
                @enderror

            </div>


            {{-- Buttons --}}
            <div class="actions" style="margin-top:16px">

                <button type="submit" class="primary">
                    Create account
                </button>

                <a href="{{ route('login') }}">
                    <button type="button">
                        Already have an account? Sign in
                    </button>
                </a>

            </div>

        </form>

    </div>

</div>

@endsection


{{-- Country Selector CSS --}}
@section('link')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">

<style>

    .iti {
        width: 100%;
    }

    .iti input {
        width: 100% !important;
        box-sizing: border-box;
    }

    .iti__dropdown-content {
        z-index: 9999;
    }

</style>

@endsection


{{-- Country Selector JavaScript --}}
@section('js')


<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const phoneInput = document.querySelector("#phone");
    const registerForm = document.querySelector("#registerForm");

    if (!phoneInput || !registerForm || !window.intlTelInput) {
        console.error("Phone input initialization failed.");
        return;
    }

    const iti = window.intlTelInput(phoneInput, {

        initialCountry: "bd",

        separateDialCode: true,

        countrySearch: true,

        nationalMode: true,

        autoPlaceholder: "aggressive",

        formatOnDisplay: true,

        loadUtils: () => import(
            "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"
        )

    });

    // Restore old number after validation error
    @if(old('phone'))
        iti.setNumber(@json(old('phone')));
    @endif

    // Submit registration form
    registerForm.addEventListener("submit", async function (e) {

        e.preventDefault();

        const number = phoneInput.value.trim();

        if (!number) {
            alert("Please enter your mobile number.");
            phoneInput.focus();
            return;
        }

        // Wait for phone validation utilities
        try {
            await iti.promise;
        } catch (error) {
            console.error("Phone utilities failed to load:", error);
            alert("Phone validation could not load. Please refresh the page.");
            return;
        }

        // Validate number
        if (!iti.isValidNumber()) {

            alert("Please enter a valid WhatsApp/mobile number.");

            phoneInput.focus();

            return;
        }

        // Get complete international number
        const fullNumber = iti.getNumber();

        // Submit full number to Laravel
        phoneInput.value = fullNumber;

        // Submit form
        registerForm.submit();

    });

});
</script>


@endsection
