@extends('app')

@section('content')
    <div>
        @if ($nodes->isNotEmpty())
            <h5>Nodes</h5>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Status</th>
                    <th>Node ID</th>
                    <th>Reputation</th>
                    <th>Address</th>
                    <th>Country</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($nodes as $node)
                    <tr>
                        <td>{{ $node->rank }}</td>
                        <td class="text-center">{!! $node->statusIcon !!}</td>
                        <td>
                            <a href="{{ route('nodes.show', ['nodeId' => $node->node_id]) }}">
                                {{ $node->shortId }}
                            </a>
                        </td>
                        <td>{{ $node->reputation }}</td>
                        <td>
                            <a href="{{ route('addresses.show', ['address' => $node->address->ip]) }}">
                                {{ substr($node->address->ip, 0, 20) }}
                            </a>
                        </td>
                        <td>
                            @include('components.flag', ['address' => $node->address])
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <h3>No results.</h3>
        @endif
    </div>
@endsection
