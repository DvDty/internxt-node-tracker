<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property string node_id
 * @property int address_id
 * @property string last_seen
 * @property int port
 * @property int protocol_id
 * @property string user_agent
 * @property double timeout_rate
 * @property string ip
 * @property bool space_available
 * @property bool status
 * @property int reputation
 * @property double response_time
 * @property Protocol protocol
 * @property Address address
 * @property string shortId
 * @property string statusIcon
 */
class Node extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'node_id',
        'address_id',
        'last_seen',
        'port',
        'protocol_id',
        'user_agent',
        'timeout_rate',
        'ip',
        'space_available',
        'reputation',
        'response_time',
        'status',
        'country',
    ];

    protected $appends = [
        'shortId',
        'statusIcon',
    ];

    public function protocol(): BelongsTo
    {
        return $this->belongsTo(Protocol::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function getShortIdAttribute(): string
    {
        return substr($this->node_id, 0, 5)
            . '...'
            . substr($this->node_id, -5);
    }

    public function getStatusIconAttribute(): string
    {
        if ($this->status === null) {
            return '';
        }

        $statusClass = $this->status ? 'status-on' : 'status-off';

        return '<span class="status ' . $statusClass . '"></span>';
    }

    public function updateStatus(): void
    {
        try {
            $this->status = false;

            if ($fp = fsockopen($this->address->ip, $this->port, $e, $e, 1)) {
                $this->status = true;
            }

            fclose($fp);
        } finally {
            $this->save();

            $record = new LedgerRecord();
            $record->ledger_type_id = 2;
            $record->node_id = $this->id;
            $record->value = $this->status;
            $record->save();
        }
    }

    public function getCountryAttribute(): Country
    {
        return $this->address->country;
    }
}
