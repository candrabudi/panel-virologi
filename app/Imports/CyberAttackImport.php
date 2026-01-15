<?php

namespace App\Imports;

use App\Models\CyberAttack;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CyberAttackImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new CyberAttack([
            'attack_id'           => $row['attack_id'] ?? null,
            'source_ip'           => $row['source_ip'] ?? null,
            'destination_ip'      => $row['destination_ip'] ?? null,
            'source_country'      => $row['source_country'] ?? null,
            'destination_country' => $row['destination_country'] ?? null,
            'protocol'            => $row['protocol'] ?? null,
            'source_port'         => $row['source_port'] ?? null,
            'destination_port'    => $row['destination_port'] ?? null,
            'attack_type'         => $row['attack_type'] ?? null,
            'payload_size_bytes'  => $row['payload_size_bytes'] ?? null,
            'detection_label'     => $row['detection_label'] ?? null,
            'confidence_score'    => $row['confidence_score'] ?? null,
            'ml_model'            => $row['ml_model'] ?? null,
            'affected_system'     => $row['affected_system'] ?? null,
            'port_type'           => $row['port_type'] ?? null,
        ]);
    }

    /**
     * Validation rules for each row.
     */
    public function rules(): array
    {
        return [
            'source_ip' => 'nullable|string',
            'destination_ip' => 'nullable|string',
            'source_port' => 'nullable|integer',
            'destination_port' => 'nullable|integer',
            'payload_size_bytes' => 'nullable|integer',
            'confidence_score' => 'nullable|numeric',
        ];
    }

    /**
     * Batch insert untuk performance.
     */
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * Chunk reading untuk memory efficiency.
     */
    public function chunkSize(): int
    {
        return 1000;
    }
}
