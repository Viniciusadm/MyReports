<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ResponseResource;
use App\Models\Answer;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function reply(Request $request): JsonResponse
    {
        try {
            $question = Answer::query()->create([
                'question_id' => $request->input('question_id'),
                'answer' => $request->input('answer'),
                'date' => $request->input('date'),
            ]);

            return response()->json(ResponseResource::make($question), 201);
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function change(Request $request, int $id): JsonResponse
    {
        try {
            $question = Answer::query()
                ->findOrFail($id)
                ->update([
                    'answer' => $request->input('answer'),
                ]);

            return response()->json(ResponseResource::make($question), 201);
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function comment(Request $request, int $id): JsonResponse
    {
        try {
            $question = Answer::query()
                ->findOrFail($id)
                ->update([
                    'comment' => $request->input('comment'),
                ]);

            return response()->json(ResponseResource::make($question), 201);
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }
}
