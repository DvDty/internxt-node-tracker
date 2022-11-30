<?php

namespace App\Rules;

use App\Models\Address;
use Illuminate\Contracts\Validation\Rule;

class IpAddressMatchesRequest implements Rule
{
    public function passes($attribute, $value): bool
    {
        return Address::findOrFail($value)->ip === request()->ip();
    }

    public function message(): string
    {
        return 'IP address should match with the request.';
    }
}
