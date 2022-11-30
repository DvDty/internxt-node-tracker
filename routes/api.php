<?php

use App\Models\Node;
use Illuminate\Support\Facades\Route;

Route::get('/nodes/{nodeId}', function (string $nodeId) {
    $node = Node::with('address.country')->where('node_id', $nodeId)->firstOrFail();

    return response()->json($node);
});
