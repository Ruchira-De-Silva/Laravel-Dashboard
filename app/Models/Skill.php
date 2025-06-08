<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'skill_name',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function addSkill(string $skillName)
    {
        $this->skill_name = $skillName;
        $this->save();
    }

    public function getUsers()
    {
        return $this->user->pluck('username');  // Using the `projects` relationship to fetch all associated projects
    }
}
