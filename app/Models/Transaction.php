<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    public $incrementing = false; // Indica que a chave primária não é auto-increment
    protected $keyType = 'string'; // O tipo da chave primária é string (UUID)

    protected $fillable = [
        'inscricao',
        'tipo_inscricao',
        'valor',
        'data_hora',
        'localizacao',
        'status',
        'motivo_risco',
    ];

    const STATUS_NORMAL = 'normal';
    const STATUS_ALTO_RISCO = 'alto_risco';

    public static function getStatusOptions()
    {
        return [
            self::STATUS_NORMAL,
            self::STATUS_ALTO_RISCO,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
