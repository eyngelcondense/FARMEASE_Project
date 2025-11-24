<?php
// app/Commands/ExpireContracts.php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ContractModel;

class ExpireContracts extends BaseCommand
{
    protected $group = 'Contracts';
    protected $name = 'contracts:expire';
    protected $description = 'Auto-expire sent contracts that have passed their expiration date';

    public function run(array $params)
    {
        $contractModel = new ContractModel();
        $expiredCount = $contractModel->autoExpireContracts();
        
        CLI::write("Expired {$expiredCount} contracts.", 'green');
    }
}