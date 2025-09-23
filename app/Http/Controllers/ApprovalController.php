<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permit;
use App\Models\PermitApproval;

class ApprovalController extends Controller
{
    public function approve(PermitApproval $approval)
    {
        $approval->update([
            'status'   => 'approved',
            'acted_at' => now(),
        ]);

        // If this was the last step, mark the permit as approved
        $allApproved = $approval->permit->approvals()->where('status', '!=', 'approved')->count() === 0;
        if ($allApproved) {
            $approval->permit->update(['status' => 'approved']);
        }

        return back()->with('success', 'Permit step approved successfully.');
    }

    public function reject(PermitApproval $approval)
    {
        $approval->update([
            'status'   => 'rejected',
            'acted_at' => now(),
        ]);

        $approval->permit->update(['status' => 'rejected']);

        return back()->with('error', 'Permit rejected.');
    }

    public function returnToPrevious(PermitApproval $approval)
    {
        if ($approval->step > 1) {
            $previousApproval = PermitApproval::where('permit_id', $approval->permit_id)
                ->where('step', $approval->step - 1)
                ->first();

            if ($previousApproval) {
                $previousApproval->update([
                    'status'   => 'pending',
                    'remarks'  => 'Returned for re-approval',
                    'acted_at' => null,
                ]);

                $approval->update([
                    'status'   => 'returned',
                    'remarks'  => 'Sent back to previous approver',
                    'acted_at' => now(),
                ]);

                $approval->permit->update(['status' => 'pending']);
            }
        }

        return back()->with('info', 'Approval returned to previous step.');
    }
}
