<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['store']]);
        $this->middleware('permission:user-edit', ['only' => ['update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('roles')->get()->map(function (User $user) {
            return array_merge($user->toArray(), [
                'roles' => $user->roles->pluck('name'),
            ]);
        });

        return response()->json([
            'message' => 'Data Retrieved',
            'data' => $users,
        ]);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $this->normalizeRolesInput($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', Rule::exists('roles', 'name')],
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->syncRoles($validated['roles']);

            return $user;
        });

        return response()->json([
            'message' => 'Data Created',
            'data' => $user,
        ], 201);
    }

    /**
     * Display the specified user with role and permissions.
     */
    public function show(User $user)
    {
        $role = $user->roles->first();

        return response()->json([
            'message' => 'Data Retrieved',
            'data' => array_merge(
                $user->toArray(),
                [
                    'role' => $role ? $role->name : null,
                    'permissions' => $role ? $role->permissions->pluck('name') : [],
                ]
            ),
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $this->normalizeRolesInput($request);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
            'roles' => ['required', 'array', 'min:1'],
            'roles.*' => ['string', Rule::exists('roles', 'name')],
        ]);

        DB::transaction(function () use ($validated, $user) {
            $user->name = $validated['name'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();
            $user->syncRoles($validated['roles']);
        });

        return response()->json([
            'message' => 'Data Updated',
            'data' => $user,
        ]);
    }

    /**
     * Remove the specified user.
     */
    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return response()->json([
                'message' => 'You cannot delete your own account.',
            ], 422);
        }

        $user->delete();

        return response()->json([
            'message' => 'Data Deleted',
        ]);
    }

    /**
     * Accept both a role name and an array of role names.
     */
    private function normalizeRolesInput(Request $request)
    {
        if ($request->filled('roles') && is_string($request->input('roles'))) {
            $request->merge(['roles' => [$request->input('roles')]]);
        }
    }
}
