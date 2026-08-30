<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetMessage extends Model
{
    use HasFactory;

    protected $table = 'widget_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'sender',
        'message',
    ];

    public function session()
    {
        return $this->belongsTo(WidgetSession::class, 'session_id');
    }
}
