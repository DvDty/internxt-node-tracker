@extends('app')

@section('content')
    @if ($node)
        <h5 class="word-break">{!! $node->statusIcon !!} {{ $node->node_id }}</h5>
        <hr>

        <div class="row">
            <div class="col-sm-6 mb-5">
                <h6 class="text-center">General information</h6>

                <p>Reputation: {{ $node->reputation }}</p>

                <p>
                    IP Address: <a href="{{ route('addresses.show', ['address' => $node->address->ip]) }}">
                        {{ $node->address->ip }}
                    </a> @include('components.flag', ['address' => $node->address])
                </p>

                <p>Last seen: {{ $node->last_seen }}</p>

                <p>Timeout rate: {{ $node->timeout_rate ?? '-' }}</p>

                <p>Space available: {{ $node->space_available ? 'Yes' : 'No' }}</p>

                <p>Response time: {{ $node->response_time }} ms</p>

                <p>Protocol: {{ $node->protocol->name }}</p>
            </div>

            <div class="col-sm-6 mb-5">
                @if (isset($reputations['values']) && count($reputations['values']) > 1)
                    <div>
                        <h6 class="text-center">Reputation over time</h6>
                        <canvas id="chart-node-reputation"
                                data-reputation-values="{{ implode(',', $reputations['values']) }}"
                                data-reputation-dates="{{ implode(',', $reputations['dates']) }}">
                        </canvas>
                    </div>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 mb-5">
                @if ($statuses['up'] > 0 || $statuses['down'] > 0)
                    <div>
                        <h6 class="text-center">Availability distribution last 7 days</h6>
                        <canvas id="chart-node-status"
                                data-status-up="{{ $statuses['up'] }}"
                                data-status-down="{{ $statuses['down'] }}">
                        </canvas>
                    </div>
                @endif
            </div>

            <div class="col-sm-6 mb-5">
                <div>
                    <h6 class="text-center">Latest status changes</h6>

                    @foreach($statusLogs as $key => $statusLog)
                        @if ($key % 2 === 0)
                            <div style="margin-bottom: 8px">{!! $statusLog !!}</div>
                        @else
                            <div style="margin-bottom: 8px; padding-left: 9px; font-size: .9rem; font-style: italic">
                                <span style="margin-right: 5px">|</span>
                                {{ $statusLog }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @else
        <div>
            <h3>No results.</h3>
        </div>
    @endif
@endsection

@section('js')
    <script type="text/javascript" src="{{ asset('js/chart/node-reputation.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/chart/node-status.js') }}"></script>
@endsection
