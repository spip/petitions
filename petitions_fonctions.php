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

/**
 * Compile la balise `#PETITION` permettant d'afficher, s'il existe,
 * le texte de pétition associé à l'article.
 *
 * Retourne :
 *
 * - `''` si l'article courant n'a pas de pétition
 * - le texte de celle-ci sinon (et `' '` si il est vide)
 *
 * @see balise_FORMULAIRE_SIGNATURE()
 * @link https://www.spip.net/3967
 *
 * @param Champ $p
 *    Pile au niveau de la balise
 * @return Champ
 *    Pile complétée du code à générer
 */
function balise_PETITION_dist($p) {
	$nom = index_boucle($p);
	if (
		($nom === '' or empty($p->boucles[$nom]))
		and $p->nom_champ !== 'FORMULAIRE_SIGNATURE'
	) {
		$msg = array(
			'zbug_champ_hors_boucle',
			array('champ' => '#PETITION')
		);
		erreur_squelette($msg, $p);
	}
	else {
		if (!empty($p->boucles[$nom])) {
			$p->code = "quete_petitions(" .
				champ_sql('id_article', $p)
				. ",'" .$p->boucles[$nom]->type_requete . "'" 
				. ",'$nom'," 
				. "'" . $p->boucles[$nom]->sql_serveur . "'"
				. ", \$Cache)";
		} else {
			$p->code = "quete_petitions(" .
				champ_sql('id_article', $p)
				. ",''" 
				. ",'$nom'," 
				. "\$connect"
				. ", \$Cache)";
		}

		$p->interdire_scripts = false;
	}

	return $p;
}

if (!function_exists('quete_petitions')) {
	/**
	 * retourne le champ 'texte' d'une petition
	 *
	 * @param int $id_article
	 * @param string $table
	 * @param string $id_boucle
	 * @param string $serveur
	 * @param array $cache
	 * @return array|bool|null|string
	 */
	function quete_petitions($id_article, $table, $id_boucle, $serveur, &$cache) {
		$retour = sql_getfetsel('texte', 'spip_petitions', ("id_article=" . intval($id_article)), '', array(), '', '',
			$serveur);

		if ($retour === null) {
			return '';
		}
		# cette page est invalidee par toute petition
		$cache['varia']['pet' . $id_article] = 1;

		# ne pas retourner '' car le texte sert aussi de presence
		return $retour ?: ' ';
	}
}
