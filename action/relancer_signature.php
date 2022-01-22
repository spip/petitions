<?php

/***************************************************************************\
 *  SPIP, Système de publication pour l'internet                           *
 *                                                                         *
 *  Copyright © avec tendresse depuis 2001                                 *
 *  Arnaud Martin, Antoine Pitrou, Philippe Rivière, Emmanuel Saint-James  *
 *                                                                         *
 *  Ce programme est un logiciel libre distribué sous licence GNU/GPL.     *
 *  Pour plus de détails voir le fichier COPYING.txt ou l'aide en ligne.   *
\***************************************************************************/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

function action_relancer_signature_dist($id_signature = null) {
	if (!$id_signature) {
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$id_signature = $securiser_action();
	}

	if (autoriser('relancer', 'signature', $id_signature)) {
		$row = sql_fetsel('*', 'spip_signatures', 'id_signature=' . intval($id_signature));
		$id_article = sql_getfetsel('id_article', 'spip_petitions', 'id_petition=' . intval($row['id_petition']));
		if (
			$id_article = intval($id_article)
			and autoriser('mordererpetition', 'article', $id_article)
		) {
			include_spip('action/editer_signature');
			include_spip('formulaires/signature');
			$url = generer_objet_url_absolue($id_article, 'article', '', '', true);
			if (
				signature_a_confirmer(
					$id_article,
					$url,
					$row['nom_email'],
					$row['ad_email'],
					$row['nom_site'],
					$row['url_site'],
					$row['message'],
					$row['lang'],
					$row['statut']
				)
			) {
				signature_modifier($id_signature, ['date_time' => date('Y-m-d H:i:s')]);
			}
		}
	}
}
