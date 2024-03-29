<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExperienceRequest;
use App\Http\Requests\UpdateExperienceRequest;
use App\Http\Resources\ExperienceCollection;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class ExperienceController extends Controller
{
    public function index(Request $request)
    {
        $experiences = QueryBuilder::for(Experience::class)
            ->allowedFilters(['type', 'user_id'])
            ->allowedSorts(['start_date', 'end_date', 'created_at', 'updated_at'])
            ->with('skills')
            ->paginate();
        return new ExperienceCollection($experiences);
    }

    public function publicIndex(Request $request, string $user_id)
    {
        $experiences = QueryBuilder::for(Experience::class)
            ->allowedFilters(['type'])
            ->allowedSorts(['start_date', 'end_date', 'created_at', 'updated_at'])
            ->with('skills')
            ->paginate();
        return new ExperienceCollection($experiences);
    }

    public function show(Request $request, Experience $experience)
    {
        try {
            $experience
                ->load('skills')
                ->load('skills.image');
            return new ExperienceResource($experience);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Experience not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function publicShow(string $user_id, string $id)
    {
        try {
            $experience = Experience::with('skills')
                ->with('skills.image')
                ->where('id', $id)
                ->firstOrFail();
            return new ExperienceResource($experience);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Experience not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(StoreExperienceRequest $request)
    {
        $validated = $request->validated();

        $skills = [];
        if ($request->has('skill_ids')) {
            $skills = $request->skill_ids;
            unset($validated['skill_ids']);
        }

        $experience = Auth::user()->Experiences()->create($validated);
        $experience->skills()->sync($skills);

        return new ExperienceResource($experience);
    }

    public function update(UpdateExperienceRequest $request, Experience $experience)
    {
        $validated = $request->validated();

        $skills = [];
        if ($request->has('skill_ids')) {
            $skills = $request->skill_ids;
            unset($validated['skill_ids']);
        }

        $experience->update($validated);

        $experience->forceFill(['updated_at' => now()])->save();

        $experience->skills()->sync($skills);

        return new ExperienceResource($experience);
    }

    public function destroy(Request $request, Experience $experience)
    {
        $experience->skills()->detach();

        $experience->delete();
        return response()->noContent();
    }
}
