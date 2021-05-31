<?php
/**
 * Test unitaire de la fonction heures
 * du fichier inc/filtres.php
 *
 * genere automatiquement par TestBuilder
 * le 
 */

	$test = 'table_objet';
	$remonte = "../";
	while (!is_dir($remonte."ecrire"))
		$remonte = "../$remonte";
	require $remonte.'tests/test.inc';
	find_in_path("base/connect_sql.php",'',true);

	//
	// hop ! on y va
	//
	$err = tester_fun('table_objet', essais_table_objet());
	
	// si le tableau $err est pas vide ca va pas
	if ($err) {
		die ('<dl>' . join('', $err) . '</dl>');
	}

	echo "OK";
	

	function essais_table_objet(){
		$essais = array (
  array (
    0 => 'petitions',
    1 => 'petition',
  ),
  array (
    0 => 'petitions',
    1 => 'petitions',
  ),
  array (
    0 => 'petitions',
    1 => 'spip_petitions',
  ),
  array (
    0 => 'signatures',
    1 => 'signature',
  ),
  array (
    0 => 'signatures',
    1 => 'signatures',
  ),
  array (
    0 => 'signatures',
    1 => 'spip_signatures',
  ),
  array (
    0 => 'signatures',
    1 => 'id_signature',
  ),
array('petitions','petition'),
array('signatures','signature'),
);
		return $essais;
	}
