
@extends('layouts.app')

@section('link')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
@endsection

@section('content')

<form action="{{ route('crews.store.document', $crew->id) }}" method="POST"
      class="screen active" id="entryScreen" enctype="multipart/form-data">

    @csrf

    <div class="sectiontop">
        <div>
            <h2>02. Crew CV Entry</h2>
            <p>CV-তে যে তথ্য থাকবে, এখানেই সেটি লিখবেন। Main Database-এ একই তথ্য সংরক্ষিত হবে (software development stage)।</p>
        </div>

        <a href="{{route('crews.create.service.recored', $crew->id)}}" type="button" class="btn primary">
            Next: Sea Service →
        </a>
    </div>

    @php
        $documentTypeGroups = [
            'Core Identity / Licenses' => [
                'Passport',
                'Seaman Book / CDC',
                'Certificate of Competency (COC)',
                'Flag State Endorsement / CEC',
                'Seafarer Identity Document (SID)',
                'Medical Fitness Certificate (ENG1 / Equivalent)',
                'Visa / Work Permit',
                'Yellow Fever Vaccination',
            ],

            'Ratings Certificate of Proficiency (COP)' => [
                'COP – Navigational Watch Rating (STCW II/4)',
                'COP – Able Seafarer Deck / AB (STCW II/5)',
                'COP – Engine-Room Watch Rating / Oiler (STCW III/4)',
                'COP – Able Seafarer Engine / ASE (STCW III/5)',
                'COP – Electro-Technical Rating / ETR (STCW III/7)',
            ],

            'Additional Maritime / Trade Certificates' => [
                'GMDSS General Operator Certificate (GOC)',
                'GMDSS Restricted Operator Certificate (ROC)',
                'Dynamic Positioning Operator (DPO) Certificate',
                'Ship’s Cook Certificate',
                'Welding / Welder Competency Certificate',
                'Dangerous Cargo Endorsement',
                'National Rating Certificate / Other Endorsement',
            ],

            'Custom' => [
                'Others — Custom Certificate Name',
            ],
        ];

        $documentRows = old(
            'documents',
            isset($crewDocuments) && $crewDocuments->count()
                ? $crewDocuments->toArray()
                : [[]]
        );
    @endphp

    <div class="panel" id="mainDocumentPanel">

        <div class="listheader">
            <div>
                <h3>Core Maritime Documents & Optional File Upload</h3>
                <p class="lead">
                    Passport / CDC / COC / Ratings COP and other certificates.
                </p>
            </div>

            <button type="button" class="btn primary" id="addDocument">
                + Add Document
            </button>
        </div>

        <div id="otherRows">

            @foreach($documentRows as $index => $document)

                @php
                    $type = old(
                        "documents.$index.document_type",
                        $document['document_type'] ?? ''
                    );

                    $custom = old(
                        "documents.$index.custom_name",
                        $document['custom_name'] ?? ''
                    );

                    $noExpiry = old(
                        "documents.$index.no_expiry",
                        $document['no_expiry'] ?? 0
                    );
                @endphp

                <div class="editableRow" data-doc-row="{{ $index }}">

                    <div class="rowtag">
                        <span class="doclabel">
                            {{ $type ?: 'New Document' }} · Document #{{ $index + 1 }}
                        </span>

                        <button type="button"
                                class="btn mini danger"
                                onclick="removeOther(this)">
                            Remove
                        </button>
                    </div>

                    <div class="docgrid">

                        {{-- Document Type --}}
                        <div class="mini-field">
                            <label>Document Type</label>

                            <select
                                name="documents[{{ $index }}][document_type]"
                                data-document-type
                                onchange="otherChange(this)"
                                class="@error("documents.$index.document_type") is-invalid @enderror"
                            >
                                <option value="">Select Document Type</option>

                                @foreach($documentTypeGroups as $group => $types)
                                    <optgroup label="{{ $group }}">
                                        @foreach($types as $option)
                                            <option value="{{ $option }}"
                                                {{ $type == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            @error("documents.$index.document_type")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Custom Certificate Name --}}
                        <div class="mini-field"
                             data-doc-custom
                             style="{{ $type == 'Others — Custom Certificate Name' ? '' : 'display:none' }}">

                            <label>Custom Certificate Name</label>

                            <input
                                type="text"
                                name="documents[{{ $index }}][custom_name]"
                                data-document-custom
                                value="{{ $custom }}"
                                placeholder="e.g. Specialized Welder Certificate"
                                class="@error("documents.$index.custom_name") is-invalid @enderror"
                            >

                            @error("documents.$index.custom_name")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Document Number --}}
                        <div class="mini-field">
                            <label>Certificate / Document Number</label>

                            <input
                                type="text"
                                name="documents[{{ $index }}][document_number]"
                                data-document-number
                                value="{{ old("documents.$index.document_number", $document['document_number'] ?? '') }}"
                                class="@error("documents.$index.document_number") is-invalid @enderror"
                            >

                            @error("documents.$index.document_number")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Issue Date --}}
                        <div class="mini-field">
                            <label>Issue Date</label>

                            <input
                                type="date"
                                name="documents[{{ $index }}][issue_date]"
                                value="{{ old(
                                    "documents.$index.issue_date",
                                    !empty($document['issue_date'])
                                        ? \Carbon\Carbon::parse($document['issue_date'])->format('Y-m-d')
                                        : ''
                                ) }}"
                                class="@error("documents.$index.issue_date") is-invalid @enderror"
                            >

                            @error("documents.$index.issue_date")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Expiry Date --}}
                        <div class="mini-field">
                            <label>Expiry Date (if applicable)</label>

                            <input
                                type="date"
                                name="documents[{{ $index }}][expiry_date]"
                                data-document-expiry
                                value="{{ old(
                                    "documents.$index.expiry_date",
                                    !empty($document['expiry_date'])
                                        ? \Carbon\Carbon::parse($document['expiry_date'])->format('Y-m-d')
                                        : ''
                                ) }}"
                                {{ $noExpiry ? 'disabled' : '' }}
                                class="@error("documents.$index.expiry_date") is-invalid @enderror"
                            >

                            <span class="toggleline">

                                <input
                                    type="hidden"
                                    name="documents[{{ $index }}][no_expiry]"
                                    value="0"
                                >

                                <input
                                    type="checkbox"
                                    name="documents[{{ $index }}][no_expiry]"
                                    value="1"
                                    data-doc-na
                                    {{ $noExpiry ? 'checked' : '' }}
                                    onchange="otherDocumentNA(this)"
                                >

                                No Fixed Expiry / N/A

                            </span>

                            @error("documents.$index.expiry_date")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- File Upload --}}
                        <div class="mini-field">
                            <label>Choose File (Optional)</label>

                            <input
                                type="file"
                                name="documents[{{ $index }}][file]"
                                accept=".pdf,.jpg,.jpeg,.png,.webp"
                                class="@error("documents.$index.file") is-invalid @enderror"
                            >

                            @if(!empty($document['file_path']))
                                <a target="_blank" href="{{asset($document['file_path'])}}">
                                    Existing file: {{ basename($document['file_path']) }}
                                </a>
                            @endif

                            @error("documents.$index.file")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror

                            <span class="attach-note">
                                PDF / JPG / PNG / WEBP • Optional
                            </span>
                        </div>

                        <input
                            type="hidden"
                            name="documents[{{ $index }}][id]"
                            value="{{ $document['id'] ?? '' }}"
                        >

                        <input
                            type="hidden"
                            name="documents[{{ $index }}][deleted]"
                            value="0"
                            data-document-deleted
                        >

                    </div>
                </div>

            @endforeach

        </div>

        <div class="layout-help">
            Each document can have its own certificate number, issue date,
            expiry date and optional attachment. Select N/A if the document
            has no fixed expiry date.
        </div>

    </div>

    <div class="d-flex justify-content-between mt-4">

        <a href="{{ route('crews.create', $crew->id) }}" class="btn">
            Back
        </a>

        <button type="submit" class="btn primary">
            Next: Sea Service →
        </button>

    </div>
