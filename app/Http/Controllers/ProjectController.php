<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters(['status', 'user_id'])
            ->allowedSorts(['title', 'status', 'created_at', 'updated_at'])
            ->paginate();
        return new ProjectCollection($projects);
    }

    public function show(Request $request, Project $project)
    {
        return new ProjectResource($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();
        $project = Auth::user()->projects()->create($validated);

        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();
        $project->update($validated);
        return new ProjectResource($project);
    }

    public function destroy(Request $request, Project $project)
    {
        $project->delete();
        return response()->noContent();
    }
}
