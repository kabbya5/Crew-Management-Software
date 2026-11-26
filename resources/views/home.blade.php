@extends('layouts.app')

@section('content')
    <div id="page">
        @include('_dashboard_header', ['active_class' => 'dashboard'])
        <div class="portalhero">
            @if($crew)
            <small>YOUR PERMANENT CREW ID · {{$crew->crew_id}}</small>
            @endif
            <h1>Welcome, {{auth()->user()->name}}</h1>
            @if($crew)
            <p>{{$crew->rank_applied_for}} · {{$crew->nationality}} · {{ ucfirst($crew->status) }} Verification</p>
            @endif

            <div class="actions">
                <a href="{{route('crews.create')}}" class="btn primary">{{$crew ? 'Edit' : 'Create'}} CV</a>
                @if($crew)
                <a class="btn" href="{{route('crews.view',$crew->id)}}">View My CV</a>
                @endif
            </div>
        </div>
        <div class="grid four">
            <div class="card"><small>Profile completeness</small>
                <h2>{{ $crew->profile_completion  ?? 0}}%</h2>
            </div>
            <div class="card"><small>Verified profile</small>
                <h2 style="text-transform: capitalize;">{{$crew->status ?? ''}}</h2>
            </div>
            <div class="card"><small>Latest submission</small>
                <h2>Not submitted</h2>
            </div>
            @if($crew)
            <a href="{{route('crews.correction', $crew->id)}}" class="card"><small>Document alerts</small>
                <h2>{{$crew->correctionItems ? $crew->correctionItems->count() : 0 }}</h2>
            </a>
            @endif
        </div>
        <div class="grid">
            <div class="card">
                <h2>Your next action</h2>
                <p>Keep your documents, sea service and availability current.</p>
                <p>Use <b>Edit CV</b> to make changes, <b>Save Draft</b> to pause, and <b>Update CV</b> to send changes for
                    review.</p><button onclick="portalNav('updates')">View update history</button>
            </div>
            <div class="card">
                <h2>Availability</h2>
                <p>Pending Verification</p>
                <p>Next available: <b>2026-11-25</b></p><button onclick="availabilityRequest('CR-001')">Update
                    availability</button>
                <p class="muted">Office confirmation is required. This does not change an active contract.</p>
            </div>
        </div>
        <footer>English-only crew workspace • Local demonstration • Your permanent Crew ID never changes.</footer>
    </div>
@endsection
