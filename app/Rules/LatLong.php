<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LatLong implements Rule
{
    protected $lat = false;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($isLat)
    {
        $this->lat = $isLat;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->lat) {
            return preg_match('/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/', $value);
        }

        return preg_match('/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid lat/long data.';
    }
}
