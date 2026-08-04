<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log an activity.
     *
     * @param string $action (e.g. "create", "update", "delete")
     * @param string $description Human readable description of the action
     * @param Model|null $subject The model instance that was affected
     * @param array|null $oldValues Old state
     * @param array|null $newValues New state
     */
    public static function log(string $action, string $description, ?Model $subject = null, ?array $oldValues = null, ?array $newValues = null)
    {
        $user = Auth::user();

        ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}
