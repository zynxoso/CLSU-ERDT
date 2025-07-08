<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update photo paths to remove 'faculty/' prefix
        $facultyMembers = DB::table('faculty_members')
            ->whereNotNull('photo_path')
            ->where('photo_path', 'LIKE', 'faculty/%')
            ->get();

        foreach ($facultyMembers as $faculty) {
            // Extract filename from path (remove 'faculty/' prefix)
            $filename = str_replace('faculty/', '', $faculty->photo_path);

            // Update database record
            DB::table('faculty_members')
                ->where('id', $faculty->id)
                ->update(['photo_path' => $filename]);

            // Move file from storage to public/experts if it exists
            $oldPath = storage_path('app/public/' . $faculty->photo_path);
            $newPath = public_path('experts/' . $filename);

            if (file_exists($oldPath) && !file_exists($newPath)) {
                // Ensure experts directory exists
                if (!is_dir(public_path('experts'))) {
                    mkdir(public_path('experts'), 0755, true);
                }

                // Copy file to new location
                copy($oldPath, $newPath);

                // Optionally delete old file (uncomment if you want to clean up)
                // unlink($oldPath);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore photo paths with 'faculty/' prefix
        $facultyMembers = DB::table('faculty_members')
            ->whereNotNull('photo_path')
            ->where('photo_path', 'NOT LIKE', 'faculty/%')
            ->get();

        foreach ($facultyMembers as $faculty) {
            // Add 'faculty/' prefix back
            $pathWithPrefix = 'faculty/' . $faculty->photo_path;

            DB::table('faculty_members')
                ->where('id', $faculty->id)
                ->update(['photo_path' => $pathWithPrefix]);
        }
    }
};
