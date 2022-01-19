<?php

/**
 * Test unitaire de la fonction heures
 * du fichier inc/filtres.php
 *
 * genere automatiquement par TestBuilder
 * le
 */

	$test = 'objet_type';
	$remonte = '../';
	while (!is_dir($remonte . 'ecrire')) {
		$remonte = "../$remonte";
	}
	require $remonte . 'tests/test.inc';
	find_in_path('base/connect_sql.php', '', true);

	//
	// hop ! on y va
	//
	$err = tester_fun('objet_type', essais_objet_type());

	// si le tableau $err est pas vide ca va pas
	if ($err) {
		die('<dl>' . join('', $err) . '</dl>');
	}

	echo 'OK';


	function essais_objet_type() {
		$essais =  [
   [
	0 => 'petition',
	1 => 'petitions',
  ],
   [
	0 => 'petition',
	1 => 'petition',
  ],
   [
	0 => 'petition',
	1 => 'spip_petitions',
  ],
   [
	0 => 'signature',
	1 => 'signatures',
  ],
   [
	0 => 'signature',
	1 => 'signature',
  ],
   [
	0 => 'signature',
	1 => 'spip_signatures',
  ],
   [
	0 => 'signature',
	1 => 'id_signature',
  ],
['petition','petitions'],
['signature','signatures'],
];
		return $essais;
	}
