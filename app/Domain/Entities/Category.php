<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return CategoryFactory::new();  
    }   

    protected $fillable = [
        'name',
        'slug'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function metadata(): HasMany
    {
        return $this->hasMany(CategoryMetadata::class);
    }
}
