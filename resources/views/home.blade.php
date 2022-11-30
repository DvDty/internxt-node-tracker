@extends('app')

@section('content')
    <div>
        <div class="text-center mb-5">
            <h1>Node tracker</h1>
            <h5>Unofficial tracking service</h5>
            <h5>for Internxt's X Core nodes</h5>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <h5 class="text-center">Most reputation gained last 24h</h5>

                <table class="table text-center">
                    <thead>
                    <tr>
                        <td>Rank</td>
                        <td>Node</td>
                        <td>Gained</td>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($mostReputationGained as $rank => $node)
                        <tr>
                            <td>
                                {{ ++$rank }}
                            </td>

                            <td class="word-break">
                                <a href="{{ route('nodes.show', ['nodeId' => $node->node_id]) }}">
                                    {{ $node->node_id }}
                                </a>
                            </td>

                            <td>
                                {{ $node->reputation_gained }}
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="col-lg-6">
                <h5 class="text-center">Most reputation lost last 24h</h5>

                <table class="table text-center">
                    <thead>
                    <tr>
                        <td>Rank</td>
                        <td>Node</td>
                        <td>Lost</td>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($mostReputationLost as $rank => $node)
                        <tr>
                            <td>
                                {{ ++$rank }}
                            </td>

                            <td class="word-break">
                                <a href="{{ route('nodes.show', ['nodeId' => $node->node_id]) }}">
                                    {{ $node->node_id }}
                                </a>
                            </td>

                            <td>
                                {{ $node->reputation_lost }}
                            </td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <h5 class="text-center">Reputation distribution by country</h5>

                <canvas id="chart-country-distribution"
                        data-countries="{{ implode(',', $countryDistribution['countries']) }}"
                        data-reputations="{{ implode(',', $countryDistribution['reputations']) }}">
                </canvas>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/chart/country-distribution.js') }}"></script>
@endsection
