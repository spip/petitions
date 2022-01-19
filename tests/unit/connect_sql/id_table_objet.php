<?php

/**
 * Test unitaire de la fonction heures
 * du fichier inc/filtres.php
 *
 * genere automatiquement par TestBuilder
 * le
 */

	$test = 'id_table_objet';
	$remonte = '../';
	while (!is_dir($remonte . 'ecrire')) {
		$remonte = "../$remonte";
	}
	require $remonte . 'tests/test.inc';
	find_in_path('base/connect_sql.php', '', true);

	//
	// hop ! on y va
	//
	$err = tester_fun('id_table_objet', essais_id_table_objet());

	// si le tableau $err est pas vide ca va pas
	if ($err) {
		die('<dl>' . join('', $err) . '</dl>');
	}

	echo 'OK';


	function essais_id_table_objet() {
		$essais =  [
   [
	0 => 'id_petition',
	1 => 'petitions',
  ],
   [
	0 => 'id_petition',
	1 => 'petition',
  ],
   [
	0 => 'id_petition',
	1 => 'spip_petitions',
  ],
   [
	0 => 'id_signature',
	1 => 'signatures',
  ],
   [
	0 => 'id_signature',
	1 => 'signature',
  ],
   [
	0 => 'id_signature',
	1 => 'spip_signatures',
  ],
   [
	0 => 'id_signature',
	1 => 'id_signature',
  ],
['id_petition','petition'],
['id_signature','signature'],
];
		return $essais;
	}
