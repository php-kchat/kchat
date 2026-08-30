<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatWidget extends Model
{
    use HasFactory;

    protected $table = 'chat_widgets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'token',
        'title',
        'icon',
        'department',
        'color',
        'position',
        'is_active',
    ];

    public function sessions()
    {
        return $this->hasMany(WidgetSession::class, 'widget_id');
    }
}
