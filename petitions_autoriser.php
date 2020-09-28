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
 * Définit les autorisations du plugin Pétitions
 *
 * @package SPIP\Petitions\Autorisations
 **/
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Fonction d'appel pour le pipeline
 *
 * @pipeline autoriser
 */
function petitions_autoriser() { }


/**
 * Autorisation de modérer une pétition
 *
 * Il faut que les pétitions soient activées globalement ou qu'il y en ait au moins une
 * et avoir droit de modifier l'objet qui reçoit la pétition
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 **/
function autoriser_modererpetition_dist($faire, $type, $id, $qui, $opt) {
	include_spip('inc/config');
	$activer_petitions = (lire_config('petitions/activer_petitions') === 'oui');
	$nb_petitions = sql_countsel('spip_petitions', 'id_article=' . intval($id));
	$autoriser =
		($activer_petitions or $nb_petitions > 0)
		and autoriser('modifier', $type, $id, $qui, $opt);
	return $autoriser;
}

/**
 * Autorisation de publier une signature
 *
 * Il faut avoir le droit de modérer la petition de l'article en question
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 */
function autoriser_signature_publier($faire, $type, $id, $qui, $opt) {
	$id_article = sql_getfetsel('P.id_article',
		'spip_signatures AS S JOIN spip_petitions AS P ON P.id_petition=S.id_petition', 'S.id_signature=' . intval($id));

	return
		autoriser('modererpetition', 'article', $id_article, $qui, $opt);
}

/**
 * Autorisation de supprimer une signature
 *
 * Il faut avoir le droit de modérer la petition de l'article en question
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 */
function autoriser_signature_supprimer($faire, $type, $id, $qui, $opt) {
	$id_article = sql_getfetsel('P.id_article',
		'spip_signatures AS S JOIN spip_petitions AS P ON P.id_petition=S.id_petition', 'S.id_signature=' . intval($id));

	return
		autoriser('modererpetition', 'article', $id_article, $qui, $opt);
}

/**
 * Autorisation de relancer une signature
 *
 * Toute personne idenfiée peut relancer une signature non publiée
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 */
function autoriser_signature_relancer($faire, $type, $id, $qui, $opt) {
	$statut = sql_getfetsel('statut', 'spip_signatures', 'id_signature=' . intval($id));

	return ($qui['id_auteur'] && !in_array($statut, array('poubelle', 'publie')));
}

/**
 * Autorisation de modifier une signature
 *
 * Jamais
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 */
function autoriser_signature_modifier_dist($faire, $type, $id, $qui, $opt) {
	return
		false;
}

/**
 * Autorisation de voir la page controler_petition
 *
 * Il faut que les pétitions soient activées globalement
 * et qu'il y ait au moins une signature
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 **/
function autoriser_controlerpetition_voir_dist($faire, $type, $id, $qui, $opt) {
	include_spip('inc/config');
	$autoriser =
		(lire_config('petitions/activer_petitions') === 'oui')
		and sql_countsel('spip_signatures') > 0;
	return $autoriser;
}

/**
 * Autorisation de voir le menu de gestion des signatures
 *
 * @uses autoriser_controlerpetition_voir_dist()
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 */
function autoriser_controlerpetition_menu_dist($faire, $type = '', $id = 0, $qui = null, $opt = null) {
	return autoriser('voir', '_controlerpetition', $id, $qui, $opt);
}

/**
 * Autorisation d'auto-association de documents sur des signatures
 *
 * Jamais
 *
 * @param  string $faire Action demandée
 * @param  string $type Type d'objet sur lequel appliquer l'action
 * @param  int $id Identifiant de l'objet
 * @param  array $qui Description de l'auteur demandant l'autorisation
 * @param  array $opt Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
 */
function autoriser_signature_autoassocierdocument_dist($faire, $type, $id, $qui, $opt) {
	return false;
}
