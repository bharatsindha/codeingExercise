<?php

namespace App\Rules;

use App\Facades\General;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class MonthRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $bookableDate = Carbon::createFromFormat(General::DATE_FORMAT, $value)->monthName;

        return in_array($bookableDate, General::ENABLED_MONTH_LIST);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('messages.month_rule');
    }
}
