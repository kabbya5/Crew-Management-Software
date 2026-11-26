
@extends('layouts.app')

@section('content')

@php
    $courseGroups = [
        'STCW Basic & Safety' => [
            'Basic Safety Training (BST / BT)',
            'Personal Survival Techniques (PST)',
            'Fire Prevention and Fire Fighting (FPFF)',
            'Elementary First Aid (EFA)',
            'Personal Safety and Social Responsibilities (PSSR)',
            'Basic Training Refresher (PST / FPFF)',
            'Proficiency in Survival Craft and Rescue Boats (PSCRB)',
            'PSCRB Refresher',
            'Proficiency in Fast Rescue Boats (PFRB)',
            'PFRB Refresher',
            'Advanced Fire Fighting (AFF)',
            'Advanced Fire Fighting Refresher',
            'Medical First Aid (MFA)',
            'Medical Care',
            'Security Awareness Training',
            'Designated Security Duties (DSD)',
            'Ship Security Officer (SSO)',
        ],

        'STCW Tanker & Specialized' => [
            'Basic Training for Oil and Chemical Tanker Cargo Operations',
            'Advanced Training for Oil Tanker Cargo Operations',
            'Advanced Training for Chemical Tanker Cargo Operations',
            'Basic Training for Liquefied Gas Tanker Cargo Operations',
            'Advanced Training for Liquefied Gas Tanker Cargo Operations',
            'Basic Training for Ships Subject to IGF Code',
            'Advanced Training for Ships Subject to IGF Code',
            'Basic Training for Ships Operating in Polar Waters',
            'Advanced Training for Ships Operating in Polar Waters',
            'Passenger Ship Emergency Familiarization (STCW V/2)',
            'Passenger Ship Safety Training — Direct Service to Passengers (STCW V/2)',
            'Passenger Ship Crowd Management (STCW V/2)',
            'Passenger Ship Crisis Management and Human Behaviour (STCW V/2)',
            'Passenger Safety, Cargo Safety and Hull Integrity — Ro-Ro (STCW V/2)',
            'Passenger Ship Safety / Familiarization Training',
        ],

        'Navigation / Engineering / Radio / Other Training' => [
            'ECDIS Generic Training',
            'Radar / ARPA Training - Operational Level',
            'Radar / ARPA Training - Management Level',
            'Bridge Resource Management (BRM)',
            'Engine Room Resource Management (ERM)',
            'GMDSS General Operator Certificate (GOC)',
            'GMDSS Restricted Operator Certificate (ROC)',
            'High Voltage - Operational Level',
            'High Voltage - Management Level',
            'Leadership and Teamworking Skills',
            'Leadership and Managerial Skills',
            'Marine Environmental Awareness',
            'Dynamic Positioning (DP) Training',
            'Ship Simulator & Bridge Teamwork',
            'Company / Flag-Specific Training',
            'Other Course (specify)',
        ],
    ];

    $rows = old('stcws');

    if ($rows === null) {
        $rows = $crewStcws->map(function ($item) {
            return [
                'id' => $item->id,
                'course_name' => $item->course_name,
                'certificate_number' => $item->certificate_number,
                'issue_date' => $item->issue_date?->format('Y-m-d'),
                'expiry_date' => $item->expiry_date?->format('Y-m-d'),
                'no_expiry' => $item->no_expiry,
                'file_path' => $item->file_path,
            ];
        })->toArray();
    }

    if (empty($rows)) {
        $rows = [[]];
    }
@endphp

