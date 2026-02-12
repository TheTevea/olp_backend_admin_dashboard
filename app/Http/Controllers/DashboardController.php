<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        return view('dashboard', [
            'pageTitle' => 'Dashboard',
            'migrationStatus' => $this->getMigrationStatus(),
        ]);
    }

    /**
     * Get current migration status.
     */
    private function getMigrationStatus()
    {
        return [
            'phase1' => [
                'name' => 'Setup & Preparation',
                'status' => 'completed',
                'percentage' => 100,
            ],
            'phase2' => [
                'name' => 'Database Migration',
                'status' => 'completed',
                'percentage' => 100,
            ],
            'phase3' => [
                'name' => 'Models Migration',
                'status' => 'in_progress',
                'percentage' => 50,
            ],
            'phase4' => [
                'name' => 'Controllers Migration',
                'status' => 'in_progress',
                'percentage' => 1,
            ],
            'phase5' => [
                'name' => 'Views Migration',
                'status' => 'pending',
                'percentage' => 0,
            ],
            'phase6' => [
                'name' => 'Assets & Configuration',
                'status' => 'pending',
                'percentage' => 0,
            ],
            'phase7' => [
                'name' => 'Testing & Validation',
                'status' => 'pending',
                'percentage' => 0,
            ],
        ];
    }
}
