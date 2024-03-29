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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters(['status'])
            ->allowedSorts(['title', 'status', 'created_at', 'updated_at'])
            ->with('coverImage')
            ->with('skills')
            ->paginate();
        return new ProjectCollection($projects);
    }

    public function publicIndex(Request $request, string $user_id)
    {
        $projects = QueryBuilder::for(Project::class)
            ->allowedSorts(['title', 'status', 'created_at', 'updated_at'])
            ->with('coverImage')
            ->with('skills')
            ->paginate();
        return new ProjectCollection($projects);
    }
    public function show(Request $request, Project $project)
    {
        try {
            $project->load('coverImage')
                ->load('skills')
                ->load('skills.image');
            return new ProjectResource($project);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function publicShow(string $user_id, string $id)
    {
        try {
            $project = Project::with('coverImage')
                ->with('skills')
                ->with('skills.image')
                ->where('id', $id)
                ->firstOrFail();
            return new ProjectResource($project);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Project not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        $skills = [];
        if ($request->has('skill_ids')) {
            $skills = $request->skill_ids;
            unset($validated['skill_ids']);
        }

        $project = Auth::user()->Projects()->create($validated);
        $project->skills()->sync($skills);

        return new ProjectResource($project);
    }
    public function update(UpdateProjectRequest $request, Project $project)
    {
        $validated = $request->validated();

        $skills = [];
        if ($request->has('skill_ids')) {
            $skills = $request->skill_ids;
            unset($validated['skill_ids']);
        }

        $project->update($validated);

        $project->forceFill(['updated_at' => now()])->save();

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
