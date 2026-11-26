
@extends('layouts.app')

@section('content')

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

    $vesselTypes = [
        'Bulk Carrier',
        'General Cargo Vessel',
        'Container Ship',
        'Oil Tanker',
        'Chemical Tanker',
        'LNG Carrier',
        'LPG Carrier',
        'Gas Carrier',
        'Tugboat',
        'AHTS',
        'PSV',
        'OSV',
        'Passenger Ship',
        'Cruise Ship',
        'Dredger',
        'Ro-Ro Vessel',
        'Other',
    ];

    $rows = old('services', $crewServices->toArray());

    if (empty($rows)) {
        $rows = [[]];
    }
@endphp

<form
    action="{{ route('crews.store.service.recored', $crew->id) }}"
    method="POST"
    class="screen active"
    id="serviceForm"
>
    @csrf

    <div class="sectiontop">
        <div>
            <h2>03. Sea Service Record</h2>
            <p>
                Vessel-wise experience history. Add all previous
                vessel service records for this crew.
            </p>
        </div>

        <a href="{{route('crews.create.stcw',$crew->id)}}" class="btn primary">
            STCW Course
        </a>
    </div>

    <div class="panel">

        <div class="listheader">
            <div>
                <h3>Vessel-wise Experience History</h3>
                <p class="lead">
                    Each vessel has a separate Sea Service record.
                </p>
            </div>
            <button type="button" class="btn primary" id="addSea">
                + Add Sea Service
            </button>
        </div>

        <div id="seaRows">

            @foreach($rows as $index => $service)

                <div class="editableRow" data-sea-row="{{ $index }}">

                    {{-- Existing record ID --}}
                    @if(!empty($service['id']))
                        <input
                            type="hidden"
                            name="services[{{ $index }}][id]"
                            value="{{ $service['id'] }}"
                        >
                    @endif

                    <div class="rowtag">
                        <span class="sea-label">
                            Sea Service #{{ $loop->iteration }}
                        </span>

                        <button
                            type="button"
                            class="btn mini danger removeSea"
                        >
                            Remove
                        </button>
                    </div>

                    <div class="fivegrid">

                        {{-- Rank --}}
                        <div class="mini-field">
                            <label>Rank</label>

                            <select
                                name="services[{{ $index }}][rank]"
                                required
                            >
                                <option value="">Select Rank</option>

                                @foreach($ranks as $rank)
                                    <option
                                        value="{{ $rank }}"
                                        @selected(($service['rank'] ?? '') == $rank)
                                    >
                                        {{ $rank }}
                                    </option>
                                @endforeach
                            </select>

                            @error("services.$index.rank")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Vessel Name --}}
                        <div class="mini-field">
                            <label>Vessel Name</label>

                            <input
                                type="text"
                                name="services[{{ $index }}][vessel_name]"
                                value="{{ $service['vessel_name'] ?? '' }}"
                                placeholder="Enter vessel name"
                                required
                            >

                            @error("services.$index.vessel_name")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Vessel Type --}}
                        <div class="mini-field">
                            <label>Vessel Type</label>

                            <select
                                name="services[{{ $index }}][vessel_type]"
                                required
                            >
                                <option value="">Select Vessel Type</option>

                                @foreach($vesselTypes as $type)
                                    <option
                                        value="{{ $type }}"
                                        @selected(($service['vessel_type'] ?? '') == $type)
                                    >
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- DWT / BHP --}}
                        <div class="mini-field">
                            <label>DWT / BHP</label>

                            <input
                                type="text"
                                name="services[{{ $index }}][dwt_bhp]"
                                value="{{ $service['dwt_bhp'] ?? '' }}"
                                placeholder="e.g. 57,000 DWT"
                            >
                        </div>

                        {{-- IMO Number --}}
                        <div class="mini-field">
                            <label>IMO Number (Optional)</label>

                            <input
                                type="text"
                                name="services[{{ $index }}][imo_number]"
                                value="{{ $service['imo_number'] ?? '' }}"
                                placeholder="IMO 7 digits"
                            >
                        </div>

                        {{-- Vessel Flag --}}
                        <div class="mini-field">
                            <label>Vessel Flag (Optional)</label>

                            <input
                                type="text"
                                name="services[{{ $index }}][vessel_flag]"
                                value="{{ $service['vessel_flag'] ?? '' }}"
                                placeholder="e.g. Liberia"
                            >
                        </div>

                        {{-- Main Engine --}}
                        <div class="mini-field">
                            <label>Main Engine / BHP / GRT (Optional)</label>

                            <input
                                type="text"
                                name="services[{{ $index }}][main_engine]"
                                value="{{ $service['main_engine'] ?? '' }}"
                                placeholder="BHP / kW, engine make, GRT"
                            >
                        </div>

                        {{-- Sign On --}}
                        <div class="mini-field">
                            <label>Sign On</label>

                            <input
                                type="date"
                                name="services[{{ $index }}][sign_on]"
                                class="sign-on"
                                value="{{ !empty($service['sign_on'])
                                    ? \Carbon\Carbon::parse($service['sign_on'])->format('Y-m-d')
                                    : '' }}"
                            >
                        </div>

                        {{-- Sign Off --}}
                        <div class="mini-field">
                            <label>Sign Off</label>

                            <input
                                type="date"
                                name="services[{{ $index }}][sign_off]"
                                class="sign-off"
                                value="{{ !empty($service['sign_off'])
                                    ? \Carbon\Carbon::parse($service['sign_off'])->format('Y-m-d')
                                    : '' }}"
                            >
                        </div>

                        {{-- Duration --}}
                        <div class="mini-field">
                            <label>Duration (Auto)</label>

                            <input
                                type="text"
                                class="duration"
                                readonly
                                value=""
                            >
                        </div>

                    </div>
                </div>

            @endforeach

        </div>

        <div class="total" id="seaTotals">
            Total Recorded Sea Service: 0 days
        </div>

        <div class="layout-help">
            Duration calculation: Sign On থেকে Sign Off পর্যন্ত
            calendar months + remaining days দেখানো হবে।
            Sign Off blank হলে Onboard / Ongoing দেখাবে।
        </div>

    </div>

    <div class="d-flex justify-content-between">

        <a href="{{ route('crews.create.document', $crew->id) }}" class="btn">
            Back
        </a>

        <button type="submit" class="btn primary">
            Save Sea Service →
        </button>

    </div>

