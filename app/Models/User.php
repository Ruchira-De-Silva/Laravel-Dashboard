<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'full_name',
        'date_of_birth',
        'employee_code',
        'date_hired',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'employee_code'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'date_hired' => 'date',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->full_name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    public function setUsername($username): void
    {
        $this->username = $username;
        // return $this->save();
    }

    public function setFullName(string $fullName): void
    {
        $this->full_name = $fullName;
    }

    public function getDOB(): string
    {
        return $this->date_of_birth->toDateString();
    }

    public function setDOB(string $DOB): void
    {
        $this->date_of_birth = $DOB;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = Hash::make($password);
    }

    public function setEmployeeCode(): void
    {
        do {
            // Generate an 8-digit employee code, ensuring leading zeros
            $employeeCode = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (self::where('employee_code', $employeeCode)->exists());  // Ensure uniqueness
        
        $this->employee_code = $employeeCode;
    }

    public function setRole(string $role): void {
        $validRoles = ['admin', 'manager', 'staff'];
        
        if (in_array($role, $validRoles)) {
            $this->role = $role;
        } else {
            throw new \InvalidArgumentException('Invalid role.');
        }
    }

    public function setDateHired()
    {
        $this->date_hired = now();
    }

    public function updatePassword($password): bool
    {
        // checking the passwords using the method check in from hash class
        if (Hash::check($password, $this->password)) {
            return false;
        }
        
        $this->password = Hash::make($password);
        return $this->save();
    }

    public function workOnProject($projectName)
    {
        // Find the project by its name
        $project = Project::where('name', $projectName)->first();

        // Check if the project exists
        if ($project) {
            // Check if the user is already associated with the project
            if (!$this->projects()->where('project_id', $project->id)->exists()) {
                // Attach the user to the project if not already associated
                $this->projects()->attach($project->id);
            }
        } else {
            // If project doesn't exist, you could return or throw an error
            return "Project not found.";
        }
    }

    public function addSkills(array $skills): void
    {
        // Iterate over each skill in the list
        foreach ($skills as $skillName) {
            // Check if the skill already exists in the skills table
            $skill = Skill::where('skill_name', $skillName)->first();

            // If the skill does not exist, create a new one
            if (!$skill) {
                $skill = Skill::create(['skill_name' => $skillName]);
            }

            // Check if the user is already associated with the skill in the pivot table
            if (!$this->skills()->where('skill_id', $skill->id)->exists()) {
                // Attach the skill to the user
                $this->skills()->attach($skill->id);
            }
        }
    }

    public function __toString(): string
    {
        return "User Details: " . PHP_EOL .
            "Full Name: " . $this->full_name . PHP_EOL .
            "Username: " . $this->username . PHP_EOL .
            "Email: " . $this->email . PHP_EOL .
            "Role: " . $this->role . PHP_EOL .
            "Date of Birth: " . $this->date_of_birth . PHP_EOL .
            "Employee Code: " . $this->employee_code . PHP_EOL .
            "Password: " . $this->password . PHP_EOL; // Password may not be ideal to show directly
    }

    public function addSkill($skillId)
    {
        // Attach the skill to the user
        return $this->skills()->attach($skillId);
    }

    public function addProject($projectId)
    {
        // Attach the skill to the user
        return $this->projects()->attach($projectId);
    }

    public function getProjectsByName()
    {
        return $this->projects->pluck('name');  // Using the `projects` relationship to fetch all associated projects
    }

    public function getProjectsById()
    {
        return $this->projects->pluck('id');  // Using the `projects` relationship to fetch all associated projects
    }

    public function getSkillsByName()
    {
        return $this->skills->pluck('name');  // Using the `skills` relationship to fetch all associated projects
    }

    public function getSkillsById()
    {
        return $this->skills->pluck('id');  // Using the `skills` relationship to fetch all associated projects
    }

    public function getAdditionalSkills()
    {
        $userSkills = $this->getSkillsById();
        
        return Skill::whereNotIn('id', $userSkills)->pluck('name');
    }

    public function getAdditionalProjects()
    {
        $projectSkills = $this->getProjectsById();
        
        return Project::whereNotIn('id', $projectSkills)->pluck('name');
    }
}
