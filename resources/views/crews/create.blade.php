@extends('layouts.app')

@section('link')
<link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
@endsection

@section('content')
    @if ($crew->id)
        <form action="{{route('crews.update', $crew->id)}}" method="POST" class="screen active" id="entryScreen" enctype="multipart/form-data">
        @method('put')
    @else
        <form action="{{route('crews.store')}}" method="POST" class="screen active" id="entryScreen" enctype="multipart/form-data">
    @endif
   
        @csrf

        @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <div class="sectiontop">
            <div>
                <h2>01. Crew CV Entry</h2>
                <p>CV-তে যে তথ্য থাকবে, এখানেই সেটি লিখবেন। Main Database-এ একই তথ্য সংরক্ষিত হবে (software development
                    stage)।</p>
            </div>
            @if($crew->id)
            <a href="{{route('crews.create.document', $crew->id)}}" class="btn primary">
                Next: Documents →
            </a>
            @endif

        </div>
        <div class="panel">
            <h3>Personal Information &amp; Crew Photo</h3>
            <p class="lead">নিচে শুধু fictitious demo data দেওয়া আছে, real crew তথ্য নয়।</p>
            
            <div class="photoUploadPanel">

                <div class="entryPhoto" id="entryPhotoPreview">
                    @if(!empty($crew->photo))
                        <img src="{{ asset($crew->photo) }}"
                            alt="Crew Personal Photo"
                            style="width:100%;height:100%;object-fit:cover;">
                    @else
                        <span>PERSONAL<br>PHOTO</span>
                    @endif
                </div>

                <div class="photoUploadInfo">

                    <strong>Crew Personal Photo / সিফেয়ারারের ব্যক্তিগত ছবি</strong>

                    <p>
                        এখানে crew-এর passport-style photo upload করবেন।
                        এই ছবি automatically CV-এর top-right corner-এ যাবে।
                    </p>

                    <label class="btn primary photoChooseLabel" for="crewPersonalPhotoInput">
                        Choose Photo / ছবি আপলোড করুন
                    </label>

                    <input
                        id="crewPersonalPhotoInput"
                        name="photo"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        onchange="uploadCrewPhoto(this)"
                        style="position:absolute;width:1px;height:1px;overflow:hidden;opacity:0"
                        aria-label="Upload crew personal photo">

                    <button
                        type="button"
                        class="btn mini danger"
                        id="clearCrewPhotoButton"
                        onclick="removeCrewPhoto()"
                        style="{{ !empty($crew->photo) ? '' : 'display:none' }}">
                        Remove Photo
                    </button>

                    <div class="muted" id="crewPhotoNote">
                        {{ !empty($crew->photo) ? 'Existing photo • Upload to replace' : 'No photo selected • Optional • JPG / PNG / WEBP • up to 5 MB' }}
                    </div>

                    <div class="muted">
                        Production software: photo file will be securely saved under
                        the permanent Crew ID and included in CV exports.
                    </div>

                    @error('photo')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                </div>
            </div>

            <div class="fields" id="personalFields">
                <div class="field">
                    <label>Place of Birth <small class="text-danger"> * </small> </label>
                    <input data-p="placeOfBirth" 
                        name="place_of_birth" 
                        class="@error('place_of_birth') 'is-invalid' @enderror" 
                        type="text" value="{{old('place_of_birth', $crew->place_of_birth)}}"
                        placeholder="Chattogram, Bangladesh"
                        required
                    >
                    
                    @error('place_of_birth')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field">
                    <label>Full Name</label>
                    <input
                        data-p="name"
                        type="text"
                        name="full_name"
                        value="{{ old('full_name', $crew->full_name) }}"
                        class="@error('full_name') is-invalid @enderror"
                        placeholder="SAMPLwwE MARINER"
                        required
                    >

                    @error('full_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field">
                    <label>Rank Applied For</label>

                    <select
                        data-p="rank"
                        name="rank_applied_for"
                        class="@error('rank_applied_for') is-invalid @enderror"
                    >
                        @php
                            $ranks = [
                                'Master / Captain',
                                'Chief Officer',
                                'Second Officer',
                                'Third Officer',
                                'Junior Officer',
                                'Deck Cadet',
                                'Chief Engineer',
                                'Second Engineer',
                                'Third Engineer',
                                'Fourth Engineer',
                                'Fifth Engineer',
                                'Junior Engineer',
                                'Engine Cadet',
                                'Electro-Technical Officer (ETO)',
                                'Able Seafarer Deck (AB)',
                                'Able Seafarer Engine (ASE)',
                                'Oiler',
                                'Motorman',
                                'Bosun',
                                'Chief Cook',
                                'Messman',
                                'Other',
                            ];

                            $selectedRank = old('rank_applied_for', $crew->rank_applied_for ?? 'Fourth Engineer');
                        @endphp

                        @foreach ($ranks as $rank)
                            <option value="{{ $rank }}" {{ $selectedRank == $rank ? 'selected' : '' }}>
                                {{ $rank }}
                            </option>
                        @endforeach
                    </select>

                    @error('rank_applied_for')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="field">
                    <label>Date of Birth</label>
                    <input
                        data-p="dob"
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth', isset($crew->date_of_birth) ? \Carbon\Carbon::parse($crew->date_of_birth)->format('Y-m-d') : '1996-04-18') }}"
                        class="@error('date_of_birth') is-invalid @enderror"
                    >

                    @error('date_of_birth')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field">
                    <label>Gender</label>

                    <select
                        data-p="gender"
                        name="gender"
                        class="@error('gender') is-invalid @enderror"
                    >
                        @php
                            $selectedGender = old('gender', $crew->gender ?? 'Male');
                        @endphp

                        <option value="Male" {{ $selectedGender == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $selectedGender == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other / Prefer not to say" {{ $selectedGender == 'Other / Prefer not to say' ? 'selected' : '' }}>
                            Other / Prefer not to say
                        </option>
                    </select>

                    @error('gender')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field">
                    <label>Nationality</label>
                    <select data-p="nation" id="nationality" name="nationality" class="form-control" data-selected="{{ old('nationality', $crew->nationality ?? 'Bangladesh') }}"> </select>
                </div>
                <div class="field mb-3">
                    <label> Mobile / WhatsApp </label>
                    <input type="tel" id="phone" class="form-control" value="{{ old('mobile', $crew->mobile) }}">

                    <input type="hidden" name="mobile" id="full_phone">
                    <input type="hidden" name="phone_country_code" id="phone_country_code">
                    <input type="hidden" name="phone_country_iso" id="phone_country_iso">
                </div>

                <div class="field">
                    <label>Email Address</label>
                    <input
                        data-p="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $crew->email) }}"
                        class="@error('email') is-invalid @enderror"
                        placeholder="sample@example.com"
                        required
                    >

                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field">
                    <label>Current Location</label>
                    <input
                        data-p="address"
                        type="text"
                        name="current_location"
                        value="{{ old('current_location', $crew->current_location) }}"
                        class="@error('current_location') is-invalid @enderror"
                        placeholder="sample@example.com"
                        required
                    >

                    @error('current_location')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="field">
                    <label>Available From</label>
                    <input
                        data-p="availability"
                        type="date"
                        name="available_from"
                        value="{{ old('available_from', isset($crew->available_from) ? \Carbon\Carbon::parse($crew->available_from)->format('Y-m-d') : '') }}"
                        class="@error('available_from') is-invalid @enderror"
                    >

                    @error('available_from')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        
        <div class="panel">
            <h3>Physical Measurements &amp; Uniform / PPE Sizing</h3>

            <p class="lead">
                Height, Weight, Blood Group, Shoe/Boot Size, Boiler Suit.
                Blood Group is sensitive health-related information; show it only where necessary.
            </p>

            <div class="fields" id="physicalFields">

                <!-- Height -->
                <div class="field">
                    <label>Height (cm)</label>
                    <input data-p="height"
                        name="height"
                        type="number"
                        step="0.01"
                        value="{{ old('height', $crew->height) }}"
                        placeholder="172"
                        class="@error('height') is-invalid @enderror">

                    @error('height')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Weight -->
                <div class="field">
                    <label>Weight (kg)</label>
                    <input data-p="weight"
                        name="weight"
                        type="number"
                        step="0.01"
                        value="{{ old('weight', $crew->weight) }}"
                        placeholder="70"
                        class="@error('weight') is-invalid @enderror">

                    @error('weight')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Blood Group -->
                <div class="field">
                    <label>Blood Group</label>
                    <select data-p="blood_group"
                        name="blood_group"
                        class="@error('blood_group') is-invalid @enderror">

                        <option value="">Select Blood Group</option>

                        @foreach(['Unknown / Not Provided', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                            <option value="{{ $group }}"
                                {{ old('blood_group', $crew->blood_group) == $group ? 'selected' : '' }}>
                                {{ $group }}
                            </option>
                        @endforeach
                    </select>

                    @error('blood_group')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Shoe Size -->
                <div class="field">
                    <label>Shoe / Safety Boot Size</label>
                    <input data-p="shoe_size"
                        name="shoe_size"
                        type="text"
                        value="{{ old('shoe_size', $crew->shoe_size) }}"
                        placeholder="42"
                        class="@error('shoe_size') is-invalid @enderror">

                    @error('shoe_size')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Shoe Size Standard -->
                <div class="field">
                    <label>Shoe Size Standard</label>
                    <select data-p="shoe_size_standard"
                        name="shoe_size_standard"
                        class="@error('shoe_size_standard') is-invalid @enderror">

                        <option value="">Select Standard</option>

                        @foreach(['EU', 'UK', 'US', 'CN', 'Other'] as $standard)
                            <option value="{{ $standard }}"
                                {{ old('shoe_size_standard', $crew->shoe_size_standard) == $standard ? 'selected' : '' }}>
                                {{ $standard }}
                            </option>
                        @endforeach
                    </select>

                    @error('shoe_size_standard')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Boiler Suit Size -->
                <div class="field">
                    <label>Boiler Suit / Coverall Size</label>
                    <select data-p="boiler_suit_size"
                        name="boiler_suit_size"
                        class="@error('boiler_suit_size') is-invalid @enderror">

                        <option value="">Select Size</option>

                        @foreach(['Not Measured', 'XS', 'S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL', 'Custom'] as $size)
                            <option value="{{ $size }}"
                                {{ old('boiler_suit_size', $crew->boiler_suit_size) == $size ? 'selected' : '' }}>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>

                    @error('boiler_suit_size')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <div class="layout-help">
                Height = cm, Weight = kg. Shoe Size should follow the selected
                size system (EU / UK / US). Select Boiler Suit Size from dropdown.
            </div>
        </div>


        <div class="panel">
            <h3>Professional Summary</h3>

            <div class="fields three" id="professionalFields">

                <!-- English Proficiency -->
                <div class="field">
                    <label>English Proficiency</label>
                    <input data-p="english_proficiency"
                        name="english_proficiency"
                        type="text"
                        value="{{ old('english_proficiency', $crew->english_proficiency) }}"
                        placeholder="Good"
                        class="@error('english_proficiency') is-invalid @enderror">

                    @error('english_proficiency')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Chinese Proficiency -->
                <div class="field">
                    <label>Chinese Proficiency</label>
                    <input data-p="chinese_proficiency"
                        name="chinese_proficiency"
                        type="text"
                        value="{{ old('chinese_proficiency', $crew->chinese_proficiency) }}"
                        placeholder="Basic"
                        class="@error('chinese_proficiency') is-invalid @enderror">

                    @error('chinese_proficiency')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <!-- Remarks -->
                <div class="field">
                    <label>Remarks / Availability Notes</label>
                    <textarea data-p="remarks"
                        name="remarks"
                        placeholder="Enter remarks or availability notes"
                        class="@error('remarks') is-invalid @enderror">{{ old('remarks', $crew->remarks) }}</textarea>

                    @error('remarks')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn primary float-right">Next: Sea Service →</button>
        </div>
        
    </form>
@endsection

@section('js')
<script>

$(document).ready(function () {

    let $nationality = $('#nationality');
    const selectedNationality = $nationality.data('selected');
    let $phone = $('#phone');

    // Load all country data
    let countries = window.intlTelInput.getCountryData();

    $.each(countries, function (index, country) {

        $nationality.append(
            $('<option>', {
                value: country.name,
                text: country.name,
                'data-iso': country.iso2
            })
        );

    });

    $nationality.val(selectedNationality);

    // Initialize phone input
    let iti = window.intlTelInput($phone[0], {
        initialCountry: 'bd',
        separateDialCode: true,
        nationalMode: false,
        preferredCountries: ['bd', 'us', 'gb', 'in'],

        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js'
    });

    // Nationality change
    $nationality.on('change', function () {

        let iso = $(this).find(':selected').data('iso');

        if (iso) {
            iti.setCountry(iso);
            updatePhone();
        }

    });

    // Update hidden phone fields
    function updatePhone() {

        let country = iti.getSelectedCountryData();

        let phoneValue = $phone.val().trim();

        // Remove spaces, brackets, and hyphens
        phoneValue = phoneValue.replace(/\D/g, '');

        // Remove leading zero
        phoneValue = phoneValue.replace(/^0+/, '');

        // Generate international number
        let fullNumber = phoneValue
            ? '+' + country.dialCode + phoneValue
            : '';

        $('#full_phone').val(fullNumber);

        $('#phone_country_code').val('+' + country.dialCode);

        $('#phone_country_iso').val(country.iso2.toUpperCase());

    }

    // Phone input events
    $phone.on('input change blur countrychange', updatePhone);

    // Initial update
    updatePhone();
});

function uploadCrewPhoto(input) {
        const file = input.files[0];

        if (!file) return;

        if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
            alert('Please select JPG, PNG or WEBP image.');
            input.value = '';
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Maximum photo size is 5 MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();

        reader.onload = function (e) {
            $('#entryPhotoPreview').html(
                `<img src="${e.target.result}"
                    style="width:100%;height:100%;object-fit:cover;">`
            );

            $('#clearCrewPhotoButton').show();
            $('#crewPhotoNote').text(file.name);
        };

        reader.readAsDataURL(file);
    }

    function removeCrewPhoto() {
        $('#crewPersonalPhotoInput').val('');

        $('#entryPhotoPreview').html('<span>PERSONAL<br>PHOTO</span>');

        $('#clearCrewPhotoButton').hide();

        $('#crewPhotoNote').text(
            'No photo selected • Optional • JPG / PNG / WEBP • up to 5 MB'
        );
    }

</script>
@endsection
