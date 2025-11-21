<?php

namespace App\Models;

use CodeIgniter\Model;

class AddonModel extends Model
{
    protected $table            = 'addons';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'description', 'price', 'type', 'status',
        'created_at', 'updated_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'name'  => 'required|max_length[255]',
        'price' => 'required|decimal',
        'type'  => 'required|in_list[equipment,service,food]',
        'status' => 'required|in_list[active,inactive]'
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get active addons
     */
    public function getActiveAddons()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getAddonsByType($type = null)
    {
        $builder = $this->where('status', 'active');
        
        if ($type) {
            $builder->where('type', $type);
        }
        
        return $builder->findAll();
    }

    public function getAddonPrices($addonIds)
    {
        if (empty($addonIds)) return [];
        
        return $this->select('id, price')
                    ->whereIn('id', $addonIds)
                    ->findAll();
    }
}