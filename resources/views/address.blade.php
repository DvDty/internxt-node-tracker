@extends('app')

@section('content')
    <h5 class="word-break">IP Address: {{ $address->ip }} @include('components.flag', ['address' => $address])</h5>
    <hr>

    <div class="row">
        <div class="col-sm-6 mb-5">
            @if ($address->numberOfNodes)
                <h6 class="text-center">Nodes ({{ $address->numberOfNodes }})</h6>
                <table class="table table-responsive">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Node ID</th>
                        <th>Reputation</th>
                        <th>Space Available</th>
                        <th>Response</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($address->nodes->sortByDesc('reputation') as $node)
                        <tr>
                            <td>
                                {!! $node->statusIcon !!}
                            </td>

                            <td>
                                <a href="{{ route('nodes.show', ['nodeId' => $node->node_id]) }}">
                                    {{ $node->shortId }}
                                </a>
                            </td>

                            <td>{{ $node->reputation }}</td>

                            <td>{{ $node->space_available ? 'Yes' : 'No' }}</td>

                            <td>{{ round($node->response_time) }} ms</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="col-sm-6 mb-5">
            <div>
                <h6 class="text-center">Nodes reputation over time</h6>
                <canvas id="chart-node-reputation-multiple"></canvas>
            </div>
        </div>

        <div class="col-sm-6">
            @if ($showEmail)
                <form>
                    <div class="form-group">
                        <label for="change-email">Email address</label>
                        <input type="email"
                               class="form-control"
                               id="change-email"
                               aria-describedby="change-email-help"
                               placeholder="Enter email"
                               name="change-email"
                               value="{{ $address->email ?? '' }}">

                        <div id="change-email-feedback" class="hidden"></div>
                        <small id="change-email-help" class="form-text text-muted">
                            Your email will be used for notifying you when certain event happens. For example a new
                            version of X-Core is out, or your node has been offline for more than an hour.
                        </small>
                    </div>

                    <input type="hidden" name="addressId" value="{{ $address->id }}">
                </form>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script type="application/javascript">
        let chartDataJson = {!! $chartDataJson !!};
    </script>

    <script type="text/javascript" src="{{ asset('js/chart/node-reputation-multiple.js') }}"></script>
@endsection
