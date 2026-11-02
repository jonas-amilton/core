<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catalog_products', function (Blueprint $table) {
            $table->text('search_text')->nullable()->after('description');
        });

        DB::table('catalog_products')->orderBy('id')->chunkById(200, function ($rows): void {
            foreach ($rows as $row) {
                DB::table('catalog_products')->where('id', $row->id)->update([
                    'search_text' => Str::lower(Str::ascii(implode(' ', array_filter([
                        $row->name,
                        $row->sku,
                        $row->description,
                    ])))),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('catalog_products', function (Blueprint $table) {
            $table->dropColumn('search_text');
        });
    }
};
