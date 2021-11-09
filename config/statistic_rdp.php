<?php

return [
    'last_date' => env('LAST_STATISTIC_DATE', \Carbon\Carbon::now()->subDay()->format('Y-m-d')),
];
