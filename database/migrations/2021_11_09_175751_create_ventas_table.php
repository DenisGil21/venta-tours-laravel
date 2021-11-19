<?php

use App\Models\Venta;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cliente');
            $table->string('telefono');
            $table->integer('cantidad_adultos');
            $table->integer('cantidad_ninos');
            $table->integer('subtotal');
            $table->float('total',8,2);
            $table->date('fecha');
            $table->string('status')->default(Venta::RESERVADO);
            $table->integer('user_id')->unsigned();
            $table->integer('paquete_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('paquete_id')->references('id')->on('paquetes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
