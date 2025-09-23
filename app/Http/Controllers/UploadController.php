<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Upload;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
//use thiagoalessio\TesseractOCR\TesseractOCR;

class UploadController extends Controller
{
    // List All uploaded files
    public function index(Request $request)
    {
        $query = Upload::query()->with('user')->latest();

        if ($search = $request->input('search')) {
            $query->where('file_name', 'like', "%{$search}%")
                  ->orWhere('file_type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $uploads = $query->paginate(20);

        return Inertia::render('Uploads/Index', [
            'uploads' => $uploads,
            'filters' => $request->only('search'),
        ]);
    }
    // Show upload form
    public function create()
    {
        return Inertia::render('Uploads/Create');
    }

    // Handle file upload
    public function store(Request $request)
    {   
    $request->validate([
        'file' => 'required|file|max:5120', // max 5MB
    ]);

    $file = $request->file('file');

    // Save the file to storage/app/public/uploads
    $path = $file->store('uploads', 'public');

    // Save file details to the database
    $upload = Upload::create([
        'file_name' => $file->getClientOriginalName(), // original name
        'file_path' => $path,                          // storage path
        'file_type' => $file->getClientMimeType(),     // MIME type
        'file_size' => $file->getSize(),               // size in bytes
        'user_id'   => auth()->id(),                   // user who uploaded
        'description' => null                           // optional
    ]);

    // Return JSON response for Axios
    return response()->json([
        'message' => 'File uploaded successfully!',
        'upload' => $upload
    ]);
    }

    // List uploaded files
   

    // Download file
    public function download($id)
    {
        $upload = Upload::findOrFail($id);
        return Storage::disk('public')->download($upload->file_path, $upload->file_name);
    }

    public function destroy($id)
    {
        $upload = Upload::findOrFail($id);

        if (Storage::disk('public')->exists($upload->file_path)) {
            Storage::disk('public')->delete($upload->file_path);
        }

        $upload->delete();

        return redirect()->route('uploads.index')->with('success', 'File deleted successfully!');
    }

    public function view($id)
    {
        $upload = Upload::findOrFail($id);
        $filePath = storage_path('app/public/' . $upload->file_path);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->file($filePath);
    }
   
}
