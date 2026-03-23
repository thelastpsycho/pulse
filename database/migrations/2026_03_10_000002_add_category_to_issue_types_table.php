<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, make sure the categories table and data exist
        // This will be handled by running the seeder

        // Add the column as nullable first
        Schema::table('issue_types', function (Blueprint $table) {
            $table->foreignId('issue_category_id')->nullable()->after('id')->constrained('issue_categories');
        });

        // Assign existing issue types to 'General' category (id: 3)
        DB::statement('UPDATE issue_types SET issue_category_id = 3 WHERE issue_category_id IS NULL');

        // Drop the foreign key constraint
        Schema::table('issue_types', function (Blueprint $table) {
            $table->dropForeign(['issue_category_id']);
        });

        // Now make it non-nullable
        Schema::table('issue_types', function (Blueprint $table) {
            $table->foreignId('issue_category_id')->nullable(false)->change();
        });

        // Recreate the foreign key constraint
        Schema::table('issue_types', function (Blueprint $table) {
            $table->foreign('issue_category_id')->references('id')->on('issue_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issue_types', function (Blueprint $table) {
            $table->dropForeign(['issue_category_id']);
            $table->dropColumn('issue_category_id');
        });
    }
};
