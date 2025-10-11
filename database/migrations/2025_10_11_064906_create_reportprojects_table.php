<?php
// database/migrations/2023_10_11_create_projects_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('project_title');
            $table->string('owner_name');
            $table->string('project_status');
            $table->decimal('total_budget', 15, 2);
            $table->string('currency');
            $table->text('description')->nullable();
            $table->text('additional_info')->nullable();
            $table->json('files')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
