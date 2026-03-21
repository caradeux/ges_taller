<?php

namespace App\Helpers;

class TextHelper
{
    /**
     * Convert string to Title Case (each word capitalized).
     * "parachoque delantero" → "Parachoque Delantero"
     */
    public static function toTitleCase(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return mb_convert_case(trim($value), MB_CASE_TITLE, 'UTF-8');
    }

    /**
     * Clean license plate: remove non-alphanumeric and uppercase.
     * "AB-CD12" → "ABCD12"
     */
    public static function cleanPlate(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $value));
    }
}
