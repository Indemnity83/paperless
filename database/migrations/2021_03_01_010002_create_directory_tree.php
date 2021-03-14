<?php

use App\Models\DirectoryTree;
use App\Models\File;
use App\Models\Folder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryTree extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the root folder
        $root = DirectoryTree::make(['parent_id' => null]);
        $root->object()->associate(Folder::create(['name' => 'All Files']));
        $root->save();

        // Bring any existing files under the root folder
        foreach(File::all() as $file) {
            $fileTree = DirectoryTree::make(['parent_id' => $root->id]);
            $fileTree->object()->associate($file);
            $fileTree->save();
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