</form>

@endsection

@section('js')

<script>
$(document).ready(function () {

    // Add Document
    $('#addDocument').on('click', function () {
        addOther();
    });

    // Initialize existing document rows independently
    $('[data-doc-row]').each(function () {

        const row = $(this);

        const typeInput = row.find('[data-document-type]');
        const expiryCheckbox = row.find('[data-doc-na]');

        otherChange(typeInput[0]);
        otherDocumentNA(expiryCheckbox[0]);

    });

});
    // Start index after the existing document rows
    let documentIndex = {{ count($documentRows) ? max(array_keys($documentRows)) + 1 : 0 }};


// Add New Document
function addOther() {

    const index = documentIndex++;

    const html = `
        <div class="editableRow" data-doc-row="${index}">

            <div class="rowtag">

                <span class="doclabel">
                    New Document · Document #${index + 1}
                </span>

                <button type="button"
                    class="btn mini danger"
                    onclick="removeOther(this)">
                    Remove
                </button>

            </div>

            <div class="docgrid">

                <!-- Document Type -->
                <div class="mini-field">

                    <label>Document Type</label>

                    <select
                        name="documents[${index}][document_type]"
                        data-document-type
                        onchange="otherChange(this)"
                        required
                    >

                        <option value="">Select Document Type</option>

                        @foreach($documentTypeGroups as $group => $types)

                            <optgroup label="{{ $group }}">

                                @foreach($types as $option)

                                    <option value="{{ $option }}">
                                        {{ $option }}
                                    </option>

                                @endforeach

                            </optgroup>

                        @endforeach

                    </select>

                </div>


                <!-- Custom Certificate Name -->
                <div class="mini-field"
                    data-doc-custom
                    style="display:none">

                    <label>Custom Certificate Name</label>

                    <input
                        type="text"
                        name="documents[${index}][custom_name]"
                        data-document-custom
                        placeholder="e.g. Specialized Welder Certificate"
                    >

                </div>


                <!-- Document Number -->
                <div class="mini-field">

                    <label>Certificate / Document Number</label>

                    <input
                        type="text"
                        name="documents[${index}][document_number]"
                        data-document-number
                    >

                </div>


                <!-- Issue Date -->
                <div class="mini-field">

                    <label>Issue Date</label>

                    <input
                        type="date"
                        name="documents[${index}][issue_date]"
                    >

                </div>


                <!-- Expiry Date -->
                <div class="mini-field">

                    <label>Expiry Date (if applicable)</label>

                    <input
                        type="date"
                        name="documents[${index}][expiry_date]"
                        data-document-expiry
                    >

                    <span class="toggleline">

                        <input
                            type="hidden"
                            name="documents[${index}][no_expiry]"
                            value="0"
                        >

                        <input
                            type="checkbox"
                            name="documents[${index}][no_expiry]"
                            value="1"
                            data-doc-na
                            onchange="otherDocumentNA(this)"
                        >

                        No Fixed Expiry / N/A

                    </span>

                </div>


                <!-- File Upload -->
                <div class="mini-field">

                    <label>Choose File (Optional)</label>

                    <input
                        type="file"
                        name="documents[${index}][file]"
                        accept=".pdf,.jpg,.jpeg,.png,.webp"
                    >

                    <span class="attach-note">
                        PDF / JPG / PNG / WEBP • Optional
                    </span>

                </div>

            </div>

        </div>
    `;

    $('#otherRows').append(html);

}


