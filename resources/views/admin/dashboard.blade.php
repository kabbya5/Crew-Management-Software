@extends('layouts.admin')
@section('content')
<div id="page">
    <div class="notice"><b>New in v2:</b> 0 CV updates awaiting review. <button
            onclick="go('submissions')">Review submissions</button> <button onclick="entryScreen()">Try crew
            dashboard</button></div>
    <div class="head">
        <div>
            <div class="eyebrow">Operations / </div>
            <h1>Recruitment to rejoining</h1>
            <div class="muted"> crew, ID — CV contract ।</div>
        </div>
        <div class="actions"><button class="primary" onclick="editCrew()">+ Register crew</button></div>
    </div>
    <div class="notice warn"><b>Developer prototype / :</b> software visual workflow reference। Changes stay in
        this browser via localStorage; use JSON backup before moving files or clearing browser data.</div>
    <div class="grid four">
        <a href="{{route('admin.crews.index')}}" class="kpi card">
            Registered crew
            <b>{{$crews->count()}}</b>
            <small>Open
                records →</small>
        </a>
        <button class="kpi card">Open
            applications<b>1</b><small>Open records →</small></button><button class="kpi card"
            onclick="go('onboard')">Onboard crew<b>0</b><small>Open records →</small></button><button
            class="kpi card" onclick="go('alerts')">Action required<b>5</b><small>Open records
                →</small></button></div>
    <div class="grid">
        <div class="card">
            <div class="eyebrow">China-ready cadets</div>
            <h2>24 seats • 14 Deck + 10 Engine</h2>
            <p>3-month pathway · BDT 350,000 per cadet</p>
            <div class="progress"><span style="width:8.333333333333332%"></span></div>
            <p>2 sample enrolments; other seats remain unfilled.</p><button onclick="go('cadets')">Open cadet
                workspace</button>
        </div>
        <div class="card">
            <div class="eyebrow">Experienced seafarers</div>
            <h2>Officers · Engineers · Crew</h2><span class="badge good">No Service Charge</span>
            <p>Rank experience, vacancy matching, screening, approval and joining.</p><button
                onclick="go('experienced')">Open recruitment workspace</button>
        </div>
    </div>
    <div class="card">
        <h2>Next actions</h2>
        <div class="tablewrap">
            <table>
                <thead>
                    <tr>
                        <th>Next action / Crew</th>
                        <th>Owner / Office</th>
                        <th>Due / Priority</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Collect missing certificates<br>Demo Rahim</td>
                        <td>Demo recruiter<br>Bangladesh</td>
                        <td>2026-11-20 <br><span class="badge ">High</span></td>
                        <td><span class="badge ">Open</span></td>
                        <td><button onclick="editEntity('tasks','TASK-001')">Edit</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <h2>Connected lifecycle</h2>
        <div class="steps">
            <div class="step">1 · CV &amp; documents</div>
            <div class="step">2 · Vacancy / cadet batch</div>
            <div class="step">3 · Interview &amp; approval</div>
            <div class="step">4 · Sign-on &amp; contract</div>
            <div class="step">5 · Payroll &amp; sign-off</div>
            <div class="step">6 · Rejoining</div>
        </div>
    </div>
    <footer>MARITIME UNIFIED HRMS • Developer reference v2.0 • Local browser data only. No real login, cloud
        sharing, banking or file storage.</footer>
</div>
@endsection
    

