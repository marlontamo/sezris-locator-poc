<?php

namespace App\Http\Controllers;

use App\Models\Permit;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RequirementController extends Controller
{
    // Show requirements for a specific permit
    public function show( $id)
    {
       $permit = Permit::findOrFail($id);
        
    return Inertia::render('Permits/Upload', [
        'permit' => $permit,
    ]);

       
    }

    
}
