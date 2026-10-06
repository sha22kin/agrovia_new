<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Platform commission
    |--------------------------------------------------------------------------
    |
    | Percent of every consultation fee that Agroviea keeps. A "Platform service fee" row is added
    | to the expert's earnings when a consultation is completed. 0 means no commission.
    |
    */
    'commission_percent' => (float) env('EXPERT_COMMISSION_PERCENT', 0),

    /*
    |--------------------------------------------------------------------------
    | Smallest withdrawal
    |--------------------------------------------------------------------------
    */
    'min_withdrawal' => (float) env('EXPERT_MIN_WITHDRAWAL', 500),

    /*
    |--------------------------------------------------------------------------
    | Experts shown on the home and Experts pages
    |--------------------------------------------------------------------------
    */
    'showcase' => [
        ['name' => 'Dr. Nazmul Hasan', 'role' => 'Crop & Plant Disease Expert', 'degree' => 'PhD, Agronomy (BAU)', 'place' => 'Mymensingh', 'years' => 14, 'price' => 500, 'rating' => 4.9],
        ['name' => 'Dr. Farhana Akter', 'role' => 'Veterinary & Livestock', 'degree' => 'DVM, Bangladesh Agricultural University', 'place' => 'Dhaka', 'years' => 9, 'price' => 600, 'rating' => 4.8],
        ['name' => 'Md. Shahin Alam', 'role' => 'Soil & Fertilizer Specialist', 'degree' => 'MSc, Soil Science', 'place' => 'Rajshahi', 'years' => 11, 'price' => 400, 'rating' => 4.7],
        ['name' => 'Tanvir Ahmed', 'role' => 'Fisheries Expert', 'degree' => 'MSc, Aquaculture', 'place' => 'Khulna', 'years' => 7, 'price' => 450, 'rating' => 4.6],
    ],

];
