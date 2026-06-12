<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'title',
        'channel',
        'category',
        'content',
        'sending_mode',
        'pharmacien_id',
        'scheduled_at',
        'sent_at',
    ];

    protected $primaryKey = 'id_message';

    public function rappels()
    {
        return $this->hasMany(
            Rappel::class,
            'message_id',
            'id_message'
        );
    }
}
