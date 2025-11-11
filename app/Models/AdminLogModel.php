<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminLogModel extends Model
{
    protected $table = 'admin_log';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'admin_id', 
        'action', 
        'target_id',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    // Auto-generate logs
    public function logActivity($adminId, $action, $targetId = null)
    {
        $data = [
            'admin_id' => $adminId,
            'action' => $action,
            'target_id' => $targetId
        ];

        return $this->insert($data);
    }

    // Get logs dengan pagination
    public function getLogsPaginated($perPage = 20)
    {
        return $this->orderBy('created_at', 'DESC')
                    ->paginate($perPage);
    }

    // Get logs hari ini
    public function getTodayLogs()
    {
        return $this->where('DATE(created_at)', date('Y-m-d'))
                    ->countAllResults();
    }
}