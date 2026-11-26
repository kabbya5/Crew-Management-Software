
@extends('layouts.app')

@section('link')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
@endsection

@section('content')

@php
    $rows = old('contacts');

    if ($rows === null) {
        $rows = $crewContacts->map(function ($contact) {
            return [
                'id' => $contact->id,
                'name' => $contact->full_name,
                'relation' => $contact->relationship,
                'mobile' => $contact->mobile,
                'email' => $contact->email,
                'address' => $contact->address,
                'priority' => $contact->priority,
            ];
        })->toArray();
    }

    if (empty($rows)) {
        $rows = [[]];
    }
@endphp

<form action="{{ route('crews.store.contact', $crew->id) }}"
      method="POST"
      class="screen active"
      id="entryScreen">

    @csrf

    <div class="sectiontop">
        <div>
            <h2>05. Crew Contact Entry</h2>
            <p>
                Add Next of Kin (NOK) and emergency contact information.
                Primary and alternate contacts can be managed separately.
            </p>
        </div>

        <button type="submit" class="btn primary">
            Save Contacts →
        </button>
    </div>

    <div class="panel">

        <div class="listheader">
            <div>
                <h3>Next of Kin (NOK) / Emergency Contacts</h3>
                <p class="lead">
                    Primary এবং Alternate Emergency Contact আলাদাভাবে Add করুন.
                    These details are confidential.
                </p>
            </div>

            <button type="button" class="btn primary" id="addKin">
                + Add Emergency Contact
            </button>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="kinRows">

            @foreach ($rows as $index => $row)

                <div class="editableRow" data-kin-row>

                    <input type="hidden"
                           name="contacts[{{ $index }}][id]"
                           value="{{ $row['id'] ?? '' }}">

                    <div class="rowtag">
                        <span class="kin-title">
                            {{ $row['priority'] ?? ''}}
                            NOK #{{ $index + 1 }}
                        </span>

                        <button type="button"
                                class="btn mini danger removeKin">
                            Remove
                        </button>
                    </div>

                    <div class="nokgrid">

                        <div class="mini-field">
                            <label>Full Name / নাম</label>
                            <input type="text"
                                   name="contacts[{{ $index }}][name]"
                                   value="{{ $row['name'] ?? '' }}"
                                   placeholder="Enter full name"
                                   required>
                        </div>

                        <div class="mini-field">
                            <label>Relationship / সম্পর্ক</label>

                            <select name="contacts[{{ $index }}][relation]">
                                <option value="">Select Relationship</option>

                                @foreach (['Spouse', 'Father', 'Mother', 'Brother', 'Sister', 'Son', 'Daughter', 'Relative', 'Other'] as $relation)
                                    <option value="{{ $relation }}"
                                        {{ ($row['relation'] ?? '') == $relation ? 'selected' : '' }}>
                                        {{ $relation }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mini-field">
                            <label>Mobile / WhatsApp</label>

                            <input type="tel"
                                   name="contacts[{{ $index }}][mobile]"
                                   value="{{ $row['mobile'] ?? '' }}"
                                   placeholder="+8801XXXXXXXXX"
                                   required>
                        </div>

                        <div class="mini-field">
                            <label>Email (Optional)</label>

                            <input type="email"
                                   name="contacts[{{ $index }}][email]"
                                   value="{{ $row['email'] ?? '' }}"
                                   placeholder="example@email.com">
                        </div>

                        <div class="mini-field wide">
                            <label>Address & Country / ঠিকানা</label>

                            <input type="text"
                                   name="contacts[{{ $index }}][address]"
                                   value="{{ $row['address'] ?? '' }}"
                                   placeholder="Enter address and country">
                        </div>

                        <div class="mini-field">
                            <label>Contact Priority</label>

                           <select name="contacts[{{ $index }}][priority]" class="primarySelect">

                                <option value="primary"
                                    {{ ($row['priority'] ?? '') === 'primary' ? 'selected' : '' }}>
                                    Primary
                                </option>

                                <option value="alternate"
                                    {{ ($row['priority'] ?? '') === 'alternate' ? 'selected' : '' }}>
                                    Alternate
                                </option>

                            </select>
                        </div>

                    </div>
                </div>

            @endforeach

        </div>

        <div class="note">
            NOK information is confidential. External CV visibility
            should be controlled according to the applicable permissions.
        </div>

    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn primary">
            Save Contacts →
        </button>
    </div>

</form>

<template id="kinTemplate">
    <div class="editableRow" data-kin-row>

        <input type="hidden" name="contacts[__INDEX__][id]" value="">

        <div class="rowtag">
            <span class="kin-title">ALTERNATE NOK</span>

            <button type="button" class="btn mini danger removeKin">
                Remove
            </button>
        </div>

        <div class="nokgrid">

            <div class="mini-field">
                <label>Full Name / নাম</label>
                <input type="text"
                       name="contacts[__INDEX__][name]"
                       placeholder="Enter full name"
                       required>
            </div>

            <div class="mini-field">
                <label>Relationship / সম্পর্ক</label>

                <select name="contacts[__INDEX__][relation]">
                    <option value="">Select Relationship</option>
                    <option value="Spouse">Spouse</option>
                    <option value="Father">Father</option>
                    <option value="Mother">Mother</option>
                    <option value="Brother">Brother</option>
                    <option value="Sister">Sister</option>
                    <option value="Son">Son</option>
                    <option value="Daughter">Daughter</option>
                    <option value="Relative">Relative</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mini-field">
                <label>Mobile / WhatsApp</label>

                <input type="tel"
                       name="contacts[__INDEX__][mobile]"
                       placeholder="+8801XXXXXXXXX"
                       required>
            </div>

            <div class="mini-field">
                <label>Email (Optional)</label>

                <input type="email"
                       name="contacts[__INDEX__][email]"
                       placeholder="example@email.com">
            </div>

            <div class="mini-field wide">
                <label>Address & Country / ঠিকানা</label>

                <input type="text"
                       name="contacts[__INDEX__][address]"
                       placeholder="Enter address and country">
            </div>

            <div class="mini-field">
                <label>Contact Priority</label>

                <select name="contacts[__INDEX__][priority]"
                        class="primarySelect">
                    <option value="primary">Primary</option>
                    <option value="alternate" selected>Alternate</option>
                </select>
            </div>

        </div>
    </div>
</template>

@endsection

@section('js')
<script>
$(document).ready(function () {

    let nextIndex = {{ count($rows) }};

    // Add new contact
    $('#addKin').on('click', function () {

        let template = $('#kinTemplate').html();

        template = template.replace(/__INDEX__/g, nextIndex);

        $('#kinRows').append(template);

        nextIndex++;

        updateKinNumbers();
    });

    // Remove contact
    $(document).on('click', '.removeKin', function () {

        $(this).closest('[data-kin-row]').remove();

        updateKinNumbers();
    });

    // Update row titles
    function updateKinNumbers() {

        $('#kinRows [data-kin-row]').each(function (index) {

            let priority = $(this).find('.primarySelect').val();

            let title = priority == 'primary' ? 'PRIMARY' : 'ALTERNATE';

            $(this).find('.kin-title').text(
                title + ' NOK #' + (index + 1)
            );
        });
    }

    // Update title when priority changes
    $(document).on('change', '.primarySelect', function () {
        updateKinNumbers();
    });

    updateKinNumbers();

});
</script>
@endsection
