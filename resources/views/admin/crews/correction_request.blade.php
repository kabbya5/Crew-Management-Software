
@extends('layouts.admin')

@section('content')

@php
    $types = [
        'Document',
        'Sea Service Record',
        'STCW Courses & Certificates',
        'Personal Information',
        'Next of Kin (NOK) / Emergency Contacts',
        'Other'
    ];

    $existingItems = $crew->correctionItems ?? collect();

    $oldTypes = old('missing_type');
    $oldItems = old('missing_item', []);
    $oldRemarks = old('remark', []);
    $oldIds = old('id', []);

    $isOldInput = is_array($oldTypes);

    $rowCount = $isOldInput
        ? max(count($oldTypes), 1)
        : max($existingItems->count(), 1);
@endphp

<div class="correction-wrapper">

    <div class="correction-card">

        <div class="correction-header">
            <h3>CV Correction Request</h3>
            <p>Manage missing and incorrect crew information.</p>
        </div>

        <div class="correction-body">

            @if(session('success'))
                <div class="correction-alert success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="correction-alert error">
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.crews.correction.store', $crew->id) }}"
                  method="POST">

                @csrf

                <div class="correction-section">

                    <div>
                        <h4>Missing / Correction Items</h4>
                        <small>Add or update multiple correction items.</small>
                    </div>

                    <button type="button"
                            class="correction-btn btn-add"
                            id="addRow">
                        + Add Row
                    </button>

                </div>

                <div class="correction-table-wrap">

                    <table class="correction-table">

                        <thead>
                            <tr>
                                <th style="width:25%">Missing Type</th>
                                <th style="width:27%">Missing Item</th>
                                <th style="width:35%">Remark</th>
                                <th style="width:13%">Action</th>
                            </tr>
                        </thead>

                        <tbody id="correctionRows">

                            @for($index = 0; $index < $rowCount; $index++)

                                @php
                                    $item = !$isOldInput
                                        ? ($existingItems[$index] ?? null)
                                        : null;

                                    $selectedType = $isOldInput
                                        ? ($oldTypes[$index] ?? '')
                                        : ($item->missing_type ?? '');

                                    $missingItem = $isOldInput
                                        ? ($oldItems[$index] ?? '')
                                        : ($item->missing_item ?? '');

                                    $remark = $isOldInput
                                        ? ($oldRemarks[$index] ?? '')
                                        : ($item->remark ?? '');

                                    $itemId = $isOldInput
                                        ? ($oldIds[$index] ?? '')
                                        : ($item->id ?? '');
                                @endphp

                                <tr>

                                    <td>
                                        <select name="missing_type[]"
                                                class="correction-input"
                                                required>

                                            <option value="">Select Type</option>

                                            @foreach($types as $option)
                                                <option value="{{ $option }}"
                                                    {{ $selectedType == $option ? 'selected' : '' }}>
                                                    {{ $option }}
                                                </option>
                                            @endforeach

                                        </select>

                                        <input type="hidden"
                                               name="id[]"
                                               value="{{ $itemId }}">
                                    </td>

                                    <td>
                                        <input type="text"
                                               name="missing_item[]"
                                               class="correction-input"
                                               value="{{ $missingItem }}"
                                               placeholder="Enter missing item"
                                               required>
                                    </td>

                                    <td>
                                        <textarea name="remark[]"
                                                  class="correction-input"
                                                  placeholder="Enter remark">{{ $remark }}</textarea>
                                    </td>

                                    <td>
                                        <button type="button"
                                                class="correction-btn btn-remove removeRow">
                                            Remove
                                        </button>
                                    </td>

                                </tr>

                            @endfor

                        </tbody>

                    </table>

                </div>

                <div class="correction-footer">

                    <a href="{{ url()->previous() }}"
                       class="correction-btn btn-cancel">
                        Cancel
                    </a>

                    <button type="submit"
                            class="correction-btn btn-submit">
                        Submit Request
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@section('link')