</form>

@endsection


@section('js')

<script>
    let seaIndex = {{ count($rows) }};

    const ranks = @json($ranks);
    const vesselTypes = @json($vesselTypes);

    $(document).ready(function () {

        // Add new service row
        $('#addSea').on('click', function () {
            addSea();
        });

        // Remove service row
        $(document).on('click', '.removeSea', function () {
            $(this).closest('.editableRow').remove();

            updateSeaLabels();
            updateSeaTotals();
        });

        // Date change
        $(document).on('change', '.sign-on, .sign-off', function () {
            calculateDuration($(this).closest('.editableRow'));
        });

        // Calculate existing records
        $('#seaRows .editableRow').each(function () {
            calculateDuration($(this));
        });

        updateSeaLabels();
        updateSeaTotals();
    });


    // Add new Sea Service
    function addSea() {

        const index = seaIndex++;

        let rankOptions = `
            <option value="">Select Rank</option>
        `;

        ranks.forEach(function (rank) {
            rankOptions += `
                <option value="${escapeHtml(rank)}">
                    ${escapeHtml(rank)}
                </option>
            `;
        });

        let vesselOptions = `
            <option value="">Select Vessel Type</option>
        `;

        vesselTypes.forEach(function (type) {
            vesselOptions += `
                <option value="${escapeHtml(type)}">
                    ${escapeHtml(type)}
                </option>
            `;
        });

        const html = `
            <div class="editableRow" data-sea-row="${index}">

                <div class="rowtag">
                    <span class="sea-label">New Sea Service</span>

                    <button
                        type="button"
                        class="btn mini danger removeSea">
                        Remove
                    </button>
                </div>

                <div class="fivegrid">

                    <div class="mini-field">
                        <label>Rank</label>

                        <select name="services[${index}][rank]" required>
                            ${rankOptions}
                        </select>
                    </div>

                    <div class="mini-field">
                        <label>Vessel Name</label>

                        <input
                            type="text"
                            name="services[${index}][vessel_name]"
                            placeholder="Enter vessel name"
                            required
                        >
                    </div>

                    <div class="mini-field">
                        <label>Vessel Type</label>

                        <select name="services[${index}][vessel_type]" required>
                            ${vesselOptions}
                        </select>
                    </div>

                    <div class="mini-field">
                        <label>DWT / BHP</label>

                        <input
                            type="text"
                            name="services[${index}][dwt_bhp]"
                            placeholder="e.g. 57,000 DWT"
                        >
                    </div>

                    <div class="mini-field">
                        <label>IMO Number (Optional)</label>

                        <input
                            type="text"
                            name="services[${index}][imo_number]"
                            placeholder="IMO 7 digits"
                        >
                    </div>

                    <div class="mini-field">
                        <label>Vessel Flag (Optional)</label>

                        <input
                            type="text"
                            name="services[${index}][vessel_flag]"
                            placeholder="e.g. Liberia"
                        >
                    </div>

                    <div class="mini-field">
                        <label>Main Engine / BHP / GRT (Optional)</label>

                        <input
                            type="text"
                            name="services[${index}][main_engine]"
                            placeholder="BHP / kW, engine make, GRT"
                        >
                    </div>

                    <div class="mini-field">
                        <label>Sign On</label>

                        <input
                            type="date"
                            name="services[${index}][sign_on]"
                            class="sign-on"
                        >
                    </div>

                    <div class="mini-field">
                        <label>Sign Off</label>

                        <input
                            type="date"
                            name="services[${index}][sign_off]"
                            class="sign-off"
                        >
                    </div>

                    <div class="mini-field">
                        <label>Duration (Auto)</label>

                        <input
                            type="text"
                            class="duration"
                            readonly
                        >
                    </div>

                </div>
            </div>
        `;

        $('#seaRows').append(html);

        updateSeaLabels();
        updateSeaTotals();
    }


    // Escape HTML values
    function escapeHtml(value) {
        return $('<div>').text(value).html();
    }


    // Update row numbers
    function updateSeaLabels() {

        $('#seaRows .editableRow').each(function (index) {

            $(this).find('.sea-label').text(
                'Sea Service #' + (index + 1)
            );

        });
    }


    // Calculate calendar duration
    function calculateDuration(row) {

        row = $(row);

        const signOn = row.find('.sign-on').val();
        const signOff = row.find('.sign-off').val();

        const durationInput = row.find('.duration');

        if (!signOn) {
            durationInput.val('—');
            updateSeaTotals();
            return;
        }

        if (!signOff) {
            durationInput.val('Onboard / Ongoing');
            updateSeaTotals();
            return;
        }

        const start = new Date(signOn + 'T00:00:00Z');
        const end = new Date(signOff + 'T00:00:00Z');

        if (end < start) {
            durationInput.val('Invalid Date');
            updateSeaTotals();
            return;
        }

        let months =
            (end.getUTCFullYear() - start.getUTCFullYear()) * 12 +
            (end.getUTCMonth() - start.getUTCMonth());

        let temp = new Date(start);

        temp.setUTCMonth(temp.getUTCMonth() + months);

        if (temp > end) {
            months--;

            temp = new Date(start);
            temp.setUTCMonth(temp.getUTCMonth() + months);
        }

        const days = Math.round(
            (end - temp) / (1000 * 60 * 60 * 24)
        );

        durationInput.val(`${months} mo ${days} d`);

        updateSeaTotals();
    }


    // Calculate total service days
    function updateSeaTotals() {

        let totalDays = 0;

        $('#seaRows .editableRow').each(function () {

            const signOn = $(this).find('.sign-on').val();
            const signOff = $(this).find('.sign-off').val();

            if (!signOn || !signOff) {
                return;
            }

            const start = new Date(signOn + 'T00:00:00Z');
            const end = new Date(signOff + 'T00:00:00Z');

            if (end >= start) {

                totalDays += Math.round(
                    (end - start) / (1000 * 60 * 60 * 24)
                );

            }
        });

        const months = Math.floor(totalDays / 30);
        const days = totalDays % 30;

        $('#seaTotals').text(
            `Total Recorded Sea Service: ${totalDays} days ≈ ${months} months ${days} days`
        );
    }

</script>

@endsection
