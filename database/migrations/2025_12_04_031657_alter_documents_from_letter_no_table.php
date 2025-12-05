<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('letter_no')->nullable()->after('description');
            $table->date('letter_date')->nullable()->after('letter_no');
            $table->string('number_in')->nullable()->after('letter_date');
            $table->string('doc_from')->nullable()->after('letter_no');
            $table->date('dateline')->nullable()->after('doc_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('letter_no');
            $table->dropColumn('letter_date');
            $table->dropColumn('number_in');
            $table->dropColumn('doc_from');
            $table->dropColumn('dateline');
        });
    }
};
