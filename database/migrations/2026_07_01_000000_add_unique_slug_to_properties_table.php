<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix any duplicate slugs before adding the unique constraint.
        // For each group of duplicates, keep the record with the lowest id
        // and append -<id> to every other record.
        $duplicates = DB::table('properties')
            ->select('slug', DB::raw('COUNT(*) as cnt'))
            ->groupBy('slug')
            ->having('cnt', '>', 1)
            ->pluck('slug');

        foreach ($duplicates as $slug) {
            $records = DB::table('properties')
                ->where('slug', $slug)
                ->orderBy('id')
                ->pluck('id');

            // Skip the first (lowest id) — only rename the rest
            foreach ($records->skip(1) as $id) {
                $newSlug = $slug . '-' . $id;
                // Ensure even the new slug is unique
                $suffix = $newSlug;
                $counter = 2;
                while (DB::table('properties')->where('slug', $suffix)->exists()) {
                    $suffix = $newSlug . '-' . $counter++;
                }
                DB::table('properties')->where('id', $id)->update(['slug' => $suffix]);
            }
        }

        Schema::table('properties', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
};
