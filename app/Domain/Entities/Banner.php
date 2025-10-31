<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'uri',
        'link'
    ];
}
