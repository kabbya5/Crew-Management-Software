@extends('layouts.app')
@section('content')
    <div id="page">
        @include('_dashboard_header', ['active_class' => 'sea_service'])
        <div class="card">
            <div class="head">
                <h2>My sea service</h2>
                <a class="btn primary" href="{{ route('crews.create.service.recored', $crew->id) }}">Add external sea
                    service</a>
            </div>
            <div class="tablewrap">
                <table>
                    <colgroup>
                        <col style="width:15%">
                        <col style="width:18%">
                        <col style="width:15%">
                        <col style="width:12%">
                        <col style="width:13%">
                        <col style="width:13%">
                        <col style="width:14%">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Vessel Name</th>
                            <th>Vessel Type</th>
                            <th>DWT / BHP</th>
                            <th>Sign On</th>
                            <th>Sign Off</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody id="pSea">
                        @php
                            $totalDays = 0;
                        @endphp
                        @forelse($crew->services as $service)
                            @php
                                $signOn = \Carbon\Carbon::parse($service->sign_on);
                                $signOff = $service->sign_off ? \Carbon\Carbon::parse($service->sign_off) : now();

                                $days = $signOn->diffInDays($signOff) + 1;
                                $totalDays += $days;

                                $years = intdiv($days, 365);
                                $remainingDays = $days % 365;
                                $months = intdiv($remainingDays, 30);
                                $remainingDays = $remainingDays % 30;

                                $duration = '';

                                if ($years > 0) {
                                    $duration .= $years . ' yr ';
                                }

                                if ($months > 0) {
                                    $duration .= $months . ' mo ';
                                }

                                if ($remainingDays > 0) {
                                    $duration .= $remainingDays . ' d';
                                }
                            @endphp
                            <tr>
                                <td>{{ $service->rank }}</td>
                                <td>{{ $service->vessel_name }}</td>
                                <td>{{ $service->vessel_type }}</td>
                                <td>{{ $service->dwt_bhp }}</td>
                                <td>{{ $signOn->format('d/m/Y') }}</td>
                                <td>{{ $service->sign_off ? $signOff->format('d/m/Y') : '-' }}</td>
                                <td><b>{{ trim($duration) }}</b></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty">No records / </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @php
                    $totalYears = intdiv($totalDays, 365);
                    $remaining = $totalDays % 365;
                    $totalMonths = intdiv($remaining, 30);
                    $remainingDays = $remaining % 30;
                @endphp

                @if ($remainingDays)
                    <div class="cvTotal" id="pTotal">
                        TOTAL RECORDED SEA SERVICE:
                        {{ $totalDays }} days
                        (approx.
                        {{ $totalYears > 0 ? $totalYears . ' years ' : '' }}
                        {{ $totalMonths }} months
                        {{ $remainingDays }} days)
                    </div>
                @endif
            </div>
            <p>Experience gained through another company may be added for verification. Company contract history cannot be
                edited by crew.</p>

            <button class="btn" style="width: fit-content">Request a correction</button>
        </div>
        <footer>English-only crew workspace • Local demonstration • Your permanent Crew ID never changes.</footer>
    </div>
@endsection
