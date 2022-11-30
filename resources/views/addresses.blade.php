@extends('app')

@section('content')
    <div>
        @if ($addresses->isNotEmpty())
            <h5>IP Addresses</h5>
            <table class="table table-responsive">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Address</th>
                    <th># of nodes</th>
                    <th>Total reputation</th>
                    <th>Nodes</th>
                    <th>Country</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($addresses as $key => $address)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>
                            <a href="{{ route('addresses.show', ['address' => $address->ip]) }}">
                                {{ substr($address->ip, 0, 20) }}
                            </a>
                        </td>
                        <td>{{ $address->numberOfNodes }}</td>
                        <td>{{ $address->reputation }}</td>
                        <td>
                            @foreach($address->nodes->sortByDesc('reputation')->take(5) as $node)
                                <a href="{{ route('nodes.show', ['nodeId' => $node->node_id]) }}">
                                    {{ $node->shortId }}
                                </a>
                                ({{ $node->reputation }}) <br>
                            @endforeach

                            @if ($address->numberOfNodes > 5)
                                ... {{ $address->numberOfNodes - 5 }} more
                            @endif
                        </td>
                        <td>
                            @include('components.flag', ['address' => $address])
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
