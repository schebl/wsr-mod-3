<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AccessController extends Controller
{
    public function store(File $file, Request $request)
    {
        Gate::authorize('update-file', $file);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        $file->users()->attach($user);

        return response()->json($file->accesses());
    }

    public function destroy(File $file, Request $request)
    {
        Gate::authorize('update-file', $file);

        $validated = $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        $file->users()->detach($user);

        return response()->json($file->accesses());
    }
}
