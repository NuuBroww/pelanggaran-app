<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminLogModel;

class AdminLogs extends BaseController
{
    public function index()
    {
        $logModel = new AdminLogModel();
        
        // Ambil logs dengan pagination
        $logs = $logModel->getLogsPaginated(20);
        $pager = $logModel->pager;
        
        // Mapping sederhana untuk nama admin berdasarkan admin_id
        $adminNames = $this->getAdminNames($logs);
        
        $data = [
            'title' => 'Admin Logs',
            'logs' => $logs,
            'adminNames' => $adminNames,
            'pager' => $pager,
            'totalLogs' => $logModel->countAll(),
            'todayLogs' => $logModel->getTodayLogs()
        ];
        
        return view('admin_logs', $data);
    }
    
    // Di AdminLogs Controller, ganti method getAdminNames


// Di AdminLogs Controller
private function getAdminNames($logs)
{
    $adminNames = [];
    
    // Ambil nama dari session dan tambahkan "Ustadz"
    $currentAdminName = session()->get('nama') ?? session()->get('username') ?? 'Admin';
    $formattedName = "Ustadz " . $currentAdminName;
    
    foreach ($logs as $log) {
        if (isset($log['admin_id'])) {
            $adminNames[$log['admin_id']] = $formattedName;
        }
    }
    
    return $adminNames;
}

private function mapOtherAdminName($adminId)
{
    $adminMapping = [
        1 => 'Ustadz Admin',
        2 => 'Ustadz Operator',
        // Tambahkan mapping admin lainnya
    ];
    
    return $adminMapping[$adminId] ?? 'Admin ' . $adminId;
}
    
    private function mapAdminName($adminId)
    {
        // Mapping manual - sesuaikan dengan admin Anda
        $adminMapping = [
            1 => 'Ustadz Admin',
            2 => 'Ustadz Operator',
            // Tambahkan mapping lainnya
        ];
        
        return $adminMapping[$adminId] ?? 'Admin ' . $adminId;
    }
}