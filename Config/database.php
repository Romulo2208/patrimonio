<?php

class DATABASE_CONFIG {

    public $default = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
		'login' => 'phpmyadmin',
		'password' => 'Falcon@Britacal_2019',
        'database' => 'patrimonio',
        'prefix' => '',
        'encoding' => 'utf8',
    );

    public $admin = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
		'login' => 'phpmyadmin',
		'password' => 'Falcon@Britacal_2019',
        'database' => 'admin',
        'prefix' => '',
        'encoding' => 'utf8',
    );
        
    public $almoxarifado = array(
        'datasource' => 'Database/Mysql',
        'persistent' => false,
        'host' => 'localhost',
		'login' => 'phpmyadmin',
		'password' => 'Falcon@Britacal_2019',
        'database' => 'patrimonio',
        'prefix' => '',
        'encoding' => 'utf8',
    );



	public $financeiro = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'phpmyadmin',
		'password' => 'Falcon@Britacal_2019',
		'database' => 'financeiro',
		'prefix' => '',
		'encoding' => 'utf8',
	);
}
