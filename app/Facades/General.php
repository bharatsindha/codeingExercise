<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

Class General extends Facade
{
    const CACHE_KEY = 'bookings';
    const DATE_FORMAT = 'd/m/Y';    // date format
    const ENABLED_MONTH_LIST = ['June', 'July', 'August'];  // allowed month list
    const GUEST_LIMIT = 8;  // maximum number of guests limit
}
