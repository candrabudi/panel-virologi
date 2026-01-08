<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreCyberSecurityServiceRequest;
use App\Models\CyberSecurityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CyberSecurityServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    private function authorizeManage(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-cyber-security')))) {
            return;
        }

        Log::warning('Unauthorized attempt to access Cyber Security Service management by User ID: '.(auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to cyber security service management');
    }

    public function index(): View
    {
        $this->authorizeManage();

        return view('cyber_security_services.index');
    }

    public function create(): View
    {
        $this->authorizeManage();

        return view('cyber_security_services.create');
    }

    public function edit(CyberSecurityService $cyberSecurityService): View
    {
        $this->authorizeManage();

        return view('cyber_security_services.edit', compact('cyberSecurityService'));
    }

    public function list(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $query = CyberSecurityService::query()->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_name', 'like', "%{$search}%")
            );
        }

        $perPage = (int) $request->get('per_page', 10);
        $services = $query->paginate($perPage);

        return ResponseHelper::ok($services);
    }

    public function store(StoreCyberSecurityServiceRequest $request): JsonResponse
    {
        $this->authorizeManage();

        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);

            if ($request->hasFile('thumbnail')) {
                $data['thumbnail'] = asset('storage/'.$request->file('thumbnail')
                    ->store('cyber_security_services', 'public'));
            }

            $service = DB::transaction(fn () => CyberSecurityService::create($data)
            );

            Log::info("Cyber Security Service created: ID {$service->id} by User ID ".auth()->id());

            return ResponseHelper::ok($service, 'Layanan berhasil disimpan', 201);
        } catch (\Throwable $e) {
            Log::error('Failed to create Cyber Security Service: '.$e->getMessage());

            return ResponseHelper::fail('Gagal menyimpan layanan', null, 500);
        }
    }

    public function update(StoreCyberSecurityServiceRequest $request, CyberSecurityService $cyberSecurityService): JsonResponse
    {
        $this->authorizeManage();

        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['name']);

            if ($request->hasFile('thumbnail')) {
                if ($cyberSecurityService->thumbnail) {
                    Storage::disk('public')->delete($cyberSecurityService->thumbnail);
                }

                $data['thumbnail'] = asset('storage/'.$request->file('thumbnail')
                    ->store('cyber_security_services', 'public'));
            }

            $data['sort_order'] = 1;

            DB::transaction(fn () => $cyberSecurityService->update($data)
            );

            Log::info("Cyber Security Service updated: ID {$cyberSecurityService->id} by User ID ".auth()->id());

            return ResponseHelper::ok($cyberSecurityService, 'Layanan berhasil diperbarui');
        } catch (\Throwable $e) {
            Log::error("Failed to update Cyber Security Service ID {$cyberSecurityService->id}: ".$e->getMessage());

            return ResponseHelper::fail('Gagal memperbarui layanan '.$e->getMessage(), null, 500);
        }
    }

    public function destroy(CyberSecurityService $cyberSecurityService): JsonResponse
    {
        $this->authorizeManage();

        try {
            if ($cyberSecurityService->thumbnail) {
                Storage::disk('public')->delete($cyberSecurityService->thumbnail);
            }

            $serviceId = $cyberSecurityService->id;

            DB::transaction(fn () => $cyberSecurityService->delete()
            );

            Log::info("Cyber Security Service deleted: ID {$serviceId} by User ID ".auth()->id());

            return ResponseHelper::ok(null, 'Layanan berhasil dihapus');
        } catch (\Throwable $e) {
            Log::error("Failed to delete Cyber Security Service ID {$cyberSecurityService->id}: ".$e->getMessage());

            return ResponseHelper::fail('Gagal menghapus layanan', null, 500);
        }
    }
}
