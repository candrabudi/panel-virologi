<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * Consistent authorization check.
     */
    private function authorizeManage(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-user')))) {
            return;
        }

        Log::warning("Unauthorized attempt to manage users by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to user management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();
        return view('users.index');
    }

    /**
     * API: List users with pagination and search.
     */
    public function list(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $query = User::with('detail')->orderByDesc('id');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return ResponseHelper::ok($query->paginate(10));
    }

    /**
     * Display creation form.
     */
    public function create(): View
    {
        $this->authorizeManage();
        return view('users.create', ['user' => null]);
    }

    /**
     * Display editing form.
     */
    public function edit(User $user): View
    {
        $this->authorizeManage();
        $user->load('detail');
        return view('users.edit', ['user' => $user]);
    }

    /**
     * API: Store a new user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        // Authorization is handled by StoreUserRequest
        $this->authorizeManage();

        try {
            $data = $request->validated();
            
            DB::transaction(function () use ($data) {
                $user = User::create([
                    'username' => $data['username'],
                    'email'    => $data['email'],
                    'password' => Hash::make($data['password']),
                    'role'     => $data['role'],
                    'status'   => $data['status'],
                ]);

                UserDetail::create([
                    'user_id'      => $user->id,
                    'full_name'    => $data['full_name'],
                    'phone_number' => $data['phone_number'] ?? null,
                ]);

                Log::info("User created: ID {$user->id} ('{$user->username}') by User ID " . auth()->id());
            });

            return ResponseHelper::ok([
                'redirect' => '/users',
            ], 'Pengguna berhasil dibuat', 201);
        } catch (\Throwable $e) {
            Log::error("Failed to create user: " . $e->getMessage());
            return ResponseHelper::fail('Gagal membuat pengguna', null, 500);
        }
    }

    /**
     * API: Update an existing user.
     */
    public function update(StoreUserRequest $request, User $user): JsonResponse
    {
        // Authorization is handled by StoreUserRequest
        $this->authorizeManage();

        try {
            $data = $request->validated();

            DB::transaction(function () use ($request, $user, $data) {
                $userData = [
                    'username' => $data['username'],
                    'email'    => $data['email'],
                    'role'     => $data['role'],
                    'status'   => $data['status'],
                ];

                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($data['password']);
                }

                $user->update($userData);

                $user->detail()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'full_name'    => $data['full_name'],
                        'phone_number' => $data['phone_number'] ?? null,
                    ]
                );

                Log::info("User updated: ID {$user->id} by User ID " . auth()->id());
            });

            return ResponseHelper::ok([
                'redirect' => '/users',
            ], 'Pengguna berhasil diperbarui');
        } catch (\Throwable $e) {
            Log::error("Failed to update user ID {$user->id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal memperbarui pengguna', null, 500);
        }
    }

    /**
     * API: Delete a user.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorizeManage();

        if ($user->id === auth()->id()) {
            Log::warning("Self-deletion attempt blocked for User ID: " . $user->id);
            return ResponseHelper::fail('Tidak dapat menghapus akun Anda sendiri', null, 403);
        }

        try {
            $userId = $user->id;
            $username = $user->username;

            DB::transaction(fn () => $user->delete());

            Log::info("User deleted: ID {$userId} ('{$username}') by User ID " . auth()->id());

            return ResponseHelper::ok(null, 'Pengguna berhasil dihapus');
        } catch (\Throwable $e) {
            Log::error("Failed to delete user ID {$user->id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menghapus pengguna', null, 500);
        }
    }
}
