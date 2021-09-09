<?php

if (!function_exists('checkIsActive')) {
    function checkIsActive($route)
    {
        if (request()->url() === route($route)) {
            return 'active';
        }
    }
}


