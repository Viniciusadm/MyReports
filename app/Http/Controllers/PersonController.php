<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PersonController extends Controller
{
    private string $path = 'public/images/people';

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

            if (isset($data['image']) && $data['image']->getSize() > 500000) {
                return response()->json(['success' => false, 'message' => 'A imagem deve ter no máximo 500KB'], 422);
            }

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

            Storage::delete($this->path . '/' . $person['image']);

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

            if (isset($data['image']) && $data['image']->getSize() > 500000) {
                return response()->json(['success' => false, 'message' => 'A imagem deve ter no máximo 500KB'], 422);
            }

            if (isset($data['image']) && gettype($data['image']) == 'object') {
                if ($person['image']) {
                    Storage::delete($this->path . '/' . $person['image']);
                }
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
        ];

        if (isset($data['image'])) {
            $extension = $data['image']->getClientOriginalExtension();
            $nomeArquivo = md5($data['image']->getClientOriginalName() . date('Y-m-d H:i:s')) . '.' . $extension;
            $data['image']->storeAs($this->path, $nomeArquivo);
            $objeto['image'] = $nomeArquivo;
        }

        if (isset($data['birth_date'])) {
            $objeto['birth_date'] = $data['birth_date'];
        }

        return $objeto;
    }
}
