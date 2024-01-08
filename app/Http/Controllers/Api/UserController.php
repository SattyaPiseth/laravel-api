<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {

        $users = User::with('roles')->withoutRole('admin');
        if ($request->has('search') && !empty($request->search)) {
            $users = $users->where('name', 'like', '%' . $request->search . '%');
        }
        $users = $users->paginate(10);
        return response()->json([
            'users' => $users,

        ],200);
    }
    
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        if (auth('api')->user()->hasRole('admin')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
            $user = new User();
            $user->fill($request->all())->save();
            $user->assignRole($request->role);

            return response()->json([
                'message' => "User created successfully",
                'user' => $user,

            ],200);
        }
        return response()->json([
            'message' => "You are not authorized to perform this action",

        ],403);

    }

    public function edit($id): Response
    {
        return Inertia::render('User/Edit', [
            'user' => User::with('roles')->find($id),
            'roles' => Role::whereNotIn('name', ['Admin'])->pluck('name')
        ]);
    }

//    public function edit($id): Response
//    {
//        return Inertia::render('User/Edit', [
//            'user' => User::with('roles')->find($id),
//            'roles' => Role::whereNotIn('name', ['Admin'])->pluck('name')
//        ]);
//    }

    public function update(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->id)],
        ]);

        $user = User::findOrFail($request->id);
        $user->fill($request->all())->save();

        if (!$user->hasRole($request->role))
            $user->syncRoles($request->role);


        return response()->json([
            'message' => "User updated successfully",
            'user' => $user,

        ],200);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        if (User::with('roles')->find($id)) {
            User::destroy($id);
            return response()->json([
                'message' => "User deleted successfully",

            ],200);
        }
        return response()->json([
            'message' => "User not found",

        ],404);
    }


  public function show($id): \Illuminate\Http\JsonResponse
  {
          // estimate response status
          $status = 200;
          // get user
          $user = User::with('roles')->find($id);
          // if user not found
          if (!$user) {
              // set status to 404
              $status = 404;
              // set user to null
              $user = null;
          }
          // return response
          return response()->json([
              'user' => $user
          ], $status);
  }
}
