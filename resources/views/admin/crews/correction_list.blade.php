@extends('layouts.admin')
@section('content')
    <div id="page">
        <div class="head">
            <div>
                <div class="eyebrow"> Verification desk </div>
                <h1>New submissions & CV updates</h1>
                <div class="muted">Approved data stays unchanged until a reviewer accepts the submitted revision.
                </div>
            </div>
        </div>
        <div class="card">
            <div class="filters">
                <input type="text" id="search" placeholder="Search name, rank or Crew ID">

                <select id="rank">
                    <option value="">Select / blank</option>
                    <option value="Master / Captain">Master / Captain</option>
                    <option value="Chief Officer">Chief Officer</option>
                    <option value="Second Officer">Second Officer</option>
                    <option value="Third Officer">Third Officer</option>
                    <option value="Chief Engineer">Chief Engineer</option>
                    <option value="Second Engineer">Second Engineer</option>
                    <option value="Third Engineer">Third Engineer</option>
                    <option value="Fourth Engineer">Fourth Engineer</option>
                    <option value="ETO">ETO</option>
                    <option value="Bosun">Bosun</option>
                    <option value="Able Seafarer Deck (AB)">Able Seafarer Deck (AB)</option>
                    <option value="Ordinary Seaman (OS)">Ordinary Seaman (OS)</option>
                    <option value="Fitter">Fitter</option>
                    <option value="Oiler">Oiler</option>
                    <option value="Wiper">Wiper</option>
                    <option value="Cook">Cook</option>
                    <option value="Steward">Steward</option>
                    <option value="Deck Cadet">Deck Cadet</option>
                    <option value="Engine Cadet">Engine Cadet</option>
                    <option value="Other">Other</option>
                </select>

                <select id="nation">
                    <option value="">Select / blank</option>
                    <option value="Bangladesh">Bangladesh</option>
                    <option value="India">India</option>
                    <option value="Myanmar">Myanmar</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Indonesia">Indonesia</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="China">China</option>
                    <option value="Sri Lanka">Sri Lanka</option>
                    <option value="Other">Other</option>
                </select>

                <select id="status">
                    <option value="">Select / blank</option>
                    <option value="pending">Pending Verification</option>
                    <option value="Available">Available</option>
                    <option value="Onboard">Onboard</option>
                    <option value="On Leave">On Leave</option>
                    <option value="Available for Rejoining">Available for Rejoining</option>
                    <option value="Availability Confirmation Required">Availability Confirmation Required</option>
                    <option value="Cadet Pipeline">Cadet Pipeline</option>
                    <option value="Not Yet Available">Not Yet Available</option>
                </select>
            </div>
            <div id="crewtable">
                @include('admin.crews._crew_list')
            </div>
        </div>
        <footer>MARITIME UNIFIED HRMS • Developer reference v2.0 • Local browser data only. No real login, cloud sharing,
            banking or file storage.</footer>
    </div>
@endsection
@section('js')
<script>

$(document).ready(function () {

    let timer;

    function filterCrews(url = "{{ route('admin.crews.correction.list') }}") {

        $.ajax({
            url: url,
            type: "GET",

            data: {
                search: $('#search').val(),
                rank: $('#rank').val(),
                nation: $('#nation').val(),
                status: $('#status').val()
            },

            beforeSend: function () {
                $('#crewtable').css('opacity', '0.5');
            },

            success: function (response) {
                $('#crewtable').html(response);
            },

            error: function (xhr) {
                alert('Something went wrong!');
            },

            complete: function () {
                $('#crewtable').css('opacity', '1');
            }
        });
    }

    // Search debounce
    $('#search').on('input', function () {
        clearTimeout(timer);

        timer = setTimeout(function () {
            filterCrews();
        }, 400);
    });

    // Dropdown filters
    $('#rank, #nation, #status').on('change', function () {
        filterCrews();
    });

        // Custom AJAX pagination
    $(document).on('click', '#crewtable .pagination-buttons a', function (e) {
        e.preventDefault();

        let url = $(this).attr('href');

        filterCrews(url);
    });
});

</script>
@endsection
