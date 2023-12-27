<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $roles = Role::orderBy('name', 'ASC');
        if ($request->has('search') && !empty($request->search)) {
            $roles = $roles->where('name', 'like', '%' . $request->search . '%');
        }
        $roles = $roles->paginate(10);
//        return Inertia::render('Role/Index', [
//            'roles' => $roles
//        ]);
        return response()->json([
            'roles' => $roles,

        ],200);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('Role/Create');
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        if (auth('api')->user()->hasRole('admin')){
            $role = new Role();
            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
            $role->fill($request->all())->save();

            return response()->json([
                'message' => "Role created successfully",
                'role' => $role,

            ],200);
        }
        return response()->json([
            'message' => "You are not authorized to perform this action",
        ],403);
    }

    public function edit($id): Response
    {
        return Inertia::render('Role/Edit', [
            'role' => Role::findById($id)
        ]);
    }

    public function update(Request $request):\Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        if (auth('api')->user()->hasRole('admin')){

            try {
                $role = Role::findById($request->id);
                $role->fill($request->all())->save();
            } catch (\Exception $e) {
                return response()->json([
                    'message' => "Role not found",
                ],404);
            }

            return response()->json([
                'message' => "Role updated successfully",
                'role' => $role,

            ],200);
        }
        return response()->json([
            'message' => "You are not authorized to perform this action",
        ],403);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        if (auth('api')->user()->hasRole('admin')){

            try {
                Role::destroy($id);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => "Role not found",
                ],404);
            }
            return response()->json([
                'message' => "Role deleted successfully",

            ],200);

        }
        return response()->json([
            'message' => "You are not authorized to perform this action",
        ],403);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        return \response()->json([
            'role' => Role::findById($id),

        ],200);
    }
}
