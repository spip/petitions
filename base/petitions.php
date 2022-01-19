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
 * Déclarations relatives à la base de données
 *
 * @package SPIP\Petitions\Pipelines
 **/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Interfaces des tables petitions et signatures pour le compilateur
 *
 * @pipeline declarer_tables_interfaces
 * @param array $interfaces
 *     Déclarations d'interface pour le compilateur
 * @return array
 *     Déclarations d'interface pour le compilateur
 */
function petitions_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['petitions'] = 'petitions';
	$interfaces['table_des_tables']['signatures'] = 'signatures';

	$interfaces['exceptions_des_tables']['signatures']['date'] = 'date_time';
	$interfaces['exceptions_des_tables']['signatures']['nom'] = 'nom_email';
	$interfaces['exceptions_des_tables']['signatures']['email'] = 'ad_email';

	$interfaces['tables_jointures']['spip_articles'][] = 'petitions';
	$interfaces['tables_jointures']['spip_articles'][] = 'signatures';

	$interfaces['exceptions_des_jointures']['petition'] = ['spip_petitions', 'texte'];
	$interfaces['exceptions_des_jointures']['id_signature'] = ['spip_signatures', 'id_signature'];

	$interfaces['table_des_traitements']['MESSAGE'][] = _TRAITEMENT_RACCOURCIS;

	// Signatures : passage des donnees telles quelles, sans traitement typo
	// la securite et conformite XHTML de ces champs est assuree par safehtml()
	foreach (['NOM_EMAIL', 'AD_EMAIL', 'NOM_SITE', 'URL_SITE', 'MESSAGE'] as $balise) {
		if (!isset($interfaces['table_des_traitements'][$balise]['signatures'])) {
			$interfaces['table_des_traitements'][$balise]['signatures'] = 'liens_nofollow(safehtml(%s))';
		} else {
			if (strpos($interfaces['table_des_traitements'][$balise]['signatures'], 'safehtml') == false) {
				$interfaces['table_des_traitements'][$balise]['signatures'] = 'liens_nofollow(safehtml(' . $interfaces['table_des_traitements'][$balise]['signatures'] . '))';
			}
		}
	}

	return $interfaces;
}

/**
 * Déclarer les objets éditoriaux des pétitions et signatures
 *
 * @pipeline declarer_tables_objets_sql
 * @param array $tables
 *     Description des tables
 * @return array
 *     Description complétée des tables
 */
function petitions_declarer_tables_objets_sql($tables) {
	$tables['spip_petitions'] = [
		'url_voir' => 'controler_petition',
		'url_edit' => 'controler_petition',
		'editable' => 'non',
		'principale' => 'oui',
		'page' => '', // pas de page editoriale pour une petition

		'texte_retour' => 'icone_retour',
		'texte_objets' => 'petitions:titre_petitions',
		'texte_objet' => 'petitions:titre_petition',

		'titre' => "texte as titre, '' AS lang",

		'field' => [
			'id_petition' => 'bigint(21) NOT NULL',
			'id_article' => "bigint(21) DEFAULT '0' NOT NULL",
			'email_unique' => "CHAR (3) DEFAULT '' NOT NULL",
			'site_obli' => "CHAR (3) DEFAULT '' NOT NULL",
			'site_unique' => "CHAR (3) DEFAULT '' NOT NULL",
			'message' => "CHAR (3) DEFAULT '' NOT NULL",
			'texte' => "LONGTEXT DEFAULT '' NOT NULL",
			'statut' => "VARCHAR (10) DEFAULT 'publie' NOT NULL",
			'maj' => 'TIMESTAMP'
		],
		'key' => [
			'PRIMARY KEY' => 'id_petition',
			'UNIQUE KEY id_article' => 'id_article'
		],
		'statut' => [
			['champ' => 'statut', 'publie' => 'publie,off', 'previsu' => 'publie,off', 'exception' => ['statut']],
		],
		'champs_editables' => [
			'email_unique',
			'site_obli',
			'site_unique',
			'message',
			'texte',
		],
		'champs_versionnes' => [
			'email_unique',
			'site_obli',
			'site_unique',
			'message',
			'texte',
		],
	];

	$tables['spip_signatures'] = [
		'url_voir' => 'controler_petition',
		'url_edit' => 'controler_petition',
		'editable' => 'non',
		'principale' => 'oui',
		'page' => '', // pas de page editoriale pour une signature

		'texte_retour' => 'icone_retour',
		'texte_objets' => 'public:signatures_petition',
		'texte_objet' => 'entree_signature',
		'info_aucun_objet' => 'petitions:aucune_signature',
		'info_1_objet' => 'petitions:une_signature',
		'info_nb_objets' => 'petitions:nombre_signatures',
		'titre' => "nom_email as titre, '' AS lang",
		'date' => 'date_time',

		'field' => [
			'id_signature' => 'bigint(21) NOT NULL',
			'id_petition' => "bigint(21) DEFAULT '0' NOT NULL",
#			"id_article"	=> "bigint(21) DEFAULT '0' NOT NULL",
			'date_time' => "datetime DEFAULT '0000-00-00 00:00:00' NOT NULL",
			'nom_email' => "text DEFAULT '' NOT NULL",
			'ad_email' => "text DEFAULT '' NOT NULL",
			'nom_site' => "text DEFAULT '' NOT NULL",
			'url_site' => "text DEFAULT '' NOT NULL",
			'message' => "mediumtext DEFAULT '' NOT NULL",
			'statut' => "varchar(10) DEFAULT '0' NOT NULL",
			'maj' => 'TIMESTAMP'
		],
		'key' => [
			'PRIMARY KEY' => 'id_signature',
			'KEY id_petition' => 'id_petition',
#			"KEY id_article"	=> "id_article",
			'KEY statut' => 'statut'
		],
		'join' => [
			'id_signature' => 'id_signature',
			'id_petition' => 'id_petition'
		],
		'tables_jointures' => [
			'petitions'
		],
		'statut' => [
			['champ' => 'statut', 'publie' => 'publie', 'previsu' => 'publie', 'exception' => ['statut', 'tout']],
		],
		'rechercher_champs' => [
			'nom_email' => 2,
			'ad_email' => 4,
			'nom_site' => 2,
			'url_site' => 4,
			'message' => 1
		],
		'champs_editables' => [
			'nom_email',
			'ad_email',
			'nom_site',
			'url_site',
			'message',
		],
		'champs_versionnes' => [
			'nom_email',
			'ad_email',
			'nom_site',
			'url_site',
			'message',
		],
		'icone_objet' => 'petition',
	];

	return $tables;
}
