<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use Illuminate\Http\Request;
use App\Models\PermitApproval;
use Inertia\Inertia;

class PermitController extends Controller
{
    /**
     * Show all available Permits
     */
    public function index()
    {
        $permits = $permits = Permit::all()->toArray();

        return Inertia::render('Permits/Index', [
            'permits' => $permits
        ]);
    }

    /**
     * Show a single permit with its approvals
     */
    public function show($id)
    {
        $permit = Permit::with([
                                'user',                  // creator
                                'approvals.approver',    // approvals + approvers
                                'uploads'
                              ])->findOrFail($id);

        return Inertia::render('Permits/Show', [
            'permit' => $permit
        ]);
    }

    /**
     * Update a permit approval
     */
    public function updateApproval(Request $request, $approvalId)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string|max:255',
        ]);

        $approval = PermitApproval::findOrFail($approvalId);

        // Ensure only assigned approver can act
        if ($approval->approver_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $approval->update([
            'status'   => $request->status,
            'remarks'  => $request->remarks,
            'acted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Approval updated successfully!');
    }

    /**
     * Approve a permit
     */
    public function approve($id)
    {
        $approval = PermitApproval::findOrFail($id);
        $approval->update([
            'status' => 'approved',
            'acted_at' => now(),
        ]);

        $permit = Permit::findOrFail($approval->permit_id);
        $permit->update([
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Approval recorded.');
    }

    /**
     * Reject a permit
     */
    public function reject($id)
    {
        $approval = PermitApproval::findOrFail($id);
        $approval->update([
            'status' => 'rejected',
            'acted_at' => now(),
        ]);

        $permit = Permit::findOrFail($approval->permit_id);
        $permit->update([
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Rejection recorded.');
    }

    /**
     * Show the form to create a permit
     */
    public function create()
    {
        return Inertia::render('Permits/Create');
    }

    /**
     * Store a new permit
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'permit_type' => 'required|string|max:50'
        ]);

        Permit::create([
            'title' => $request->title,
            'description' => $request->description,
            'permit_type' => $request->permit_type,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('permits.index')->with('success', 'Permit created successfully!');
    }

    /**
     * Return approval to previous step
     */
    public function returnToPrevious(PermitApproval $approval)
    {
        // Ensure not step 1
        if ($approval->step > 1) {
            $previousApproval = PermitApproval::where('permit_id', $approval->permit_id)
                ->where('step', $approval->step - 1)
                ->first();

            if ($previousApproval) {
                // Reopen previous step
                $previousApproval->update([
                    'status' => 'pending',
                    'remarks' => 'Returned for re-approval',
                    'acted_at' => null,
                ]);

                // Mark current step as "returned"
                $approval->update([
                    'status' => 'returned',
                    'remarks' => 'Sent back to previous approver',
                    'acted_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Approval returned to previous step.');
    }
}
