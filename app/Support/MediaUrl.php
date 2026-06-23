<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MediaUrl
{
    /**
     * Resolve a stored image/file value to a usable URL.
     * The value is either a relative storage path (uploaded file) or
     * a full external URL (URL mode) — same column holds both.
     */
    public static function resolve(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        return str_starts_with($value, 'http') ? $value : '/storage/' . $value;
    }

    /**
     * Delete a previously stored file from the public disk, but only if it's
     * a local storage path — never attempt to delete an external URL.
     */
    public static function deleteIfLocal(?string $value): void
    {
        if ($value && !str_starts_with($value, 'http')) {
            Storage::disk('public')->delete($value);
        }
    }
}
