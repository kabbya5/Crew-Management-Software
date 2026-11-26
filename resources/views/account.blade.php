
@extends('layouts.app')

@section('content')
<div id="page">
    @include('_dashboard_header')

    <div class="account-wrapper">

        {{-- Account Information --}}
        <div class="card account-card">
            <h2>Account &amp; Access</h2>

            <p>Permanent Crew ID: <b>CR-001</b></p>
            <p>Login number: +8801700000001</p>

            <div class="notice info">
                Your Crew ID is permanent. Updating your login number will not
                affect your Crew ID, documents, sea service, or account history.
            </div>
        </div>

        {{-- Change Login Number --}}
        <div class="card account-card">
            <h3>Change Login Number</h3>
            <p>Update your phone number used to log in to your account.</p>

            <form action="{{ route('account.phone.update') }}" method="POST" id="phoneForm">
                @csrf

                <div class="form-group">
                    <label for="current_phone">Current Login Number</label>
                    <input
                        type="text"
                        id="current_phone"
                        class="form-control"
                        value="{{auth()->user()->phone}}"
                        readonly
                    >
                </div>

                <div class="form-group">
                    <label for="phone">New Login Number</label>

                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="form-control"
                        placeholder="Enter phone number"
                        autocomplete="tel"
                        required
                    >

                    <input type="hidden" name="country_code" id="country_code">
                    <input type="hidden" name="full_phone" id="full_phone">

                    <small id="phoneError" class="error-message"></small>
                </div>

                <div class="form-group">
                    <label for="phone_current_password">Current Password</label>
                    <input
                        type="password"
                        id="phone_current_password"
                        name="current_password"
                        class="form-control"
                        placeholder="Enter current password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit" class="btn primary">
                    Update Login Number
                </button>
            </form>
        </div>

        {{-- Change Password --}}
        <div class="card account-card">
            <h3>Change Password</h3>
            <p>Update your password to keep your account secure.</p>

            <form action="{{ route('account.password.update') }}" method="POST" id="passwordForm">
                @csrf

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="form-control"
                        placeholder="Enter current password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input
                        type="password"
                        id="new_password"
                        name="password"
                        class="form-control"
                        placeholder="Enter new password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input
                        type="password"
                        id="confirm_password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Confirm new password"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >

                    <small id="passwordError" class="error-message"></small>
                </div>

                <button type="submit" class="btn primary">
                    Update Password
                </button>
            </form>
        </div>

    </div>

    <footer>
        English-only crew workspace • Account security • Your permanent Crew ID never changes.
    </footer>
</div>

{{-- International Telephone Input --}}
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/intl-tel-input@25/build/css/intlTelInput.css">

<style>
    .account-wrapper {
        max-width: 850px;
        margin: 0 auto;
    }

    .account-card {
        margin-bottom: 22px;
        padding: 25px;
    }

    .account-card h2,
    .account-card h3 {
        margin-bottom: 8px;
    }

    .account-card p {
        color: #64748b;
        margin-bottom: 16px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .form-control {
        width: 100%;
        min-height: 45px;
        padding: 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        outline: none;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
    }

    .iti {
        width: 100%;
    }

    .iti input {
        width: 100%;
    }

    .error-message {
        display: block;
        color: #dc2626;
        font-size: 13px;
        margin-top: 6px;
    }

    .notice.info {
        padding: 12px 15px;
        margin: 18px 0;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        color: #1e40af;
        font-size: 14px;
    }

    .btn.primary {
        padding: 11px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    }

    @media (max-width: 600px) {
        .account-card {
            padding: 18px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25/build/js/intlTelInput.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ===============================
    // PHONE NUMBER INITIALIZATION
    // ===============================

    const phoneInput = document.querySelector("#phone");
    const phoneForm = document.querySelector("#phoneForm");
    const phoneError = document.querySelector("#phoneError");

    const iti = window.intlTelInput(phoneInput, {
        initialCountry: "auto",
        separateDialCode: true,
        nationalMode: true,
        preferredCountries: ["bd", "us", "gb", "in", "sa", "ae"],
        geoIpLookup: function (callback) {
            fetch("https://ipapi.co/json/")
                .then(response => response.json())
                .then(data => callback(data.country_code ? data.country_code.toLowerCase() : "bd"))
                .catch(() => callback("bd"));
        },
        loadUtils: () => import(
            "https://cdn.jsdelivr.net/npm/intl-tel-input@25/build/js/utils.js"
        )
    });

    // Submit phone form
    phoneForm.addEventListener('submit', function (e) {

        phoneError.textContent = "";

        if (!iti.isValidNumber()) {
            e.preventDefault();
            phoneError.textContent = "Please enter a valid phone number.";
            return;
        }

        // International format, for example: +8801712345678
        const fullNumber = iti.getNumber();

        // Selected country dial code, for example: +880
        const countryCode = "+" + iti.getSelectedCountryData().dialCode;

        document.querySelector("#full_phone").value = fullNumber;
        document.querySelector("#country_code").value = countryCode;

        // Store the international number in phone field too
        phoneInput.value = fullNumber;
    });


    // ===============================
    // PASSWORD VALIDATION
    // ===============================

    const passwordForm = document.querySelector("#passwordForm");
    const newPassword = document.querySelector("#new_password");
    const confirmPassword = document.querySelector("#confirm_password");
    const passwordError = document.querySelector("#passwordError");

    passwordForm.addEventListener('submit', function (e) {

        passwordError.textContent = "";

        if (newPassword.value.length < 8) {
            e.preventDefault();
            passwordError.textContent = "Password must contain at least 8 characters.";
            return;
        }

        if (newPassword.value !== confirmPassword.value) {
            e.preventDefault();
            passwordError.textContent = "New password and confirmation do not match.";
            return;
        }
    });

});
</script>

@endsection
