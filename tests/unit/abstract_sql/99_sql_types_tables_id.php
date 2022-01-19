<?php

	// nom du test
	$test = 'sql/sql_types_tables_id';

	$remonte = '../';
	while (!is_dir($remonte . 'ecrire')) {
		$remonte = "../$remonte";
	}
	require $remonte . 'tests/test.inc';

	include_spip('base/connect_sql');

// Des tests
$essais['table_objet'] = [
['petitions','petition'],
['signatures','signature'],
];

$essais['table_objet_sql'] = [
['spip_petitions','petition'],
['spip_signatures','signature'],
];

$essais['id_table_objet'] = [
['id_petition','petition'],
['id_signature','signature'],
];


$essais['objet_type'] = [
['petition','petitions'],
['signature','signatures'],
];

	// hop ! on y va
	$err = [];
	foreach ($essais as $f => $essai) {
		$err = array_merge(tester_fun($f, $essai), $err);
	}

	// si le tableau $err est pas vide ca va pas
	if ($err) {
		echo ('<dl>' . join('', $err) . '</dl>');
	} else {
		echo 'OK';
	}
