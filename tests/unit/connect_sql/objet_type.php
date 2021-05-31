<?php
/**
 * Test unitaire de la fonction heures
 * du fichier inc/filtres.php
 *
 * genere automatiquement par TestBuilder
 * le 
 */

	$test = 'objet_type';
	$remonte = "../";
	while (!is_dir($remonte."ecrire"))
		$remonte = "../$remonte";
	require $remonte.'tests/test.inc';
	find_in_path("base/connect_sql.php",'',true);

	//
	// hop ! on y va
	//
	$err = tester_fun('objet_type', essais_objet_type());
	
	// si le tableau $err est pas vide ca va pas
	if ($err) {
		die ('<dl>' . join('', $err) . '</dl>');
	}

	echo "OK";
	

	function essais_objet_type(){
		$essais = array (
  array (
    0 => 'petition',
    1 => 'petitions',
  ),
  array (
    0 => 'petition',
    1 => 'petition',
  ),
  array (
    0 => 'petition',
    1 => 'spip_petitions',
  ),
  array (
    0 => 'signature',
    1 => 'signatures',
  ),
  array (
    0 => 'signature',
    1 => 'signature',
  ),
  array (
    0 => 'signature',
    1 => 'spip_signatures',
  ),
  array (
    0 => 'signature',
    1 => 'id_signature',
  ),
array('petition','petitions'),
array('signature','signatures'),
);
		return $essais;
	}

