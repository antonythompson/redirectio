<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Domains extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('domain');
            $table->timestamps();
        });
        Schema::create('ips', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ip');
            $table->integer('server_id')->unsigned();
            $table->foreign('server_id')->references('id')->on('servers');
            $table->timestamps();
        });
        Schema::create('websites', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active');
            $table->string('domain');
            $table->integer('ip_id')->unsigned();
            $table->foreign('ip_id')->references('id')->on('ips');
            $table->timestamps();
        });
        Schema::create('sub_domains', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('website_id')->unsigned();
            $table->foreign('website_id')->references('id')->on('websites');
            $table->string('url');
            $table->timestamps();
        });
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('ssl')->default(0);
            $table->boolean('ip_correct')->default(0);
            $table->boolean('ssl_redirect')->default(0);
            $table->boolean('google_analytics')->default(0);
            $table->timestamps();
        });
        Schema::create('sub_domain_test', function (Blueprint $table) {
            $table->integer('sub_domain_id')->unsigned();
            $table->foreign('sub_domain_id', 'sub_test_sub_id')->references('id')->on('sub_domains');
            $table->integer('test_id')->unsigned();
            $table->foreign('test_id', 'sub_test_test_id')->references('id')->on('tests');
        });
        Schema::create('test_website', function (Blueprint $table) {
            $table->integer('test_id')->unsigned();
            $table->foreign('test_id')->references('id')->on('tests');
            $table->integer('website_id')->unsigned();
            $table->foreign('website_id')->references('id')->on('websites');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('websites');
        Schema::dropIfExists('sub_domains');
        Schema::dropIfExists('sub_domain_test');
        Schema::dropIfExists('test_website');
        Schema::dropIfExists('tests');
    }
}
