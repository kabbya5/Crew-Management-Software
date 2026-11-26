@extends('layouts.app')
@section('content')
    <div id="page">

       @include('_dashboard_header', ['active_class' => 'documents'])

        <div class="card">
            <div class="head">
                <h2>My documents &amp; certificates</h2>
                <a href="{{route('crews.create.document', $crew->id)}}" class="btn primary">Edit / renew documents</a>
            </div>
            <div class="tablewrap">
                <table>
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Number</th>
                            <th>File</th>
                            <th>Issue Date</th>
                            <th>Expiry</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($crew->documents as $document)
                            <tr>
                                <td>{{ $document->document_type }}</td>

                                <td>{{ $document->document_number ?? '-' }}</td>
                                <td>
                                    @if ($document->file_path)
                                        <a href="{{ asset($document->file_path) }}" target="_blank">
                                            View Document
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td>
                                    {{ $document->issue_date
                                        ? \Carbon\Carbon::parse($document->issue_date)->format('d/m/Y')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $document->expiry_date
                                        ? \Carbon\Carbon::parse($document->expiry_date)->format('d/m/Y')
                                        : '-' }}
                                </td>

                                <td>
                                    @if (!$document->expiry_date)
                                        <span class="status active">No Expiry</span>
                                    @elseif (\Carbon\Carbon::parse($document->expiry_date)->isPast())
                                        <span class="status expired">Expired</span>
                                    @else
                                        <span class="status active">Valid</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="mt-3">
                Upload renewed documents through Edit CV and select Update CV. Previous approved evidence remains in version
                history.
            </p>
        </div>
        <footer>English-only crew workspace • Local demonstration • Your permanent Crew ID never changes.</footer>
    </div>
@endsection
