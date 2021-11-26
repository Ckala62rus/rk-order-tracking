<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WinServer extends Model
{
    use HasFactory;

    protected $fillable = [
        'server_name',
        'description',
        'enable',
    ];

    protected $casts = [
        'enable' => 'boolean',
    ];

    /**
     * Get services by relation
     * @return HasMany
     */
    public function services()
    {
        return $this->hasMany(Service::class, 'server_id', 'id');
    }
}