<form action="{{ route('crews.store.stcw', $crew->id) }}"
      method="POST"
      enctype="multipart/form-data"
      class="screen active"
      id="stcwForm">

    @csrf

    <section class="screen active" id="coursesScreen">

        <div class="sectiontop">
            <div>
                <h2>04. STCW COURSES & CERTIFICATES</h2>
                <p>STCW dropdown → Course select → Certificate No. → Issue / Expiry → Optional Choose File → Add Another</p>
            </div>

            <a href="{{route('crews.create.contact',$crew->id)}}" class="btn primary">
                Emergency Contacts
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="panel">

            <div class="listheader">
                <div>
                    <h3>STCW & Maritime Training Certificates</h3>
                    <p class="lead">
                        একজন Mariner-এর একাধিক course থাকবে। Entry row যত খুশি Add করা যাবে।
                    </p>
                </div>

                <button type="button" class="btn primary" id="addCourse">
                    + Add STCW Course
                </button>
            </div>

            <div id="courseRows">

                @foreach($rows as $index => $row)

                    @php
                        $noExpiry = !empty($row['no_expiry']);
                        $filePath = $row['file_path'] ?? null;
                    @endphp

                    <div class="editableRow course-row" data-index="{{ $index }}">

                        <div class="rowtag">
                            <span class="row-title">
                                STCW / Training Certificate #{{ $loop->iteration }}
                            </span>

                            <button type="button" class="btn mini danger removeCourse">
                                Remove
                            </button>
                        </div>

                        @if(!empty($row['id']))
                            <input type="hidden"
                                   name="stcws[{{ $index }}][id]"
                                   value="{{ $row['id'] }}">
                        @endif

                        <div class="coursegrid">

                            <div class="mini-field">
                                <label>Course Name (Dropdown)</label>

                                <select name="stcws[{{ $index }}][course_name]" required>
                                    <option value="">Select STCW Course</option>

                                    @foreach($courseGroups as $group => $courses)
                                        <optgroup label="{{ $group }}">
                                            @foreach($courses as $course)
                                                <option value="{{ $course }}"
                                                    @selected(($row['course_name'] ?? '') == $course)>
                                                    {{ $course }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mini-field">
                                <label>Certificate Number</label>

                                <input type="text"
                                       name="stcws[{{ $index }}][certificate_number]"
                                       value="{{ $row['certificate_number'] ?? '' }}" placeholder="DEMO-PSCR-001">
                            </div>

                            <div class="mini-field">
                                <label>Issue Date</label>

                                <input type="date"
                                       name="stcws[{{ $index }}][issue_date]"
                                       value="{{ $row['issue_date'] ?? '' }}">
                            </div>

                            <div class="mini-field">
                                <label>Expiry Date</label>

                                <input type="date"
                                       name="stcws[{{ $index }}][expiry_date]"
                                       value="{{ $noExpiry ? '' : ($row['expiry_date'] ?? '') }}"
                                       class="expiry-date"
                                       {{ $noExpiry ? 'disabled' : '' }}>

                                <span class="toggleline">
                                    <input type="checkbox"
                                           name="stcws[{{ $index }}][no_expiry]"
                                           value="1"
                                           class="no-expiry"
                                           {{ $noExpiry ? 'checked' : '' }}>

                                    No fixed expiry / N/A
                                </span>
                            </div>

                            <div class="mini-field">
                                <label>Choose File (Optional)</label>

                                <input type="file"
                                       name="stcws[{{ $index }}][file]"
                                       accept=".pdf,.jpg,.jpeg,.png,.webp">

                                <span class="attach-note">
                                    @if($filePath)
                                        Current file:
                                        <a href="{{ asset( $filePath) }}"
                                           target="_blank">
                                            View file
                                        </a>
                                    @else
                                        No file selected • not mandatory
                                    @endif
                                </span>
                            </div>

                        </div>
                    </div>

                @endforeach

            </div>

            <div class="layout-help">
                Expiry date না থাকলে “No Fixed Expiry / N/A” টিক দিন।
                Renewal frequency সব certificate-এর জন্য এক নয়;
                issuing authority/flag requirements অনুযায়ী যাচাই করতে হবে।
            </div>

            <div class="d-flex justify-content-between mt-4">

                <a href="{{ route('crews.create.service.recored', $crew->id) }}" class="btn">
                    Back
                </a>

                <button type="submit" class="btn primary">
                    Next Emergency Contact →
                </button>

            </div>
        </div>
    </section>

</form>

{{-- Template for adding new rows --}}
<template id="courseTemplate">

    <div class="editableRow course-row" data-index="__INDEX__">

        <div class="rowtag">
            <span class="row-title">STCW / Training Certificate</span>

            <button type="button" class="btn mini danger removeCourse">
                Remove
            </button>
        </div>

        <div class="coursegrid">

            <div class="mini-field">
                <label>Course Name (Dropdown)</label>

                <select name="stcws[__INDEX__][course_name]" required>
                    <option value="">Select STCW Course</option>

                    @foreach($courseGroups as $group => $courses)
                        <optgroup label="{{ $group }}">
                            @foreach($courses as $course)
                                <option value="{{ $course }}">{{ $course }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="mini-field">
                <label>Certificate Number</label>

                <input type="text"
                       name="stcws[__INDEX__][certificate_number]" placeholder="DEMO-PSCR-001">
            </div>

            <div class="mini-field">
                <label>Issue Date</label>

                <input type="date"
                       name="stcws[__INDEX__][issue_date]">
            </div>

            <div class="mini-field">
                <label>Expiry Date</label>

                <input type="date"
                       name="stcws[__INDEX__][expiry_date]"
                       class="expiry-date">
                
                <span class="toggleline">
                    <input type="checkbox"
                           name="stcws[__INDEX__][no_expiry]"
                           value="1"
                           class="no-expiry">

                    No fixed expiry / N/A
                </span>
            </div>

            <div class="mini-field">
                <label>Choose File (Optional)</label>

                <input type="file"
                       name="stcws[__INDEX__][file]"
                       accept=".pdf,.jpg,.jpeg,.png,.webp">

                <span class="attach-note">
                    No file selected • not mandatory
                </span>
            </div>

        </div>
    </div>

</template>

@endsection


@section('js')

<script>
$(document).ready(function () {

    let courseIndex = {{ count($rows) }};

    // Add new course row
    $('#addCourse').on('click', function () {

        let template = $('#courseTemplate').html();

        template = template.replace(/__INDEX__/g, courseIndex);

        $('#courseRows').append(template);

        courseIndex++;

        updateCourseNumbers();
    });


    // Remove course row
    $(document).on('click', '.removeCourse', function () {

        $(this).closest('.course-row').remove();

        updateCourseNumbers();
    });


    // No expiry checkbox
    $(document).on('change', '.no-expiry', function () {

        let row = $(this).closest('.course-row');
        let expiryInput = row.find('.expiry-date');

        if ($(this).is(':checked')) {
            expiryInput.val('').prop('disabled', true);
        } else {
            expiryInput.prop('disabled', false);
        }
    });


    // Update row numbers
    function updateCourseNumbers() {

        $('#courseRows .course-row').each(function (index) {

            $(this).find('.row-title').text(
                'STCW / Training Certificate #' + (index + 1)
            );
        });
    }

    updateCourseNumbers();

});
</script>

@endsection
