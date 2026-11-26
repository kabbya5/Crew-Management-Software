@extends('layouts.app')
@section('content')
    <div id="page">

        @include('_dashboard_header', ['active_class' => 'correction'])

        <div class="card">
            <div class="head">
                <h2> Cv correction </h2>
                <a href="{{route('crews.create')}}" class="btn primary">Edit / CV</a>
            </div>
            <div class="tablewrap">
                <table>
                    <thead>
                        <tr>
                            <th> Missing Type </th>
                            <th> Missing Item </th>
                            <th> Remark </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($crew->correctionItems as $item)
                            <tr>
                                <td>{{ $item->missing_type }}</td>

                                <td>{{ $item->missing_item ?? '-' }}</td>
                                <td> {{$item->remark}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
