<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Survey;
use App\Http\Resources\SurveyResource;
use App\Http\Resources\SurveyResultResource;
use Illuminate\Support\Facades\Auth;

class SurveyResultAPIController extends Controller
{
    public function index(Survey $survey)
    {
        $results = $survey->results()->paginate(config('survey-manager.pagination_perPage', 10));

        return SurveyResultResource::collection($results)
                ->additional(['meta' => [
                    'survey'    =>  new SurveyResource($survey),
                ]]);
    }

    /**
     * @param Survey $survey
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Survey $survey, Request $request)
    {
        $request->validate([
            'json'  =>  'required',
        ]);

        $result = $survey->results()->create([
            'json'          =>  $request->input('json'),
            'user_id'       =>  Auth::check() ? Auth::id() : null,
            'ip_address'    =>  $request->ip(),
        ]);

        return response()->json([
            'data'      =>  new SurveyResultResource($result),
            'message'   =>  'Survey Result successfully created',
        ], 201);
    }
}
