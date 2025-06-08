<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Skill;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Fetch the user’s skills and projects
        $skills = $user->skills->pluck('name');
        $projects = $user->projects;
        
        // Fetch all available skills
        // $availableSkills = Skill::all();
        
        // Pass data to the view
        return view('dashboard', compact('user', 'skills', 'projects', 'availableSkills'));
    }

    public function addSkill(Request $request)
    {
        $user = Auth::users();
        $user->addSkill($request->skillId);
        
        return redirect()->route('dashboard');
    }

    public function addProject(Request $request)
    {
        $user = Auth::users();
        $user->addProject($request->project_id);
        
        return redirect()->route('dashboard');
    }

    public function getAllSkills()
    {
        $user = Auth::users();

        return $user->getAdditionalSkills();
    }

    public function getAllProjects()
    {
        $user = Auth::users();

        return $user->getAdditionalProjects();
    }
}
