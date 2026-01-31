<?php

namespace App\Models;

use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class DatabaseNotification extends BaseDatabaseNotification
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return 'laravel_notifications';
    }
}
