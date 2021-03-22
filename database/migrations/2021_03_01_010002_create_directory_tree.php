<?php

use Illuminate\Database\Migrations\Migration;

class CreateDirectoryTree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('folders')->insert([
            'name' => 'All Files',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('objects')->insert([
            'item_type' => 'folder',
            'item_id' => DB::table('folders')->first()->id,
            'parent_id' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $root_id = DB::table('objects')->first()->id;
        $files = DB::table('files')->select('id')->get();

        foreach($files as $file) {
            DB::table('objects')->insert([
                'item_type' => 'file',
                'item_id' => $file->id,
                'parent_id' => $root_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
