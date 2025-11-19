<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditLogModel extends Model
{
    protected $table            = 'audit_logs';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'admin_id', 'action', 'table_name', 'record_id',
        'old_value', 'new_value', 'timestamp', 'ip_address'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'timestamp';
    protected $updatedField  = null;

    /**
     * Log an admin action
     */
    public function logAction($adminId, $action, $tableName, $recordId, $oldValue = null, $newValue = null, $ipAddress = null)
    {
        $data = [
            'admin_id'   => $adminId,
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'old_value'  => $oldValue ? json_encode($oldValue) : null,
            'new_value'  => $newValue ? json_encode($newValue) : null,
            'ip_address' => $ipAddress ?? service('request')->getIPAddress()
        ];

        return $this->insert($data);
    }

    /**
     * Get logs by admin
     */
    public function getLogsByAdmin($adminId, $limit = 50)
    {
        return $this->where('admin_id', $adminId)
                    ->orderBy('timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get logs by table and record
     */
    public function getLogsByRecord($tableName, $recordId)
    {
        return $this->where('table_name', $tableName)
                    ->where('record_id', $recordId)
                    ->orderBy('timestamp', 'DESC')
                    ->findAll();
    }

    /**
     * Get recent logs with admin details
     */
    public function getRecentLogs($limit = 100)
    {
        return $this->select('audit_logs.*, a.position as admin_position')
                    ->join('admins a', 'audit_logs.admin_id = a.id')
                    ->orderBy('audit_logs.timestamp', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}