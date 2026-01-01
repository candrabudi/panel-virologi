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

        if (auth()->user()->role === 'admin') {
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

    public function index()
    {
        $this->authorizeManage();

        return view('users.index');
    }

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
            $query->paginate(10)
        );
    }

    public function create()
    {
        $this->authorizeManage();

        return view('users.create', [
            'user' => null,
        ]);
    }

    public function edit(User $user)
    {
        $this->authorizeManage();

        $user->load('detail');

        return view('users.edit', [
            'user' => $user,
        ]);
    }

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

        DB::transaction(function () use ($request) {
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
        });

        return $this->ok([
            'redirect' => '/users',
        ], 'Pengguna berhasil dibuat');
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeManage();

        $userKey = $user->getKey();
        $userKeyName = $user->getKeyName();

        $validator = Validator::make($request->all(), [
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'username')
                    ->ignore($userKey, $userKeyName),
            ],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('users', 'email')
                    ->ignore($userKey, $userKeyName),
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
                    ['user_id' => $user->getKey()],
                    [
                        'full_name' => trim($request->full_name),
                        'phone_number' => $request->phone_number,
                    ]
                );
            });

            return $this->ok([
                'redirect' => '/users',
            ], 'Pengguna berhasil dirubah');
        } catch (\Throwable $e) {
            return $this->fail('Failed to update user', null, 500);
        }
    }

    public function destroy(User $user)
    {
        $this->authorizeManage();

        if ($user->id === Auth::id()) {
            return $this->fail('Cannot delete your own account', null, 403);
        }

        DB::transaction(fn () => $user->delete());

        return $this->ok(null, 'User deleted');
    }
}
