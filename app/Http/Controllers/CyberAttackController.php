<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\CyberAttack;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CyberAttackImport;

class CyberAttackController extends Controller
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

        if ($user && ($user->role === 'admin' || $user->role === 'editor' || (method_exists($user, 'can') && $user->can('manage-cyber-attack')))) {
            return;
        }

        Log::warning('Unauthorized attempt to manage cyber attacks by User ID: '.(auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to cyber attack management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();

        return view('cyber_attacks.index');
    }

    /**
     * API: List cyber attacks with pagination and search.
     */
    public function list(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $query = CyberAttack::query()->orderByDesc('id');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('attack_id', 'like', "%{$search}%")
                  ->orWhere('source_ip', 'like', "%{$search}%")
                  ->orWhere('destination_ip', 'like', "%{$search}%")
                  ->orWhere('source_country', 'like', "%{$search}%")
                  ->orWhere('destination_country', 'like', "%{$search}%")
                  ->orWhere('attack_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('attack_type')) {
            $query->where('attack_type', $request->attack_type);
        }

        if ($request->filled('protocol')) {
            $query->where('protocol', $request->protocol);
        }

        $perPage = $request->input('per_page', 10);
        $attacks = $query->paginate($perPage);

        return ResponseHelper::ok($attacks);
    }

    /**
     * Display import form.
     */
    public function import(): View
    {
        $this->authorizeManage();

        return view('cyber_attacks.import');
    }

    /**
     * Process import file.
     */
    public function processImport(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        try {
            DB::beginTransaction();

            Excel::import(new CyberAttackImport, $request->file('file'));

            DB::commit();

            Log::info('Cyber attacks imported successfully by User ID '.auth()->id());

            return ResponseHelper::ok([
                'redirect' => '/cyber-attacks',
            ], 'Data berhasil diimport', 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Failed to import cyber attacks: '.$e->getMessage());

            return ResponseHelper::fail('Gagal mengimport data: '.$e->getMessage(), null, 500);
        }
    }

    /**
     * Insert single record (untuk progress bar).
     */
    public function insertSingle(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $request->validate([
            'attack_id' => 'nullable|string',
            'source_ip' => 'nullable|string',
            'destination_ip' => 'nullable|string',
            'source_country' => 'nullable|string',
            'destination_country' => 'nullable|string',
            'protocol' => 'nullable|string',
            'source_port' => 'nullable|integer',
            'destination_port' => 'nullable|integer',
            'attack_type' => 'nullable|string',
            'payload_size_bytes' => 'nullable|integer',
            'detection_label' => 'nullable|string',
            'confidence_score' => 'nullable|numeric|min:0|max:1',
            'ml_model' => 'nullable|string',
            'affected_system' => 'nullable|string',
            'port_type' => 'nullable|string',
        ]);

        try {
            $attack = CyberAttack::create($request->only([
                'attack_id',
                'source_ip',
                'destination_ip',
                'source_country',
                'destination_country',
                'protocol',
                'source_port',
                'destination_port',
                'attack_type',
                'payload_size_bytes',
                'detection_label',
                'confidence_score',
                'ml_model',
                'affected_system',
                'port_type',
            ]));

            return ResponseHelper::ok(['id' => $attack->id], 'Record inserted');
        } catch (\Throwable $e) {
            Log::error('Failed to insert single record: '.$e->getMessage());

            return ResponseHelper::fail('Insert failed: '.$e->getMessage(), null, 500);
        }
    }

    /**
     * Delete a cyber attack record.
     */
    public function destroy(CyberAttack $cyberAttack): JsonResponse
    {
        $this->authorizeManage();

        try {
            $attackId = $cyberAttack->id;
            $attackIdentifier = $cyberAttack->attack_id;

            DB::transaction(fn () => $cyberAttack->delete());

            Log::info("Cyber attack deleted: ID {$attackId} ('{$attackIdentifier}') by User ID ".auth()->id());

            return ResponseHelper::ok(null, 'Data serangan cyber berhasil dihapus');
        } catch (\Throwable $e) {
            Log::error("Failed to delete cyber attack ID {$cyberAttack->id}: ".$e->getMessage());

            return ResponseHelper::fail('Gagal menghapus data', null, 500);
        }
    }

    /**
     * Get statistics for dashboard.
     */
    public function statistics(): JsonResponse
    {
        $this->authorizeManage();

        try {
            $stats = [
                'total_attacks' => CyberAttack::count(),
                'attack_types' => CyberAttack::select('attack_type', DB::raw('count(*) as count'))
                    ->groupBy('attack_type')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get(),
                'top_source_countries' => CyberAttack::select('source_country', DB::raw('count(*) as count'))
                    ->groupBy('source_country')
                    ->orderByDesc('count')
                    ->limit(5)
                    ->get(),
                'protocols' => CyberAttack::select('protocol', DB::raw('count(*) as count'))
                    ->groupBy('protocol')
                    ->orderByDesc('count')
                    ->get(),
                'recent_attacks' => CyberAttack::orderByDesc('created_at')
                    ->limit(10)
                    ->get(),
            ];

            return ResponseHelper::ok($stats);
        } catch (\Throwable $e) {
            Log::error('Failed to get cyber attack statistics: '.$e->getMessage());

            return ResponseHelper::fail('Gagal mengambil statistik', null, 500);
        }
    }

    /**
     * Download template CSV (header only).
     */
    public function downloadTemplate()
    {
        $this->authorizeManage();

        try {
            $headers = [
                'attack_id',
                'source_ip',
                'destination_ip',
                'source_country',
                'destination_country',
                'protocol',
                'source_port',
                'destination_port',
                'attack_type',
                'payload_size_bytes',
                'detection_label',
                'confidence_score',
                'ml_model',
                'affected_system',
                'port_type'
            ];

            $callback = function() use ($headers) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $headers);
                fclose($file);
            };

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="cyber_attacks_template.csv"',
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to download template: '.$e->getMessage());
            
            return back()->with('error', 'Gagal mendownload template');
        }
    }

    /**
     * Download sample data CSV (header + dummy data).
     */
    public function downloadSample()
    {
        $this->authorizeManage();

        try {
            $headers = [
                'attack_id',
                'source_ip',
                'destination_ip',
                'source_country',
                'destination_country',
                'protocol',
                'source_port',
                'destination_port',
                'attack_type',
                'payload_size_bytes',
                'detection_label',
                'confidence_score',
                'ml_model',
                'affected_system',
                'port_type'
            ];

            $sampleData = [
                ['ATK001', '192.168.1.100', '10.0.0.50', 'United States', 'Indonesia', 'TCP', '45123', '80', 'DDoS', '2048', 'Malicious', '0.95', 'RandomForest', 'WebServer', 'HTTP'],
                ['ATK002', '203.122.45.89', '172.16.0.10', 'China', 'Singapore', 'UDP', '53212', '443', 'Malware', '4096', 'Suspicious', '0.87', 'NeuralNet', 'Database', 'HTTPS'],
                ['ATK003', '45.76.123.99', '192.168.0.1', 'Russia', 'Malaysia', 'HTTP', '8080', '3306', 'SQL Injection', '1024', 'Malicious', '0.92', 'SVM', 'MySQL', 'Database'],
                ['ATK004', '198.51.100.23', '10.10.10.10', 'Brazil', 'Thailand', 'HTTPS', '443', '22', 'Brute Force', '512', 'Suspicious', '0.78', 'DecisionTree', 'SSH', 'SSH'],
                ['ATK005', '104.28.15.78', '172.31.255.1', 'Germany', 'Vietnam', 'TCP', '12345', '21', 'FTP Attack', '8192', 'Malicious', '0.89', 'RandomForest', 'FTPServer', 'FTP'],
                ['ATK006', '185.220.101.45', '192.168.100.50', 'Netherlands', 'Philippines', 'UDP', '53', '53', 'DNS Amplification', '16384', 'Malicious', '0.96', 'NeuralNet', 'DNSServer', 'DNS'],
                ['ATK007', '91.241.19.84', '10.20.30.40', 'Ukraine', 'Japan', 'TCP', '3389', '3389', 'RDP Exploit', '4096', 'Malicious', '0.91', 'RandomForest', 'WindowsServer', 'RDP'],
                ['ATK008', '159.203.45.67', '172.16.50.100', 'India', 'South Korea', 'HTTP', '80', '8080', 'XSS Attack', '2048', 'Suspicious', '0.85', 'SVM', 'WebApp', 'HTTP'],
                ['ATK009', '66.42.88.123', '192.168.1.254', 'Canada', 'Australia', 'HTTPS', '443', '8443', 'Command Injection', '1024', 'Malicious', '0.94', 'DecisionTree', 'API', 'HTTPS'],
                ['ATK010', '217.182.77.99', '10.0.100.200', 'France', 'New Zealand', 'TCP', '25', '587', 'SMTP Spam', '512', 'Suspicious', '0.76', 'NeuralNet', 'MailServer', 'SMTP'],
                ['ATK011', '142.93.156.78', '172.20.10.50', 'United Kingdom', 'Taiwan', 'UDP', '1900', '1900', 'SSDP Amplification', '32768', 'Malicious', '0.98', 'RandomForest', 'IoTDevice', 'SSDP'],
                ['ATK012', '89.248.165.12', '192.168.5.10', 'Poland', 'Hong Kong', 'HTTP', '8000', '9000', 'Directory Traversal', '1536', 'Suspicious', '0.83', 'SVM', 'FileServer', 'HTTP'],
                ['ATK013', '103.78.45.234', '10.50.100.150', 'Bangladesh', 'Singapore', 'TCP', '445', '445', 'SMB Exploit', '2048', 'Malicious', '0.93', 'NeuralNet', 'WindowsShare', 'SMB'],
                ['ATK014', '195.154.67.234', '172.30.20.80', 'Romania', 'Indonesia', 'HTTPS', '8443', '443', 'SSL Stripping', '4096', 'Malicious', '0.90', 'DecisionTree', 'Proxy', 'HTTPS'],
                ['ATK015', '121.56.189.45', '192.168.200.1', 'Vietnam', 'Thailand', 'UDP', '123', '123', 'NTP Amplification', '65536', 'Malicious', '0.97', 'RandomForest', 'NTPServer', 'NTP'],
            ];

            $callback = function() use ($headers, $sampleData) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $headers);
                foreach ($sampleData as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="cyber_attacks_sample_data.csv"',
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to download sample: '.$e->getMessage());
            
            return back()->with('error', 'Gagal mendownload sample data');
        }
    }
}
