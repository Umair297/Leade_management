<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    // Specify the fillable properties
    protected $fillable = ['case_id', 'agent'];


    /**
     * Get the agent name (no relationship since agent is an enum)
     */
    public function getAgentNameAttribute()
    {
        return $this->agent;
    }
    
    // Relationship with the CaseModel
    public function case()
    {
        return $this->belongsTo(CaseModel::class, 'case_id');
    }

    // If storing `user_id`, define this relationship:
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

}
