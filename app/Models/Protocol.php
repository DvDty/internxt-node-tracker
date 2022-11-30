<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string name
 */
class Protocol extends Model
{
    protected $fillable = [
        'name',
    ];

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }
}
