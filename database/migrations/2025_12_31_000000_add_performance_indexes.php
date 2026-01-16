<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visitors', function (Blueprint $table) {
            if (!$this->indexExists('visitors', 'visitors_company_id_index')) {
                $table->index('company_id');
            }
            if (!$this->indexExists('visitors', 'visitors_branch_id_index')) {
                $table->index('branch_id');
            }
            if (!$this->indexExists('visitors', 'visitors_department_id_index')) {
                $table->index('department_id');
            }
            if (!$this->indexExists('visitors', 'visitors_status_index')) {
                $table->index('status');
            }
            if (!$this->indexExists('visitors', 'visitors_phone_index')) {
                $table->index('phone');
            }
        });

        Schema::table('security_checks', function (Blueprint $table) {
            if (!$this->indexExists('security_checks', 'security_checks_visitor_id_index')) {
                $table->index('visitor_id');
            }
        });

        Schema::table('departments', function (Blueprint $table) {
            if (!$this->indexExists('departments', 'departments_company_id_index')) {
                $table->index('company_id');
            }
        });

        Schema::table('branches', function (Blueprint $table) {
            if (!$this->indexExists('branches', 'branches_company_id_index')) {
                $table->index('company_id');
            }
        });
    }

    private function indexExists($table, $indexName)
    {
        $indexes = \DB::select("SHOW INDEX FROM {$table}");
        foreach ($indexes as $index) {
            if ($index->Key_name === $indexName) {
                return true;
            }
        }
        return false;
    }

    public function down()
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
            $table->dropIndex(['branch_id']);
            $table->dropIndex(['department_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['company_id', 'status']);
            $table->dropIndex(['in_time', 'out_time']);
        });

        Schema::table('security_checks', function (Blueprint $table) {
            $table->dropIndex(['visitor_id']);
        });

        Schema::table('departments', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });

        Schema::table('branches', function (Blueprint $table) {
            $table->dropIndex(['company_id']);
        });
    }
};