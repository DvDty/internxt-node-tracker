<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int id
 * @property string ip
 * @property int reputation
 * @property int country_id
 * @property string email
 * @property Node[] nodes
 * @property Country country
 * @property int numberOfNodes
 */
class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'reputation',
        'country_id',
        'email',
    ];

    protected $appends = [
        'numberOfNodes',
    ];

    protected $hidden = [
        'email',
    ];

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function getNumberOfNodesAttribute(): int
    {
        return count($this->nodes);
    }

    public function recalculateReputation()
    {
        $this->reputation = 0;

        foreach ($this->nodes as $node) {
            $this->reputation += $node->reputation;
        }

        $this->save();
    }
}
