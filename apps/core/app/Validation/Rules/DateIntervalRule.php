<?php

namespace App\Core\Validation\Rules;

use Carbon\CarbonInterval;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class DateIntervalRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        try {
            new CarbonInterval($value);
        } catch (\Throwable $e) {
            $fail($e->getMessage());
        }
    }
}
