<?php

namespace Modules\Project\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Modules\Project\Models\Project;
use Modules\Project\Enums\ProjectType;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Project::with('leader')->latest();

        if ($user->hasRole('admin-pusat') || $user->hasRole('super-admin')) {
            $query->where('type', ProjectType::NASIONAL->value);
        } elseif ($user->hasRole('admin-province')) {
            $query->where('type', ProjectType::PROVINSI->value);
        } elseif ($user->hasRole('admin-kab-kota')) {
            $query->where('type', ProjectType::KAB_KOTA->value);
        } else {
            $query->where(function ($q) use ($user) {
                $q->where('team_leader', $user->id)
                    ->orWhereJsonContains('team_members', (string) $user->id);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->get('per_page', 10);
        $projects = $query->paginate($perPage)->withQueryString();

        return view('project::index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $adminScope = auth()->user()->scopeArea;
        $userRole = auth()->user()->roles->first()->name ?? '';

        $usersQuery = User::role('user');

        if ($userRole === 'admin-province' && $adminScope && $adminScope->province_code) {
            $usersQuery->whereHas('scopeArea', function ($q) use ($adminScope) {
                $q->where('province_code', $adminScope->province_code)
                    ->whereNull('regency_code');
            });
        } elseif ($userRole === 'admin-kab-kota' && $adminScope && $adminScope->regency_code) {
            $usersQuery->whereHas('scopeArea', function ($q) use ($adminScope) {
                $q->where('regency_code', $adminScope->regency_code);
            });
        } elseif ($userRole === 'admin-pusat' || $userRole === 'super-admin') {
            $usersQuery->where(function ($query) {
                $query->doesntHave('scopeArea')
                    ->orWhereHas('scopeArea', function ($q) {
                        $q->whereNull('province_code')
                            ->whereNull('regency_code');
                    });
            });
        } else {
            $usersQuery->where('id', 0);
        }

        $users = $usersQuery->get();
        return view('project::create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'duration' => 'nullable|integer',
            'teamLeader' => 'required|exists:users,id',
            'teamMembers' => 'nullable|array',
            'teamMembers.*' => 'exists:users,id',
        ]);

        $user = auth()->user();
        $type = ProjectType::NASIONAL->value; // Default fallback

        if ($user->hasRole('admin-province')) {
            $type = ProjectType::PROVINSI->value;
        } elseif ($user->hasRole('admin-kab-kota')) {
            $type = ProjectType::KAB_KOTA->value;
        }

        Project::create([
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
            'team_leader' => $request->teamLeader,
            'team_members' => $request->teamMembers,
            'type' => $type,
            'status' => 'On Progress',
        ]);

        return redirect()->route('project.index')->with('success', 'Proyek berhasil dibuat!');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $project = Project::with(['leader'])->findOrFail($id);
        return view('project::show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);

        $adminScope = auth()->user()->scopeArea;
        $userRole = auth()->user()->roles->first()->name ?? '';

        $usersQuery = User::role('user');

        if ($userRole === 'admin-province' && $adminScope && $adminScope->province_code) {
            $usersQuery->whereHas('scopeArea', function ($q) use ($adminScope) {
                $q->where('province_code', $adminScope->province_code)
                    ->whereNull('regency_code');
            });
        } elseif ($userRole === 'admin-kab-kota' && $adminScope && $adminScope->regency_code) {
            $usersQuery->whereHas('scopeArea', function ($q) use ($adminScope) {
                $q->where('regency_code', $adminScope->regency_code);
            });
        } elseif ($userRole === 'admin-pusat' || $userRole === 'super-admin') {
            $usersQuery->where(function ($query) {
                $query->doesntHave('scopeArea')
                    ->orWhereHas('scopeArea', function ($q) {
                        $q->whereNull('province_code')
                            ->whereNull('regency_code');
                    });
            });
        } else {
            $usersQuery->where('id', 0);
        }

        $users = $usersQuery->get();
        return view('project::edit', compact('project', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'duration' => 'nullable|integer',
            'teamLeader' => 'required|exists:users,id',
            'teamMembers' => 'nullable|array',
            'teamMembers.*' => 'exists:users,id',
        ]);

        $project->update([
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
            'team_leader' => $request->teamLeader,
            'team_members' => $request->teamMembers,
        ]);

        return redirect()->route('project.index')->with('success', 'Proyek berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('project.index')->with('success', 'Proyek berhasil dihapus!');
    }
}
