public function up()
{
    Schema::table('tasks', function (Blueprint $table) {
        // Kita ubah kolom module_id agar boleh NULL
        $table->unsignedBigInteger('module_id')->nullable()->change();
    });
}

public function down()
{
    Schema::table('tasks', function (Blueprint $table) {
        $table->unsignedBigInteger('module_id')->nullable(false)->change();
    });
}