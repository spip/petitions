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
}  #securite

//
// Formulaire de signature d'une petition
//

include_spip('base/abstract_sql');

// Contexte necessaire lors de la compilation

// Il *faut* demander petition, meme si on ne s'en sert pas dans l'affichage,
// car on doit obtenir la jointure avec la table des petitions pour verifier
// si une petition est attachee a l'article.

function balise_FORMULAIRE_SIGNATURE($p) {
	return calculer_balise_dynamique($p, 'FORMULAIRE_SIGNATURE', ['id_article', 'petition']);
}

// Verification des arguments (contexte + filtres)
function balise_FORMULAIRE_SIGNATURE_stat($args, $context_compil) {

	// pas d'id_article => erreur de contexte
	if (!$args[0]) {
		$msg = [
			'zbug_champ_hors_motif',
			[
				'champ' => 'FORMULAIRE_SIGNATURE',
				'motif' => 'ARTICLES'
			]
		];
		erreur_squelette($msg, $context_compil);

		return '';
	} // article sans petition => pas de balise
	else {
		if (!$args[1]) {
			return '';
		}
	}

	// on envoie pas cet argument dans le CVT
	unset($args[1]);

	return $args;
}
