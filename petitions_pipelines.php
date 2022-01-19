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

/**
 * Utilisations de pipelines
 *
 * @package SPIP\Petitions\Pipelines
 **/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Boite de configuration des objets articles
 *
 * @param array $flux
 * @return array
 */
function petitions_afficher_config_objet($flux) {
	if (
		(($type = $flux['args']['type']) == 'article')
		and ($id = $flux['args']['id'])
	) {
		if (autoriser('modererpetition', $type, $id)) {
			$table = table_objet($type);
			$id_table_objet = id_table_objet($type);
			$flux['data'] .= recuperer_fond('prive/configurer/petitionner', [$id_table_objet => $id]);
		}
	}

	return $flux;
}

/**
 * Utilisation du pipeline affiche milieu
 *
 * Ajoute le formulaire de configuration des pétitions sur la configuration des contenus
 *
 * @pipeline affiche_milieu
 *
 * @param array $flux
 *     Données du pipeline
 * @return array
 *     Données du pipeline
 */
function petitions_affiche_milieu($flux) {

	if ($flux['args']['exec'] == 'configurer_contenu') {
		$flux['data'] .= recuperer_fond('prive/squelettes/inclure/configurer', ['configurer' => 'configurer_petitions']);
	}

	return $flux;
}

/**
 * Optimiser la base de données en supprimant les pétitions orphelines
 *
 * @param array $flux
 * @return array
 */
function petitions_optimiser_base_disparus($flux) {
	$n = &$flux['data'];
	$mydate = $flux['args']['date'];

	//
	// Signatures poubelles
	//

	sql_delete('spip_petitions', 'statut=' . sql_quote('poubelle') . ' AND maj < ' . sql_quote($mydate));

	// rejeter les signatures non confirmees trop vieilles (20jours)
	if (!defined('_PETITIONS_DELAI_SIGNATURES_REJETEES')) {
		define('_PETITIONS_DELAI_SIGNATURES_REJETEES', 20);
	}
	sql_delete('spip_signatures', "NOT (statut='publie' OR statut='poubelle') AND NOT(" . sql_date_proche(
		'date_time',
		-_PETITIONS_DELAI_SIGNATURES_REJETEES,
		' DAY'
	) . ')');


	return $flux;
}
