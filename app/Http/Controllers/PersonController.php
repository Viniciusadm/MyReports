<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PersonController extends Controller
{
    public function getAll(): JsonResponse
    {
        try {
            $people = Person::all();
            return response()->json([
                'success' => true,
                'data' => $people
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function search(): JsonResponse
    {
        try {
            if (request('q') == null) {
                return response()->json('');
            }

            $exclude = json_decode(request('exclude'));

            $people = Person::query()->select('id', 'name')
            ->where('name', 'like', '%' . request('q') . '%')
            ->whereNotIn('id', $exclude)
            ->take(5)
            ->get();

            return response()->json($people);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getById($id): JsonResponse
    {
        try {
            $person = Person::query()->find($id);
            return response()->json($person);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'email',
                'phone' => 'numeric',
                'birthday' => 'date'
            ]);

            $data = $request->all();

            $person = Person::query()->create($data);

            return response()->json($person);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $person = Person::query()->find($id);
            $person->delete();

            return response()->json('Person deleted successfully');
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $this->validate($request, [
                'email' => 'email',
                'phone' => 'numeric',
                'birthday' => 'date'
            ]);

            $data = $request->all();

            $person = Person::query()->find($id);

            $person->update($data);

            return response()->json($person);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
