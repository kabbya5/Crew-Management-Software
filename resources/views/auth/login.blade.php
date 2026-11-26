@extends('layouts.app')
@section('content')
<div id="page">
    <div class="head">
        <div>
            <div class="eyebrow">Unified maritime platform</div>
            <h1>Welcome to your crew workspace</h1>
            <div class="muted">One permanent Crew ID. One approved CV. A complete career history.</div>
        </div>
        <div class="actions"></div>
    </div>
    <div class="grid authgrid">
        <div class="card">
            <div class="eyebrow">For seafarers</div>
            <h2>Crew sign in</h2>
            <p>Manage your CV, renew documents and submit updates for office review.</p>
            
            <form id="loginForm" method="POST" action="{{ route('login') }}">
                @csrf

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
                    autocomplete="username"
                    required
                >

                @error('phone')
                    <p class="bad">{{ $message }}</p>
                @enderror

                {{-- Password --}}
                <label for="password" style="margin-top:12px">
                    Password *
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                >

                @error('password')
                    <p class="bad">{{ $message }}</p>
                @enderror

                {{-- Remember Me --}}
                <div style="margin-top:14px">
                    <label style="display:flex; align-items:center; gap:9px; cursor:pointer">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            style="width:16px; height:16px; min-height:16px; padding:0"
                            {{ old('remember') ? 'checked' : '' }}
                        >

                        <span>Remember me</span>

                    </label>
                </div>

                {{-- Buttons --}}
                <div class="actions" style="margin-top:16px">

                    <button type="submit" class="primary">
                        Sign in
                    </button>

                    <a href="{{ route('register') }}">
                        <button type="button">
                            Create account
                        </button>
                    </a>

                    <a href="{{ route('password.request.form') }}">
                        <button type="button">
                            Forgot password
                        </button>
                    </a>

                </div>

                <p id="loginError" class="bad" role="alert"></p>

            </form>
            <hr>
            <p class="muted">Sample account: +8801700000001 · password: DemoCrew!2026</p><button onclick="crewLogin('CR-001')">Open sample crew dashboard</button></div>
        <div class="card">
            <div class="eyebrow">For office executives</div>
            <h2>Office operations</h2>
            <p>Review registrations and updates. Shortlist candidates, manage contracts and keep the crew lifecycle connected.</p><button class="primary" onclick="officeMode()">Open office demo</button>
            <h3>Start here</h3>
            <ol>
                <li>Open the sample crew account.</li>
                <li>Edit the approved CV and submit an update.</li>
                <li>Switch to the office demo and review it.</li>
                <li>Approve or request a correction.</li>
                <li>Continue recruitment using the same Crew ID.</li>
            </ol>
            <div class="notice warn">Offline demonstration: account access is simulated locally. No OTP is sent. Production must enforce sessions, passwords, ownership and permissions on your server.</div>
        </div>
    </div>
</div>
@endsection

@section('link')

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">

<style>
    .iti {
        width: 100%;
        display: block;
    }

    .iti input {
        width: 100% !important;
        box-sizing: border-box;
    }

    .iti__dropdown-content {
        z-index: 99999 !important;
    }
</style>

@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const phoneInput = document.querySelector("#phone");
    const loginForm = document.querySelector("#loginForm");

    const iti = window.intlTelInput(phoneInput, {

        initialCountry: "bd",

        separateDialCode: true,

        countrySearch: true,

        nationalMode: true,

        autoPlaceholder: "aggressive",

        loadUtils: () => import(
            "https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"
        )

    });

    @if(old('phone'))
        iti.setNumber(@json(old('phone')));
    @endif

    loginForm.addEventListener("submit", async function (e) {

        e.preventDefault();

        try {
            await iti.promise;
        } catch (error) {
            alert("Phone validation could not load.");
            return;
        }

        if (!iti.isValidNumber()) {

            alert("Please enter a valid WhatsApp/mobile number.");

            phoneInput.focus();

            return;
        }

        // Add country code automatically
        phoneInput.value = iti.getNumber();

        // Submit login form
        loginForm.submit();

    });

});
</script>

@endsection