<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permit;
use App\Models\PermitApproval;
use App\Models\User;

class PermitSeeder extends Seeder
{
    public function run(): void
    {
        $locator = User::firstOrCreate(
            ['email' => 'locator@example.com'],
            [
                'name' => 'Locator User',
                'password' => bcrypt('password')
            ]
        );

        $approver1 = User::firstOrCreate(
            ['email' => 'chris@jhmc.com'],
            [
                'name' => 'OSAC Processor',
                'password' => bcrypt('password')
            ]
        );

        $approver2 = User::firstOrCreate(
            ['email' => 'gerald@jhmc.com'],
            [
                'name' => 'SEZAD MANAGER',
                'password' => bcrypt('password')
            ]
        );

        $approver3 = User::firstOrCreate(
            ['email' => 'noel@jhmc.com'],
            [
                'name' => 'CCO Inspector',
                'password' => bcrypt('password')
            ]
        );

        // --- Create Permit ---
        $permit = Permit::create([
            'user_id'     => $locator->id,
            'permit_type' => 'Building',
            'description' => 'Demo project for kiosk installation',
            'start_date'  => now(),
            'expiry_date' => now()->addDays(30),
            'status'      => 'pending',
        ]);

        // --- Create Sequential Approvals ---
        $steps = [
            ['approver_id' => $approver1->id, 'step' => 1],
            ['approver_id' => $approver2->id, 'step' => 2],
            ['approver_id' => $approver3->id, 'step' => 3],
        ];

        foreach ($steps as $step) {
            PermitApproval::create([
                'permit_id'   => $permit->id,
                'approver_id' => $step['approver_id'],
                'step'        => $step['step'],
                'status'      => 'pending',
            ]);
        }
    }
}
