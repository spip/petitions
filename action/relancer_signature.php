<?php

/***************************************************************************\
 *  SPIP, Systeme de publication pour l'internet                           *
 *                                                                         *
 *  Copyright (c) 2001-2009                                                *
 *  Arnaud Martin, Antoine Pitrou, Philippe Riviere, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribue sous licence GNU/GPL.     *
 *  Pour plus de details voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

if (!defined("_ECRIRE_INC_VERSION")) return;

function action_relancer_signature_dist($id_signature=null){
	if (!$id_signature){
		$securiser_action = charger_fonction('securiser_action','inc');
		$id_signature = $securiser_action();
	}

	$row = sql_fetsel('*', 'spip_signatures', 'id_signature='.intval($id_signature));
	$id_article = $row['id_article'];
	if ($id_article AND autoriser('mordererpetition','article',$id_article)) {
		include_spip('action/editer_signature');
		include_spip('formulaires/signature');
		$url = generer_url_entite_absolue($id_article, 'article','','',true);
		if (signature_a_confirmer($id_article, $url, $row['nom_email'], $row['ad_email'], $row['nom_site'], $row['url_site'], $row['message'], $row['lang'], $row['statut']))
			signature_set($id_signature, array("date_time" => date('Y-m-d H:i:s')));
	}
}

?>