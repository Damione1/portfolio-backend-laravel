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
            ->with('coverImage')
            ->with('skills')
            ->paginate();
        return new ProjectCollection($projects);
    }

    public function show(Request $request, Project $project)
    {
        $project->load('coverImage')
            ->load('skills')
            ->load('skills.image');
        return new ProjectResource($project);
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $project = Auth::user()->Projects()->create($validated);

        // Check if 'skills' array exists in the request
        if ($request->has('skills')) {
            $project->skills()->sync($request->skills);
        }

        return new ProjectResource($project);
    }



    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();

        $skills = [];
        if ($request->has('skills')) {
            $skills = $request->skills;
            unset($validated['skills']);
        }

        $project->update($validated);

        // sync the skills
        $project->skills()->sync($skills);

        return new ProjectResource($project);
    }


    public function destroy(Request $request, Project $project)
    {
        // Detach the skills for this project
        $project->skills()->detach();

        // Delete the project
        $project->delete();
        return response()->noContent();
    }
}
