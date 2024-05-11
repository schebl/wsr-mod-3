<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(Request $request)
    {
        $response = [];

        foreach ($request->user()->files as $file) {
            $response[] = [
                'file_id' => $file->id,
                'name' => 'Имя файла',
                'url' => url('/files/' . $file->id),
                'accesses' => $file->accesses(),
            ];
        }

        return response()->json($response);
    }

    public function store(Request $request)
    {
        $response = [];

        foreach ($request->file('files') ?? [] as $file) {
            $validator = Validator::make(
                ['file' => $file],
                ['file' => ['required', 'file', 'max:2000', 'mimes:doc,pdf,docx,zip,jpeg,jpg,png']]
            );

            $name = $file->getClientOriginalName();

            if ($validator->fails()) {
                $response[] = [
                    'success' => false,
                    'message' => 'File not loaded',
                    'name' => $name,
                ];

                continue;
            }

            // Замена одинаковых имён
            $original_name = $name;
            $files = $request->user()->files();
            for ($i = 1;
                 $files->where('name', $name)->exists();
                 $i++) {
                $name = Str::of($original_name)->beforeLast('.') . " ($i)." . $file->getClientOriginalExtension();
            }

            $path = $file->store('public');

            $file = File::create([
                'name' => $name,
                'path' => $path,
                'owner_id' => $request->user()->id,
            ]);

            $response[] = [
                'success' => true,
                'message' => 'Success',
                'name' => $file->name,
                'url' => url('/files/' . $file->id),
                'file_id' => $file->id,
            ];
        }

        return response()->json($response);
    }

    public function update(File $file, Request $request)
    {
        Gate::authorize('update-file', $file);

        $validated = $request->validate([
            'name' => ['required', 'unique:files,name'],
        ]);

        $file->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Renamed',
        ]);
    }

    public function destroy(File $file)
    {
        Gate::authorize('update-file', $file);

        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File already deleted',
        ]);
    }

    public function download(File $file)
    {
        Gate::authorize('access-file', $file);

        return Storage::download($file->path);
    }
}
