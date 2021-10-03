<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()
            ->references('id')
            ->on('users');
            $table->foreignId('role_id')->references('id')->on('roles');
            $table->timestamps();
        });

        $users = User::all();
        foreach($users as $user) {
            DB::table('user_roles')->insert([
                'user_id' => $user->id,
                'role_id' => $user->role_id,
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
        Schema::dropIfExists('user_roles');
    }
}