// Remove Document
function removeOther(element) {

    const row = $(element).closest('[data-doc-row]');

    const id = row.find('input[name$="[id]"]').val();

    if (id) {
        row.find('[data-document-deleted]').val('1');
        row.hide();
    } else {
        // Newly added row, not saved yet
        row.remove();
    }
}


// Document Type Change
function otherChange(element) {

    if (!element) return;

    // Find only the current document row
    const row = $(element).closest('[data-doc-row]');

    if (!row.length) return;

    const value = $(element).val();

    const index = row.data('doc-row');

    const custom = row.find('[data-doc-custom]');

    const customInput = custom.find('[data-document-custom]');


    // Show or hide custom certificate name
    if (value === 'Others — Custom Certificate Name') {

        custom.show();

    } else {

        custom.hide();

        customInput.val('');

    }


    // Update only the current row label
    row.find('.doclabel').text(
        (value || 'New Document') + ' · Document #' + (index + 1)
    );

}


// No Expiry Checkbox Change
function otherDocumentNA(element) {

    if (!element) return;

    // Find only the current document row
    const row = $(element).closest('[data-doc-row]');

    if (!row.length) return;

    const checked = $(element).is(':checked');

    const expiry = row.find('[data-document-expiry]');


    // Disable or enable only this row's expiry input
    expiry.prop('disabled', checked);

    if (checked) {

        expiry.val('');

    }

}

</script>

@endsection
