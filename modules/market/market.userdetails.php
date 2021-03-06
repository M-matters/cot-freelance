<?php

/**
 * [BEGIN_COT_EXT]
 * Hooks=users.details.tags
 * [END_COT_EXT]
 */

/**
 * market module
 *
 * @package market
 * @version 2.5.2
 * @author CMSWorks Team
 * @copyright Copyright (c) CMSWorks.ru, littledev.ru
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL');
require_once cot_incfile('market', 'module');

list($usr['auth_read'], $usr['auth_write'], $usr['isadmin']) = cot_auth('market', 'any', 'RWA');

$tab = cot_import('tab', 'G', 'ALP');
list($pg, $d, $durl) = cot_import_pagenav('dmarket', $cfg['market']['cat___default']['maxrowsperpage']);

//маркет вкладка
$t1 = new XTemplate(cot_tplfile(array('market','userdetails'), 'module'));
$t1->assign(array(
	"ADDPRD_URL" => cot_url('market', 'm=add'),
	"PRD_ADDPRD_URL" => cot_url('market', 'm=add'),
	"ADDPRD_SHOWBUTTON" => ($usr['auth_write']) ? true : false,
	"RPD_ADDPRD_SHOWBUTTON" => ($usr['auth_write']) ? true : false, // for compatibility with previous versions
));

$where = array();
$order = array();

if($usr['id'] == 0 || $usr['id'] != $urr['user_id'] && !$usr['isadmin'])
{
	$where['state'] = "item_state=0";
}

$where['owner'] = "item_userid=" . $urr['user_id'];

$order['date'] = "item_date DESC";

/* === Hook === */
foreach (cot_getextplugins('market.userdetails.query') as $pl)
{
	include $pl;
}
/* ===== */

$where = ($where) ? 'WHERE ' . implode(' AND ', $where) : '';
$order = ($order) ? 'ORDER BY ' . implode(', ', $order) : '';

$sql_market_count = $db->query("SELECT * FROM $db_market as m 
	" . $where . "");
$market_count = $sql_market_count->rowCount();

$sqllist = $db->query("SELECT * FROM $db_market AS m
	" . $where . "
	" . $order . "
	LIMIT $d, " . $cfg['market']['cat___default']['maxrowsperpage']);

$pagenav = cot_pagenav('users', 'm=details&id=' . $urr['user_id'] . '&u=' . $urr['user_name'] . '&tab=market', $d, $market_count, $cfg['market']['cat___default']['maxrowsperpage'], 'dmarket');

$t1->assign(array(
	"PAGENAV_PAGES" => $pagenav['main'],
	"PAGENAV_PREV" => $pagenav['prev'],
	"PAGENAV_NEXT" => $pagenav['next'],
	"PAGENAV_COUNT" => $market_count,
));

$sqllist_rowset = $sqllist->fetchAll();
$sqllist_idset = array();

foreach($sqllist_rowset as $item)
{
	$sqllist_idset[$item['item_id']] = $item['item_alias'];
}

/* === Hook === */
$extp = cot_getextplugins('market.userdetails.loop');
/* ===== */

foreach ($sqllist_rowset as $item)
{
	$t1->assign(cot_generate_markettags($item, 'PRD_ROW_', $cfg['market']['shorttextlen'], $usr['isadmin'], $cfg['homebreadcrumb']));
	
	/* === Hook === */
	foreach ($extp as $pl)
	{
		include $pl;
	}
	/* ===== */

	$t1->parse("MAIN.PRD_ROWS");
}

/* === Hook === */
	foreach (cot_getextplugins('market.userdetails.tags') as $pl)
	{
		include $pl;
	}
	/* ===== */

$t1->parse("MAIN");

$t->assign(array(
	"USERS_DETAILS_MARKET_COUNT" => $market_count,
	"USERS_DETAILS_MARKET_URL" => cot_url('users', 'm=details&id=' . $urr['user_id'] . '&u=' . $urr['user_name'] . '&tab=market'),
));

$t->assign('MARKET', $t1->text("MAIN"));