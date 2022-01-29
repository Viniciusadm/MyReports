<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;

class PersonController extends Controller
{
    public function getAll(): JsonResponse
    {
        try {
            $people = Person::all()->sortBy('name');
            return response()->json($people);
        } catch (Exception $e) {
            return response()->json([
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

            $people = Person::select('id', 'name')
            ->where('name', 'like', '%' . request('q') . '%')
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
            $person = Person::find($id);
            return response()->json($person);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @throws ValidationException
     */
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

            $person = Person::create($data);

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
            $person = Person::find($id);
            $person->delete();

            return response()->json('Person deleted successfully');
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $this->validate($request, [
                'email' => 'email',
                'phone' => 'numeric',
                'birthday' => 'date'
            ]);

            $data = $request->all();

            $person = Person::find($id);

            $person->update($data);

            return response()->json($person);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
