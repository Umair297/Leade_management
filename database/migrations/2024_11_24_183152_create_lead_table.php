<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadTable extends Migration
{
    public function up()
    {
        Schema::create('lead', function (Blueprint $table) {
            $table->id();
            $table->integer('items');
            $table->string('prestamo');
            $table->string('cod_cliente');
            $table->string('identificacion');
            $table->string('nombre_cliente');
            $table->decimal('saldo', 10, 2);
            $table->integer('dias_mora');
            $table->string('morosidad');
            $table->string('lugar_empleo')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('prov')->nullable();
            $table->text('direccion')->nullable();
            $table->enum('status', ['Sin Gestion', 'Gestion en Proceso', 'Anulado', 'Completado']);
            $table->date('follow_up_date')->nullable(); // Add this line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead');
    }
}
