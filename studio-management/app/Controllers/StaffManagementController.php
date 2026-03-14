<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\StudioModel;

class StaffManagementController extends BaseController
{
    protected $studioModel;

    public function __construct()
    {
        $this->studioModel = model(StudioModel::class);
    }

    public function index()
    {
        $data['title'] = 'Studio Staff Directory';
        $data['studios'] = $this->studioModel->findAll();
        return view('studio/staff_management/index', $data);
    }
}

