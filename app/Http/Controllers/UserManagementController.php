<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    private function authorizeManage(): void
    {
        if (method_exists(auth()->user(), 'can') && auth()->user()->can('manage-user')) {
            return;
        }

        if (Auth::user()->role === 'admin') {
            return;
        }

        abort(403, 'Forbidden');
    }

    private function ok($data = null, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    private function fail(string $message = 'Request failed', $errors = null, int $code = 400)
    {
        $payload = [
            'status' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    /**
     * Blade only.
     */
    public function index()
    {
        $this->authorizeManage();

        return view('users.index');
    }

    /**
     * JSON list.
     */
    public function list(Request $request)
    {
        $this->authorizeManage();

        $query = User::with('detail')
            ->orderByDesc('id');

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', '%'.$request->q.'%')
                  ->orWhere('email', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return $this->ok(
            $query->get()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'last_login_at' => $user->last_login_at,
                    'full_name' => optional($user->detail)->full_name,
                    'phone_number' => optional($user->detail)->phone_number,
                    'avatar' => optional($user->detail)->avatar,
                ];
            })
        );
    }

    /**
     * Store.
     */
    public function store(Request $request)
    {
        $this->authorizeManage();

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|max:150|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['admin', 'editor', 'user'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'blocked'])],
            'full_name' => 'required|string|max:150',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            $user = DB::transaction(function () use ($request) {
                $user = User::create([
                    'username' => trim($request->username),
                    'email' => trim($request->email),
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                    'status' => $request->status,
                ]);

                UserDetail::create([
                    'user_id' => $user->id,
                    'full_name' => trim($request->full_name),
                    'phone_number' => $request->phone_number,
                ]);

                return $user;
            });

            return $this->ok([
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
            ], 'User created', 201);
        } catch (\Throwable $e) {
            return $this->fail('Failed to create user', null, 500);
        }
    }

    /**
     * Update.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $user = User::with('detail')->find($id);

        if (!$user) {
            return $this->fail('User not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
            'role' => ['required', Rule::in(['admin', 'editor', 'user'])],
            'status' => ['required', Rule::in(['active', 'inactive', 'blocked'])],
            'full_name' => 'required|string|max:150',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        try {
            DB::transaction(function () use ($request, $user) {
                $data = [
                    'username' => trim($request->username),
                    'email' => trim($request->email),
                    'role' => $request->role,
                    'status' => $request->status,
                ];

                if ($request->filled('password')) {
                    $data['password'] = Hash::make($request->password);
                }

                $user->update($data);

                $user->detail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'full_name' => trim($request->full_name),
                        'phone_number' => $request->phone_number,
                    ]
                );
            });

            return $this->ok(null, 'User updated');
        } catch (\Throwable $e) {
            return $this->fail('Failed to update user', null, 500);
        }
    }

    /**
     * Delete.
     */
    public function destroy($id)
    {
        $this->authorizeManage();

        if (!ctype_digit((string) $id)) {
            return $this->fail('Invalid id', null, 400);
        }

        $user = User::find($id);

        if (!$user) {
            return $this->fail('User not found', null, 404);
        }

        if ($user->id === Auth::id()) {
            return $this->fail('Cannot delete your own account', null, 403);
        }

        try {
            DB::transaction(fn () => $user->delete());

            return $this->ok(null, 'User deleted');
        } catch (\Throwable $e) {
            return $this->fail('Failed to delete user', null, 500);
        }
    }
}
