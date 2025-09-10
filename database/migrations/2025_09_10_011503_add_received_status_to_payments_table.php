<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReceivedStatusToPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Adicionar o status RECEIVED ao enum existente
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('PENDING', 'CONFIRMED', 'RECEIVED', 'RECEIVED_IN_CASH', 'OVERDUE', 'REFUNDED', 'RECEIVED_WITH_OVERDUE', 'CHARGEBACK_REQUESTED', 'CHARGEBACK_DISPUTE', 'AWAITING_CHARGEBACK_REVERSAL', 'DUNNING_REQUESTED', 'DUNNING_RECEIVED', 'AWAITING_RISK_ANALYSIS') DEFAULT 'PENDING'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remover o status RECEIVED do enum
        DB::statement("ALTER TABLE payments MODIFY COLUMN status ENUM('PENDING', 'CONFIRMED', 'RECEIVED_IN_CASH', 'OVERDUE', 'REFUNDED', 'RECEIVED_WITH_OVERDUE', 'CHARGEBACK_REQUESTED', 'CHARGEBACK_DISPUTE', 'AWAITING_CHARGEBACK_REVERSAL', 'DUNNING_REQUESTED', 'DUNNING_RECEIVED', 'AWAITING_RISK_ANALYSIS') DEFAULT 'PENDING'");
    }
}
