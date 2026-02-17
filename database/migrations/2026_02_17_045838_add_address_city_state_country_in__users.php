<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Country;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address')->nullable()->after('password');
            $table->string('city')->nullable()->after('address');
            $table->string('state')->nullable()->after('city');
            $table->foreignIdFor(Country::class)->nullable()->after('state')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $column = ['address','city','state','country_id'];
        Schema::table('users', function (Blueprint $table) use ($column) {

            if (Schema::hasColumn('users', 'country_id')) {
                $table->dropForeign(['users_country_id_foreign']); 
            }

            $existingColumn = array_filter($column, fn($clm) => Schema::hasColumn('users',$clm));
            if (!empty($existingColumn)) {
                $table->dropColumn($existingColumn);
            }
        });
    }
};
