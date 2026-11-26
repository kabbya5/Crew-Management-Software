<div class="tablewrap">
    <table>
        <thead>
            <tr>
                <th>Crew / ID</th>
                <th>Rank / Nationality</th>
                <th>Phone </th>
                <th>Track</th>
                <th>Readiness</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($crews as $crew)
                <tr>
                    <td><b>{{ $crew->full_name }}</b><br><small>{{ $crew->crew_id }}</small></td>
                    <td>{{ $crew->rank_applied_for }}<br>{{ $crew->nationality }}</td>
                    <td>{{ $crew->mobile }}</td>
                    <td>
                        <span class="badge ">Experienced</span><br><span class="badge good">No Service
                            Charge</span>
                    </td>
                    <td>
                        @if($crew->documents->count() > 0)
                        <span class="badge warn">
                            {{$crew->correctionItems ? $crew->correctionItems->count() : 0 }}
                             Document gaps
                        </span>
                        @else 
                         <span class="badge warn">
                            Missing Document
                        </span>
                        @endif
                    </td>
                    <td><span class="badge">{{ $crew->status }}</span></td>
                    <td>
                        <a href="{{ route('admin.crews.view', $crew->id) }}">View</a>
                        <a href="{{route('crews.create', ['crew_id' => $crew->id]) }}" style="margin-left: 10px"> Edit </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<x-custom-pagination :data="$crews" />
