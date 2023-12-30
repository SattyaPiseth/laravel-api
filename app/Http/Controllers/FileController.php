<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $files = Storage::disk('public')->allFiles('uploads');
        $files = array_map(function ($file) {
            return [
                'name' => basename($file),
                'url' => Storage::disk('public')->url($file),
                'size' => round(Storage::disk('public')->size($file) / 1024 / 1024, 2) . 'MB',
                'created_at' => \Carbon\Carbon::parse(Storage::disk('public')->lastModified($file))->diffForHumans(),
                'extension' => pathinfo($file, PATHINFO_EXTENSION),
            ];
        }, $files);

        return response()->json([
            'message' => 'Files retrieved successfully.',
            'files' => $files
        ], 200);
    }

    public function store_single(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
        ]);

        $fileName = md5($request->file->getClientOriginalName() . time()) . "." . $request->file->getClientOriginalExtension();
        $filePath = $request->file('file')->storeAs('uploads', $fileName, 'public');
        if ($filePath) {

            return response()->json([

                'message' => 'File uploaded successfully.',
                'file' => [
                    'name' => basename($filePath),
                    'url' => Storage::disk('public')->url($filePath),
                    'size' => round(Storage::disk('public')->size($filePath) / 1024 / 1024, 2) . 'MB',
                    'created_at' => \Carbon\Carbon::parse(Storage::disk('public')->lastModified($filePath))->diffForHumans(),
                    'extension' => pathinfo($filePath, PATHINFO_EXTENSION),
                ]
            ], 201);
        }

        return response()->json([
            'message' => 'File not uploaded!',
        ], 400);
    }

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $file = Storage::disk('public')->exists('uploads/' . $id);
        if ($file) {
            $file = [
                'name' => basename($id),
                'url' => Storage::disk('public')->url('uploads/' . $id),
                'size' => round(Storage::disk('public')->size('uploads/' . $id) / 1024 / 1024, 2) . 'MB',
                'created_at' => \Carbon\Carbon::parse(Storage::disk('public')->lastModified('uploads/' . $id))->diffForHumans(),
                'extension' => pathinfo($id, PATHINFO_EXTENSION),
            ];
            return response()->json([
                'message' => 'File retrieved successfully.',
                'file' => $file
            ], 200);
        }
        return response()->json([
            'message' => 'File not found!',
        ], 404);
    }

    public function delete($id): \Illuminate\Http\JsonResponse
    {
        $file = Storage::disk('public')->exists('uploads/' . $id);
        if ($file) {
            Storage::disk('public')->delete('uploads/' . $id);
            return response()->json([
                'message' => 'File deleted successfully.',
            ], 200);
        }
        return response()->json([
            'message' => 'File not found!',
        ], 404);
    }

    public function deleteAll(): \Illuminate\Http\JsonResponse
    {
        $files = Storage::disk('public')->allFiles('uploads');
        if ($files) {
            Storage::disk('public')->delete($files);
            return response()->json([
                'message' => 'All files deleted successfully.',
            ], 200);
        }
        return response()->json([
            'message' => 'Files not found!',
        ], 404);
    }
}
