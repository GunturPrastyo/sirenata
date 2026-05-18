<?php

namespace Modules\Project\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Enums\ProjectType;
use Modules\Project\Models\Project;

class ProjectService
{
    public function paginateFiltered(
        ProjectType $type,
        ?string $search = null,
        ?string $status = null,
        int $limit = 10
    ): LengthAwarePaginator {
        return Project::with('leader')
            ->latest()
            ->where('type', $type->value)
            ->when($search, fn($query) => $query->where('name', 'like', '%' . $search . '%'))
            ->when($status, fn($query) => $query->where('status', $status))
            ->paginate($limit)
            ->withQueryString();
    }

    public function createProject(array $data, ProjectType $type): Project
    {
        return Project::create([
            'name' => $data['proyekName'],
            'start_date' => $data['startDate'],
            'end_date' => $data['endDate'],
            'duration' => $data['duration'],
            'team_leader' => $data['teamLeader'],
            'team_members' => $data['teamMembers'],
            'type' => $type->value,
            'status' => 'On Progress',
        ]);
    }

    public function updateProject(Project $project, array $data): Project
    {
        $project->update([
            'name' => $data['proyekName'],
            'start_date' => $data['startDate'],
            'end_date' => $data['endDate'],
            'duration' => $data['duration'],
            'team_leader' => $data['teamLeader'],
            'team_members' => $data['teamMembers'],
        ]);

        return $project;
    }

    public function deleteProject(Project $project): void
    {
        $project->delete();
    }

    public function getUsersByScope(?string $provinceCode = null, ?string $regencyCode = null)
    {
        $query = User::role('user');

        if ($regencyCode) {
            $query->whereHas('scopeArea', function ($q) use ($regencyCode) {
                $q->where('regency_code', $regencyCode);
            });
        } elseif ($provinceCode) {
            $query->whereHas('scopeArea', function ($q) use ($provinceCode) {
                $q->where('province_code', $provinceCode)
                  ->whereNull('regency_code');
            });
        } else {
            $query->where(function ($q) {
                $q->doesntHave('scopeArea')
                    ->orWhereHas('scopeArea', function ($sq) {
                        $sq->whereNull('province_code')->whereNull('regency_code');
                    });
            });
        }

        return $query->get();
    }
}
