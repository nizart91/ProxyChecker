<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidIpAddressWithPort implements Rule
{
    public function passes($attribute, $value)
    {
        // Check if $value is in the format IP:Port or just IP
        if (preg_match('/^(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})(:\d{1,5})?$/', $value, $matches)) {
            // $matches[1] will contain the IP address part
            // $matches[2] will contain the :Port part if present
            return filter_var($matches[1], FILTER_VALIDATE_IP) !== false;
        }
        return false;
    }

    public function message()
    {
        return 'The :attribute must be a valid IP address with optional port (IP:Port).';
    }
}
