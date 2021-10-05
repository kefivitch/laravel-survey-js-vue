<?php

namespace App\Http\Controllers\API;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Survey;
use App\Http\Resources\SurveyResource;
use App\Http\Resources\SurveyResultResource;
use App\Models\SurveyResult;
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
            'user_id'       =>  $request->input('user_id'),
            'ip_address'    =>  $request->ip(),
        ]);

        return response()->json([
            'data'      =>  new SurveyResultResource($result),
            'message'   =>  'Survey Result successfully created',
        ], 201);
    }

    public function destroy($survey_id, $id)
    {
        $survey = SurveyResult::find($id);

        if (is_null($survey)) {
            return response()->json('Survey result not found', 404);
        }
        $survey->delete();

        return response()->json([
            'data' => $id,
            'message' => 'Survey result deleted successfully',
        ], 200);
    }
}
