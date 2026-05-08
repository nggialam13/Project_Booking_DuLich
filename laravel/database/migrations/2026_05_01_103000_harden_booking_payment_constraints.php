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
     * Harden data integrity for booking/payment domain.
     */
    public function up(): void
    {
        // 1) Ensure existing rows always have booking_code before NOT NULL.
        DB::table('bookings')
            ->whereNull('booking_code')
            ->orderBy('id')
            ->chunkById(200, function ($rows): void {
                foreach ($rows as $row) {
                    DB::table('bookings')
                        ->where('id', $row->id)
                        ->update([
                            'booking_code' => 'BK-' . now()->format('ymd') . '-' . str_pad((string) $row->id, 6, '0', STR_PAD_LEFT),
                        ]);
                }
            });

        // Validate existing data before adding UNIQUE constraints.
        $duplicateBookingDetails = DB::table('booking_details')
            ->select('booking_id')
            ->groupBy('booking_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicateBookingDetails) {
            throw new RuntimeException('Cannot add unique constraint: booking_details contains duplicate booking_id.');
        }

        $duplicatePayments = DB::table('payments')
            ->select('booking_id')
            ->groupBy('booking_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicatePayments) {
            throw new RuntimeException('Cannot add unique constraint: payments contains duplicate booking_id.');
        }

        // 2) Enforce 1-1 for booking -> booking_detail.
        if (!$this->indexExists('booking_details', 'booking_details_booking_id_unique')) {
            Schema::table('booking_details', function (Blueprint $table): void {
                $table->unique('booking_id', 'booking_details_booking_id_unique');
            });
        }

        // 3) Enforce 1-1 for booking -> payment (1 booking has at most 1 payment).
        if (!$this->indexExists('payments', 'payments_booking_id_unique')) {
            Schema::table('payments', function (Blueprint $table): void {
                $table->unique('booking_id', 'payments_booking_id_unique');
            });
        }

        // 4) Rebuild booking_code unique index with safe length for older MySQL key limits.
        if ($this->indexExists('bookings', 'bookings_booking_code_unique')) {
            DB::statement('ALTER TABLE bookings DROP INDEX bookings_booking_code_unique');
        }
        DB::statement('ALTER TABLE bookings MODIFY booking_code VARCHAR(191) NOT NULL');
        if (!$this->indexExists('bookings', 'bookings_booking_code_unique')) {
            Schema::table('bookings', function (Blueprint $table): void {
                $table->unique('booking_code', 'bookings_booking_code_unique');
            });
        }

        // 5) Add numeric safety constraints (prevent invalid negative values).
        if (!$this->checkConstraintExists('booking_details', 'chk_booking_details_quantity_positive')) {
            DB::statement('ALTER TABLE booking_details ADD CONSTRAINT chk_booking_details_quantity_positive CHECK (quantity > 0)');
        }
        if (!$this->checkConstraintExists('booking_details', 'chk_booking_details_price_non_negative')) {
            DB::statement('ALTER TABLE booking_details ADD CONSTRAINT chk_booking_details_price_non_negative CHECK (price >= 0)');
        }
        if (!$this->checkConstraintExists('bookings', 'chk_bookings_total_price_non_negative')) {
            DB::statement('ALTER TABLE bookings ADD CONSTRAINT chk_bookings_total_price_non_negative CHECK (total_price >= 0)');
        }
        if (!$this->checkConstraintExists('payments', 'chk_payments_amount_non_negative')) {
            DB::statement('ALTER TABLE payments ADD CONSTRAINT chk_payments_amount_non_negative CHECK (amount >= 0)');
        }
    }

    /**
     * Roll back integrity constraints.
     */
    public function down(): void
    {
        // Drop CHECK constraints first.
        if ($this->checkConstraintExists('booking_details', 'chk_booking_details_quantity_positive')) {
            DB::statement('ALTER TABLE booking_details DROP CHECK chk_booking_details_quantity_positive');
        }
        if ($this->checkConstraintExists('booking_details', 'chk_booking_details_price_non_negative')) {
            DB::statement('ALTER TABLE booking_details DROP CHECK chk_booking_details_price_non_negative');
        }
        if ($this->checkConstraintExists('bookings', 'chk_bookings_total_price_non_negative')) {
            DB::statement('ALTER TABLE bookings DROP CHECK chk_bookings_total_price_non_negative');
        }
        if ($this->checkConstraintExists('payments', 'chk_payments_amount_non_negative')) {
            DB::statement('ALTER TABLE payments DROP CHECK chk_payments_amount_non_negative');
        }

        // Roll back NOT NULL (keep length 191 to stay compatible with key-length limit).
        DB::statement('ALTER TABLE bookings MODIFY booking_code VARCHAR(191) NULL');

        if ($this->indexExists('payments', 'payments_booking_id_unique')) {
            Schema::table('payments', function (Blueprint $table): void {
                $table->dropUnique('payments_booking_id_unique');
            });
        }

        if ($this->indexExists('booking_details', 'booking_details_booking_id_unique')) {
            Schema::table('booking_details', function (Blueprint $table): void {
                $table->dropUnique('booking_details_booking_id_unique');
            });
        }
    }
};
