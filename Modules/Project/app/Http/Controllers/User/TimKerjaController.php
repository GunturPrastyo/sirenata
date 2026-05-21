<?php

namespace Modules\Project\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Models\Project;

class TimKerjaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $projects = Project::where('team_leader', $user->id)
            ->orWhereJsonContains('team_members', $user->id)
            ->with('leader')
            ->latest()
            ->get();

        return view('project::tim-kerja.index', compact('projects'));
    }
}
