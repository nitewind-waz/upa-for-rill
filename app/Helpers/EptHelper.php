<?php

namespace App\Helpers;

class EptHelper
{
    /**
     * Tentukan level berdasarkan total score
     */
    public static function getLevel($score)
    {
        if (!is_numeric($score)) {
            return null;
        }

        if ($score <= 393) {
            return "ELEMENTARY";
        } elseif ($score <= 473) {
            return "LOW INTERMEDIATE";
        } elseif ($score <= 510) {
            return "INTERMEDIATE";
        } elseif ($score <= 587) {
            return "UPPER INTERMEDIATE";
        }

        return "ADVANCED";
    }
}