<style>
    .correction-wrapper {
        max-width: 1200px;
        margin: 30px auto;
        padding: 0 15px;
        color: #1e293b;
    }

    .correction-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,.04);
    }

    .correction-header {
        padding: 20px 24px;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .correction-header h3 {
        font-size: 19px;
        font-weight: 700;
        margin: 0;
        color: #0f172a;
    }

    .correction-header p {
        font-size: 13px;
        color: #64748b;
        margin: 6px 0 0;
    }

    .correction-body {
        padding: 25px;
    }

    .correction-alert {
        padding: 13px 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .correction-alert.success {
        background: #ecfdf5;
        color: #047857;
        border: 1px solid #a7f3d0;
    }

    .correction-alert.error {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .correction-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 18px;
    }

    .correction-section h4 {
        font-size: 15px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .correction-section small {
        color: #64748b;
        font-size: 12px;
    }

    .correction-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 17px;
        border: 0;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: .2s;
    }

    .btn-add {
        background: #059669;
        color: #fff;
    }

    .btn-add:hover {
        background: #047857;
    }

    .btn-remove {
        background: #fee2e2;
        color: #dc2626;
        padding: 8px 12px;
    }

    .btn-remove:hover {
        background: #fecaca;
    }

    .btn-submit {
        background: #2563eb;
        color: #fff;
        min-width: 155px;
    }

    .btn-submit:hover {
        background: #1d4ed8;
    }

    .btn-cancel {
        background: #f1f5f9;
        color: #334155;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
    }

    .correction-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
    }

    .correction-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .correction-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        text-align: left;
        padding: 15px 13px;
        border-bottom: 1px solid #e2e8f0;
    }

    .correction-table td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
    }

    .correction-table tr:last-child td {
        border-bottom: 0;
    }

    .correction-input {
        display: block;
        width: 100%;
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #fff;
        color: #1e293b;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        box-sizing: border-box;
        transition: .2s;
    }

    .correction-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,.1);
    }

    textarea.correction-input {
        min-height: 70px;
        resize: vertical;
    }

    .correction-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
    }

    @media(max-width: 600px) {
        .correction-wrapper {
            padding: 0 10px;
            margin: 15px auto;
        }

        .correction-body {
            padding: 16px;
        }

        .correction-header {
            padding: 16px;
        }

        .correction-section {
            align-items: flex-start;
        }

        .correction-footer {
            flex-direction: column-reverse;
        }

        .correction-footer .correction-btn {
            width: 100%;
        }
    }
</style>

@endsection


@section('js')

<script>
$(document).ready(function () {

    const types = @json($types);

    // Add Row
    $('#addRow').on('click', function () {

        let $row = $('<tr>');

        let $typeCell = $('<td>');

        let $select = $('<select>', {
            name: 'missing_type[]',
            class: 'correction-input',
            required: true
        });

        $select.append(
            $('<option>', {
                value: '',
                text: 'Select Type'
            })
        );

        types.forEach(function(type) {
            $select.append(
                $('<option>', {
                    value: type,
                    text: type
                })
            );
        });

        $typeCell.append($select);

        $typeCell.append(
            $('<input>', {
                type: 'hidden',
                name: 'id[]',
                value: ''
            })
        );

        let $itemCell = $('<td>');

        $itemCell.append(
            $('<input>', {
                type: 'text',
                name: 'missing_item[]',
                class: 'correction-input',
                placeholder: 'Enter missing item',
                required: true
            })
        );

        let $remarkCell = $('<td>');

        $remarkCell.append(
            $('<textarea>', {
                name: 'remark[]',
                class: 'correction-input',
                placeholder: 'Enter remark'
            })
        );

        let $actionCell = $('<td>');

        $actionCell.append(
            $('<button>', {
                type: 'button',
                class: 'correction-btn btn-remove removeRow',
                text: 'Remove'
            })
        );

        $row.append(
            $typeCell,
            $itemCell,
            $remarkCell,
            $actionCell
        );

        $('#correctionRows').append($row);

    });


    // Remove Row
    $(document).on('click', '.removeRow', function () {

        if ($('#correctionRows tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('At least one correction row is required.');
        }

    });

});
</script>

@endsection
