@extends('layouts.app')
@section('content')
    <section class="screen active" id="previewScreen">
        <div class="cvwrap">
            <div class="cvDocument" id="cvDocument">
                <div class="cvpage">
                    <div class="cvbrand">
                        <div>
                            <h1 id="pBrand">CENTRAL CREW PROFILE</h1>
                            <span id="pBrandTag">
                                STANDARDIZED SEAFARER
                                CURRICULUM VITAE
                            </span>
                        </div>
                        <div class="cv-brandmark" id="pMark">CV</div>
                    </div>

                    <div class="cvtitle">
                        <div>
                            <h2 id="pName" style="text-transform: uppercase">{{$crew->full_name}}</h2>
                            <div class="subtitle" id="pRank">{{$crew->rank_applied_for}}</div>
                            <div class="status">Crew ID: <span id="pId">{{$crew->crew_id}}</span> · <span
                                    id="pNation">{{$crew->nationality}}</span>
                                </div>
                        </div>

                        <div class="photo" id="cvCrewPhoto">
                            <img src="{{asset($crew->photo)}}">
                        </div>
                    </div>
                    <section class="cvSection">
                        <h3>01 · Personal &amp; Contact Details</h3>
                        <div class="cvfields" id="pPersonal">
                            <div class="item"><b>Nationality</b><span>{{$crew->nationality}}</span></div>
                            <div class="item"><b>Date of Birth</b>
                                <span>{{ \Carbon\Carbon::parse($crew->date_of_birth)->format('d/m/Y') }}</span>
                            </div>

                            <div class="item"><b>Gender</b><span>{{$crew->gender}}</span></div>

                            <div class="item"><b>Mobile / WhatsApp</b><span>{{$crew->mobile}}</span></div>
                            <div class="item"><b>Email</b><span>{{$crew->email}}</span></div>
                            <div class="item"><b>Current Location</b><span>{{$crew->current_location}}</span></div>
                            <div class="item"><b>Place of Birth</b><span>{{$crew->place_of_birth}}</span></div>
                        </div>
                    </section>
                    <section class="cvSection">
                        <h3>02 · Physical Details / PPE Sizing</h3>
                        <div class="cvfields" id="pPhysical">
                            <div class="item"><b>Height</b><span>{{$crew->height}}</span></div>
                            <div class="item"><b>Weight</b><span>{{$crew->weight}}</span></div>
                            <div class="item"><b>Shoe / Size Standard</b><span>{{$crew->shoe_size_standard}}</span></div>
                            <div class="item"><b>Shoe / Boot Size</b><span>{{$crew->shoe_size}}</span></div>
                            <div class="item"><b>Boiler Suit / Coverall</b><span>{{$crew->boiler_suit_size}} </span></div>
                            <div class="item"><b>Blood Group</b><span>{{$crew->blood_group}}</span></div>
                        </div>
                    </section>
                    <section class="cvSection">
                        <h3>03 · Next of Kin / Emergency Contact (Restricted)</h3>
                        
                        <div class="cvfields" id="pKin">
                            @foreach($crew->nextOfKins as $contact)
                            <div class="item">
                                <b>{{strtoupper($contact->priority)}} · {{$contact->relationship}}</b>
                                <span>{{$contact->full_name . ' '}} | {{$contact->mobile . " "}} |
                                    {{$contact->email . '  |'}} {{$contact->address}}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </section>
                    <section class="cvSection">
                        <h3>04 · Professional Summary</h3>
                        <div class="cvfields" id="pProfessional">
                            <div class="item"><b>Present Rank</b><span>{{$crew->rank_applied_for}}</span></div>
                            <div class="item"><b>Availability</b><span> {{ \Carbon\Carbon::parse($crew->available_from)->format('d/m/Y') }}</span></div>
                            <div class="item"><b>English Proficiency</b><span>{{$crew->english_proficiency}}</span></div>
                            <div class="item"><b>Chinese Proficiency</b><span>{{$crew->chinese_proficiency}}</span></div>
                        </div>
                    </section>
                    <section class="cvSection">
                        <h3>05 · Maritime Documents &amp; Validity</h3>
                        <table class="cvtable">
                            <thead>
                                <tr>
                                    <th>Document</th>
                                    <th>Number / Reference</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date</th>
                                </tr>
                            </thead>
                            <tbody id="pOther">
                                @foreach ($crew->documents as $document)
                                    <tr>
                                        <td>{{$document->document_type}}</td>
                                        <td>{{$document->document_number}}</td>
                                        <td>{{ \Carbon\Carbon::parse($document->issue_date)->format('d/m/Y') }}</td>
                                        <td>{{ $document->expiry_date ? \Carbon\Carbon::parse($document->expiry_date)->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                    <section class="cvSection">
                        <h3>06 · Sea Service Record / Seagoing Experience</h3>
                        <table class="cvtable">
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
                                @foreach($crew->services as $service)
                                @php
                                    $signOn = \Carbon\Carbon::parse($service->sign_on);
                                    $signOff = $service->sign_off
                                        ? \Carbon\Carbon::parse($service->sign_off)
                                        : now();

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
                                    <td>{{$service->rank}}</td>
                                    <td>{{$service->vessel_name}}</td>
                                    <td>{{$service->vessel_type}}</td>
                                    <td>{{$service->dwt_bhp}}</td>
                                    <td>{{ $signOn->format('d/m/Y') }}</td>
                                    <td>{{ $service->sign_off ? $signOff->format('d/m/Y') : '-' }}</td>
                                    <td><b>{{ trim($duration) }}</b></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        @php
                            $totalYears = intdiv($totalDays, 365);
                            $remaining = $totalDays % 365;
                            $totalMonths = intdiv($remaining, 30);
                            $remainingDays = $remaining % 30;
                        @endphp

                        <div class="cvTotal" id="pTotal">
                            TOTAL RECORDED SEA SERVICE:
                            {{ $totalDays }} days
                            (approx.
                            {{ $totalYears > 0 ? $totalYears . ' years ' : '' }}
                            {{ $totalMonths }} months
                            {{ $remainingDays }} days)
                        </div>
                    </section>
                    <section class="cvSection">
                        <h3>07 · STCW Training &amp; Certificates</h3>
                        <table class="cvtable">
                            <colgroup>
                                <col style="width:44%">
                                <col style="width:18%">
                                <col style="width:17%">
                                <col style="width:21%">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Course / Certificate Name</th>
                                    <th>Certificate No.</th>
                                    <th>Issue Date</th>
                                    <th>Expiry Date / N/A</th>
                                </tr>
                            </thead>
                            <tbody id="pCourses">
                                @foreach($crew->stcws as $stcw)
                                <tr>
                                    <td>{{$stcw->course_name}}</td>
                                    <td>{{$stcw->certificate_number}}</td>
                                    <td>{{ $stcw->issue_date ? \Carbon\Carbon::parse($stcw->issue_date)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $stcw->no_expiry ? \Carbon\Carbon::parse($stcw->no_expiry)->format('d/m/Y') : '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                    <section class="cvSection">
                        <h3>08 · Remarks / Availability</h3>
                        <div class="cvfields" id="pRemarks">
                            <div class="item">
                                <b>Available From</b>
                                <span>{{ $crew->available_from ? \Carbon\Carbon::parse($crew->available_from)->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div class="item">
                                <b>Remarks</b>
                                <span>
                                    {{$crew->remarks}}
                                </span>
                            </div>
                        </div>
                    </section>
                    <div class="cvfooter"><span id="pFooter">Central Crew Database · Confidential Recruitment
                            Document</span><span>Generated from Crew Profile • Sample CV</span></div>
                </div>
            </div>
        </div>
    </section>
@endsection
