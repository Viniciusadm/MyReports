<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PersonController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $people = Person::query()
                ->orderBy('name')
                ->get();

            return response()->json(['success' => true, 'data' => $people]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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

            return response()->json(['success' => true, 'data' => $people]);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $person = Person::query()->find($id);

            if (!$person) {
                return response()->json(['success' => false, 'message' => 'Pessoa não encontrada!'], 404);
            }

            return response()->json(['success' => true, 'data' => $person]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $person = Person::query()->create([
                'name' => $data['name'],
                'birth_date' => $data['birth_date'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'description' => $data['description'],
                'twitter' => $data['twitter'],
                'instagram' => $data['instagram'],
            ]);

            return response()->json(['success' => true, 'data' => $person]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $person = Person::query()->find($id);

            if (!$person) {
                return response()->json(['success' => false, 'message' => 'Pessoa não encontrada!'], 404);
            }

            $person->delete();

            return response()->json(['success' => true, 'message' => 'Pessoa deletada com sucesso!']);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $data = $request->all();

            $person = Person::query()->find($id);

            if (!$person) {
                return response()->json(['success' => false, 'message' => 'Pessoa não encontrada!'], 404);
            }

            $person->update([
                'name' => $data['name'],
                'birth_date' => $data['birth_date'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'description' => $data['description'],
            ]);

            return response()->json(['success' => true, 'data' => $person]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
