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
                ->select('id', 'name', 'nickname')
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
            if (request('q') === null) {
                return response()->json('');
            }

            $exclude = json_decode(request('exclude'));

            $people = Person::query()->select('id', 'name')
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . request('q') . '%')
                        ->orWhere('nickname', 'like', '%' . request('q') . '%');
                })
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
            $person = Person::query()
                ->withCount('participants')
                ->find($id);

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

            $objeto = $this->getObjeto($data);

            $person = Person::query()->create($objeto);

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

            $objeto = $this->getObjeto($data);

            $person->update($objeto);

            return response()->json(['success' => true, 'data' => $person]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    private function getObjeto(array $data): array
    {
        $objeto = [
            'name' => $data['name'],
            'nickname' => $data['nickname'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'description' => $data['description'] ?? null,
            'twitter' => $data['twitter'] ?? null,
            'instagram' => $data['instagram'] ?? null,
            'image' => $data['image'] ?? null,
        ];

        if (isset($data['birth_date'])) {
            $objeto['birth_date'] = $data['birth_date'];
        }

        return $objeto;
    }
}
