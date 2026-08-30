<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WidgetSession extends Model
{
    use HasFactory;

    protected $table = 'widget_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'visitor_uid',
        'visitor_name',
        'widget_id',
        'agent_id',
        'status',
    ];

    public function widget()
    {
        return $this->belongsTo(ChatWidget::class, 'widget_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function messages()
    {
        return $this->hasMany(WidgetMessage::class, 'session_id');
    }
}
