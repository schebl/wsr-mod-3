<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(Request $request) {}

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
            for ($i = 1; File::where('name', $name)->exists(); $i++) {
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

    public function update(File $file, Request $request) {}

    public function destroy(File $file) {}

    public function download(File $file) {}
}
