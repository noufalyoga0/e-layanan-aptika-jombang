<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_code',
        'opd_name',
        'service_id',
        'service_name',
        'detail_target',
        'status',
        'priority',
        'notes',
        'assigned_to',
        'disp_notes',
        'tech_result',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(TicketLog::class, 'ticket_id');
    }
}
