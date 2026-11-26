@extends('layouts.app')
@section('content')
    <div id="page">

       @include('_dashboard_header', ['active_class' => 'stcw'])

        <div class="card">
            <div class="head">
                <h2>STCW Training &amp; Certificates</h2>
                <a href="{{ route('crews.create.stcw', $crew->id) }}" class="btn primary">Edit / renew </a>
            </div>
            <div class="tablewrap">
                <table>
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
                        @foreach ($crew->stcws as $stcw)
                            <tr>
                                <td>{{ $stcw->course_name }}</td>
                                <td>{{ $stcw->certificate_number }}</td>
                                <td>{{ $stcw->issue_date ? \Carbon\Carbon::parse($stcw->issue_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td>{{ $stcw->no_expiry ? \Carbon\Carbon::parse($stcw->no_expiry)->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
