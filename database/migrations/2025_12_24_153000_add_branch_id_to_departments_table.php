<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (!Schema::hasColumn('departments', 'branch_id')) {
                $table->foreignId('branch_id')
                    ->nullable()
                    ->after('company_id')
                    ->constrained('branches')
                    ->nullOnDelete();
                $table->index('branch_id');
            }
        });

        $branchMap = DB::table('branches')
            ->select('id', 'company_id')
            ->orderBy('id')
            ->get()
            ->groupBy('company_id');

        DB::table('departments')
            ->select('id', 'company_id', 'branch_id')
            ->orderBy('id')
            ->chunkById(100, function ($departments) use ($branchMap) {
                foreach ($departments as $department) {
                    if (!empty($department->branch_id)) {
                        continue;
                    }

                    $branches = $branchMap->get($department->company_id, collect());
                    if ($branches->isEmpty()) {
                        continue;
                    }

                    $firstBranchId = $branches->first()->id ?? null;
                    if ($firstBranchId) {
                        DB::table('departments')
                            ->where('id', $department->id)
                            ->update(['branch_id' => $firstBranchId]);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'branch_id')) {
                $table->dropForeign(['branch_id']);
                $table->dropIndex(['branch_id']);
                $table->dropColumn('branch_id');
            }
        });
    }
};
