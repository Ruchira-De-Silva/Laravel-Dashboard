<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Login extends Model
{
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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
        ];
    }

    public function login(string $username, string $password)
    {
        $user = User::where('username', $username)->first();
        
        if ($user === null) {
            return redirect('/login')->with('error', 'No username found.');
        }
        
        if (Hash::check($password, $user->password)) {
            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard')
                    ->with('error', 'Access Denied to that page.');

                case 'manager':
                    return redirect()->route('manager.dashboard')
                    ->with('error', 'Access Denied to that page.');

                case 'staff':
                    return redirect()->route('staff.dashboard')
                    ->with('error', 'Access Denied to that page.');

                default:
                    return redirect('/')->with('error', 'Access Denied');
            }
        } else {
            return redirect('/login')->with('error', 'Incorrect password.');
        }
    }
}
