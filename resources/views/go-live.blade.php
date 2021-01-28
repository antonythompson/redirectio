@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Go live checks</div>

                <div class="card-body">
                    <table>
                        <tr>
                            <th>Domain</th>
                            <th>Down</th>
                            <th>SSL</th>
                            <th>SSL Redirect</th>
                            <th>G Analytics/Tag M</th>
                            <th>IP Correct</th>
                        </tr>

                        @foreach ($websites as $website)
                        @php
                        $latestTest = $website->latestTest();
                        @endphp

                            <tr>
                                <td><strong>{{$website->domain}}</strong></td>
                                <td>{!! $latestTest->is_down !!}</td>
                                <td>{!! $latestTest->ssl !!}</td>
                                <td>{!! $latestTest->ssl_redirect !!}</td>
                                <td>{!! $latestTest->google_analytics !!}</td>
                                <td>{!! $latestTest->ip_correct !!}</td>
                            </tr>

                        @endforeach
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection