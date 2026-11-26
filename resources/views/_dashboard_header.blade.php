<div class="portalnav">
    <a href="{{ route('home') }}"
       class="btn {{ ($active_class ?? '') == 'dashboard' ? 'active' : '' }}">
        Dashboard
    </a>

    <button type="button"
       class="{{ ($active_class ?? '') == 'cv' ? 'active' : '' }}">
        My CV
    </button>

    @if($crew)

    <a href="{{ route('crew.document.view', $crew->id) }}"
       class="btn {{ ($active_class ?? '') == 'documents' ? 'active' : '' }}">
        Documents
    </a>

    <a href="{{ route('crew.sea.service.view', $crew->id) }}"
       class="btn {{ ($active_class ?? '') == 'sea_service' ? 'active' : '' }}">
        Sea Service
    </a>

    <button type="button"
       class="{{ ($active_class ?? '') == 'updates' ? 'active' : '' }}"
       onclick="portalNav('updates')">
        Update History
    </button>

    <a href="{{ route('crew.stcw.view', $crew->id) }}"
       class="btn {{ ($active_class ?? '') == 'stcw' ? 'active' : '' }}">
        STCW
    </a>

    <a href="{{ route('crews.correction', $crew->id) }}"
       class="btn {{ ($active_class ?? '') == 'correction' ? 'active' : '' }}">
        Correction Request
    </a>
    @endif

    <a href="{{ route('account') }}"
       class="btn {{ ($active_class ?? '') == 'account' ? 'active' : '' }}">
        My Account
    </a>
</div>