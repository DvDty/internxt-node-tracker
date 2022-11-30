<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int ledger_type_id
 * @property string node_id
 * @property ?int value
 */
class LedgerRecord extends Model
{
    protected $fillable = [
        'ledger_type_id',
        'node_id',
        'value',
    ];
}
