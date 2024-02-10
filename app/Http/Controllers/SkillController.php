<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSkillRequest;
use App\Http\Requests\UpdateSkillRequest;
use App\Models\Skill;
use Illuminate\Http\Request;
use App\Http\Resources\SkillCollection;
use App\Http\Resources\SkillResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $Skills = QueryBuilder::for(Skill::class)
            ->allowedSorts(['order'])
            ->with('image')
            ->paginate();
        return new SkillCollection($Skills);
    }

    public function publicIndex(Request $request, string $user_id)
    {
        $Skills = QueryBuilder::for(Skill::class)
            ->allowedSorts(['order'])
            ->with('image')
            ->paginate();
        return new SkillCollection($Skills);
    }

    public function show(Request $request, Skill $Skill)
    {
        try {
            $Skill->load('projects')
                ->load('image');
            return new SkillResource($Skill);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Skill not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function publicShow(string $user_id, string $id)
    {
        try {
            $Skill = Skill::with('projects')
                ->with('image')
                ->where('id', $id)
                ->firstOrFail();
            return new SkillResource($Skill);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Skill not found'], Response::HTTP_NOT_FOUND);
        }
    }

    public function store(StoreSkillRequest $request)
    {
        $validated = $request->validated();
        $Skill = Auth::user()->Skills()->create($validated);
        return new SkillResource($Skill);
    }


    public function update(UpdateSkillRequest $request, Skill $Skill)
    {
        $validated = $request->validated();
        return new SkillResource($Skill);
    }


    public function destroy(Request $request, Skill $Skill)
    {
        // Detach the skills for this Skill
        $Skill->projects()->detach();

        // Delete the Skill
        $Skill->delete();
        return response()->noContent();
    }
}
