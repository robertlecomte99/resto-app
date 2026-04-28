<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('menus', function (Blueprint $table) {
            $table->time('order_deadline')->default('11:30:00')->after('menu_date');
        });
    }
    public function down(): void {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('order_deadline');
        });
    }
};