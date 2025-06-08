<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;
    }

    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;
    }

    public function setStatus($status)
    {
        $validStatus = ['pending', 'ongoing', 'completed'];

        if (in_array($status, $validStatus)) {
            $this->status = $status;
            $this->save();
        } else {
            throw new \InvalidArgumentException('Invalid status.');
        }        
    }

    public function getUsers()
    {
        return $this->users()->pluck('username');  // Using the `projects` relationship to fetch all associated projects
    }
}
