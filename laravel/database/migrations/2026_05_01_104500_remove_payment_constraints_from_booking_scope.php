<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function indexExists(string $table, string $indexName): bool
    {
        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }

    private function checkConstraintExists(string $table, string $constraintName): bool
    {
        return DB::table('information_schema.table_constraints')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('constraint_name', $constraintName)
            ->where('constraint_type', 'CHECK')
            ->exists();
    }

    /**
     * Keep scope only for Booking owner (remove Payment constraints).
     */
    public function up(): void
    {
        // Remove unique(booking_id) from payments so Payment owner can decide business rules.
        if ($this->indexExists('payments', 'payments_booking_id_unique')) {
            Schema::table('payments', function (Blueprint $table): void {
                $table->dropUnique('payments_booking_id_unique');
            });
        }

        // Remove payment amount check to avoid touching Payment teammate's domain.
        if ($this->checkConstraintExists('payments', 'chk_payments_amount_non_negative')) {
            DB::statement('ALTER TABLE payments DROP CHECK chk_payments_amount_non_negative');
        }
    }

    /**
     * Re-apply the removed Payment constraints if rolled back.
     */
    public function down(): void
    {
        if (!$this->indexExists('payments', 'payments_booking_id_unique')) {
            Schema::table('payments', function (Blueprint $table): void {
                $table->unique('booking_id', 'payments_booking_id_unique');
            });
        }

        if (!$this->checkConstraintExists('payments', 'chk_payments_amount_non_negative')) {
            DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payments_amount_non_negative CHECK (amount >= 0)');
        }
    }
};
