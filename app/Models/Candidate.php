<?php

namespace App\Models;

use App\Models\Scopes\AgentScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'source', 'owner', 'created_by',
    ];

    /**
     * Get the user that owns the candidate.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'owner');
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new AgentScope);
    }
}
