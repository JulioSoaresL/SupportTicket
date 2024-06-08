<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($reply) {
            $ticket = $reply->ticket;
            if ($ticket->status === 'Aberto') {
                $ticket->status = 'Em atendimento';
                $ticket->save();
            }
        });
    }


}
