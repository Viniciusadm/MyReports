<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ResponseResource;
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

            return response()->json(ResponseResource::make($people));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
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
                ->take(10)
                ->get();

            return response()->json(ResponseResource::make($people));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $person = Person::query()
                ->withCount('participants')
                ->findOrFail($id);

            return response()->json(ResponseResource::make($person));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $objeto = $this->getObjeto($request);
            $person = Person::query()->create($objeto);

            return response()->json(ResponseResource::make($person));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $person = Person::query()
                ->findOrFail($id)
                ->delete();

            return response()->json(ResponseResource::make($person));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $objeto = $this->getObjeto($request);

            $person = Person::query()
                ->findOrFail($id)
                ->update($objeto);

            return response()->json(ResponseResource::make($person));
        } catch (Exception $e) {
            return response()->json(ErrorResource::make($e->getMessage()), 500);
        }
    }

    private function getObjeto(Request $request): array
    {
        $objeto = [
            'name' => $request->input('name'),
            'nickname' => $request->input('nickname', explode(' ', $request->input('name'))[0]),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'description' => $request->input('description'),
            'twitter' => $request->input('twitter'),
            'instagram' => $request->input('instagram'),
            'image' => $request->input('image'),
        ];

        if ($request->input('birth_date')) {
            $objeto['birth_date'] = $request->input('birth_date');
        }

        return $objeto;
    }
}
