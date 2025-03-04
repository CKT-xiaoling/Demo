<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Music extends Model
{
    use HasFactory, Notifiable;

    /**
     * 与模型关联的表名
     *
     * @var string
     */
    protected $table = 'music';

    /**
     * 指示是否自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = true;

    protected $fillable = ['id', 'name', 'audio_name', 'file_size', 'hash', 'time_length', 'created_at', 'updated_at'];
}
