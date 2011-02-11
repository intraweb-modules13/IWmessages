<?php
// $Id: pnuserapi.php,v 1.11 2005/04/28 08:25:38 markwest Exp $
// ----------------------------------------------------------------------
// POST-NUKE Content Management System
// Copyright (C) 2002 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
/**
 * Messages Module
 * 
 * Purpose of file:  User API -- 
 *                   The file that contains all user operational 
 *                   functions for the module
 *
 * @package      PostNuke_Miscellaneous_Modules
 * @subpackage   Messages
 * @version      $Id: pnuserapi.php,v 1.11 2005/04/28 08:25:38 markwest Exp $
 * @author       Mark West
 * @author       Richard Tirtadji
 * @link         http://www.postnuke.com  The PostNuke Home Page
 * @copyright    Copyright (C) 2002 by the PostNuke Development Team
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
 
/**
 * get all messages
 * 
 * @return   array         items array, or false on failure
 */
function IWmessages_userapi_getall($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	// Get arguments from argument array
	$inici = FormUtil::getPassedValue('inici', isset($args['inici']) ? $args['inici'] : null, 'REQUEST');
	$numitems = FormUtil::getPassedValue('numitems', isset($args['numitems']) ? $args['numitems'] : null, 'REQUEST');
	$uid = FormUtil::getPassedValue('uid', isset($args['uid']) ? $args['uid'] : null, 'POST');
	$filter = FormUtil::getPassedValue('filter', isset($args['filter']) ? $args['filter'] : null, 'POST');

	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}

	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($uid) || !is_numeric($uid) || $uid != UserUtil::getVar('uid')) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}

	$items = array();

	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];

	if($filter == 1){
		$marcat = " AND $c[marcat] = '1' ";
	}

	$orderby = "$c[msg_time] DESC, $c[msg_id] DESC";
	$where = "$c[to_userid]='".$uid."' AND $c[del_msg_to] <> '1'".$marcat;

	// get the objects from the db
	$items = DBUtil::selectObjectArray('IWmessages', $where, $orderby, $inici - 1, $numitems);

	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($items === false) {
		return LogUtil::registerError (__('Error! Could not load items.', $dom));
	}

	// Return the items
	return $items;
}

/**
 * @author	 Albert Pï¿œrez Monfort
 * get all messages send
 * 
 * @return   array         items array, or false on failure
 */
function IWmessages_userapi_getallsend($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	// Get arguments from argument array
	$inicisend = FormUtil::getPassedValue('inicisend', isset($args['inicisend']) ? $args['inicisend'] : null, 'REQUEST');
	$numitems = FormUtil::getPassedValue('numitems', isset($args['numitems']) ? $args['numitems'] : null, 'REQUEST');
	$uid = FormUtil::getPassedValue('uid', isset($args['uid']) ? $args['uid'] : null, 'POST');
	$filtersend = FormUtil::getPassedValue('filtersend', isset($args['filtersend']) ? $args['filtersend'] : null, 'POST');

	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}

	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($uid) || !is_numeric($uid) || $uid != UserUtil::getVar('uid')) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}

	$items = array();

	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];

	if($filtersend == 1){
		$notread = " AND $c[read_msg] = '0' ";
	}

	$orderby = "$c[msg_time] DESC, $c[msg_id] DESC";
	$where = "$c[from_userid]='".$uid."' AND $c[del_msg_from] <> '1'".$marcat.$notread;

	// get the objects from the db
	$items = DBUtil::selectObjectArray('IWmessages', $where, $orderby, $inicisend - 1, $numitems);

	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($items === false) {
		return LogUtil::registerError (__('Error! Could not load items.', $dom));
	}

	// Return the items
	return $items;
}

/**
 * get a specific message
 * 
 * @param    $args['msgid']  id of messageget
 * @param    $args['uid']  id of user
 * @return   array         item array, or false on failure
 */
function IWmessages_userapi_get($args){
	$dom = ZLanguage::getModuleDomain('IWmessages');
	// Get arguments from argument array
	$msgid = FormUtil::getPassedValue('msgid', isset($args['msgid']) ? $args['msgid'] : null, 'REQUEST');
	$uid = FormUtil::getPassedValue('uid', isset($args['uid']) ? $args['uid'] : null, 'REQUEST');

	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}

	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($msgid) || !is_numeric($msgid) || !isset($uid) || !is_numeric($uid)) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}

	$items = DBUtil::selectObjectByID('IWmessages', $msgid, 'msg_id');

	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($items === false) {
		return LogUtil::registerError (__('Error! Could not load items.', $dom));
	}

	// Return the items
	return $items;
}

/**
 * utility function to count the number of items held by this module
 * 
 * @author	 Nathan Codding
 * @param 	 $args['userid'] userid to get private message count for
 * @param    $args['unread'] if set to true, return number of unread messages only
 * @return   integer   number of items held by this module
 */
function IWmessages_userapi_countitems($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	// Get arguments from argument array
	$uid = FormUtil::getPassedValue('uid', isset($args['uid']) ? $args['uid'] : null, 'POST');
	$unread = FormUtil::getPassedValue('unread', isset($args['unread']) ? $args['unread'] : null, 'POST');
	$sv = FormUtil::getPassedValue('sv', isset($args['sv']) ? $args['sv'] : null, 'POST');
	
	if(!ModUtil::func('IWmain', 'user', 'checkSecurityValue', array('sv' => $sv))){
		// Security check
		if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
			return LogUtil::registerPermissionError();
		}
	}else{
		$requestByCron = true;
	}

	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($uid) || !is_numeric($uid) || ($uid != UserUtil::getVar('uid') && !$requestByCron)) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}

	$items = array();

	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];

	$where = "$c[to_userid]='".$uid."' AND $c[del_msg_to] <> '1'";

	if(isset($unread) && $unread == 1){
		$where .= " AND $c[read_msg] = 0";
	}

	// get the objects from the db
	$items = DBUtil::selectObjectArray('IWmessages', $where);

	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($items === false) {
		return LogUtil::registerError (__('Error! Could not load items.', $dom));
	}

	// Return the items
	return count($items);
}

/**
 * function to count the number of messages send * 
 *
 * @author	 Albert Pï¿œrez Monfort
 * @param 	 $args['userid'] userid to get private message count to
 * @param    $args['unread'] if set to true, return number of unread messages only
 * @return   integer   number of items send by this module
 */
function IWmessages_userapi_countitemssend($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	// Get arguments from argument array
	$uid = FormUtil::getPassedValue('uid', isset($args['uid']) ? $args['uid'] : null, 'POST');
	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}
	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($uid) || !is_numeric($uid) || $uid != UserUtil::getVar('uid')) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}
	$items = array();
	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];
	$where = "$c[from_userid]='".$uid."' AND $c[del_msg_from] <> '1'";
	// get the objects from the db
	$items = DBUtil::selectObjectArray('IWmessages', $where);
	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($items === false) {
		return LogUtil::registerError (__('Error! Could not load items.', $dom));
	}
	// Return the items
	return count($items);
}

/**
 * utility function to count the number of items held by this module
 * 
 * @author	 Nathan Codding
 * @param 	 $args['userid'] userid to get private message count for
 * @return   integer   number of items held by this module
 */
function IWmessages_userapi_setreadstatus($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	$msgid = FormUtil::getPassedValue('msgid', isset($args['msgid']) ? $args['msgid'] : null, 'POST');
	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}
	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($msgid) || !is_numeric($msgid)) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}
	// Get the item
	$item = ModUtil::apiFunc('IWmessages', 'user', 'get', array('uid' => UserUtil::getVar('uid'),
																'msgid' => $msgid));
	if (!$item) {
		return LogUtil::registerError (__('No such item found.', $dom));
	}
	$time = date("Y-m-d H:i");
	$items = array('read_msg' => 1,
					'msg_readtime' => $time);
	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];
	$where = "$c[msg_id]=$msgid";
	if (!DBUTil::updateObject ($items, 'IWmessages', $where)) {
		return LogUtil::registerError (__('Error! Update attempt failed.', $dom));
	}
	return true;
}

/**
 * delete a private message
 * 
 * @author	 Nathan Codding
 * @Modified by Albert Pï¿œrez Monfort
 * @param 	 $args['msgid'] userid to get private message count for
 * @return   integer   number of items held by this module
 */
function IWmessages_userapi_delete($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	// Get arguments from argument array
	$msgid = FormUtil::getPassedValue('msgid', isset($args['msgid']) ? $args['msgid'] : null, 'POST');
	$uid = FormUtil::getPassedValue('uid', isset($args['uid']) ? $args['uid'] : null, 'POST');
	$qui = FormUtil::getPassedValue('qui', isset($args['qui']) ? $args['qui'] : null, 'POST');
	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}
	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($msgid) || !is_numeric($msgid) || !isset($uid) || !is_numeric($uid)) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}

	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];
	$where = "$c[msg_id]=$msgid";
	if($qui == "d"){
		$item = array('del_msg_to' => 1);
		$where .= " AND $c[to_userid] = '" .$uid."'";
	}else{
		$item = array('del_msg_from' => 1);
		$where .= " AND $c[from_userid] = '" .$uid."'";
	}
	if (!DBUTil::updateObject ($item, 'IWmessages', $where)) {
		return LogUtil::registerError (__('Error! Update attempt failed.', $dom));
	}
	$item = ModUtil::apiFunc('IWmessages', 'user', 'get', array('uid' => UserUtil::getVar('uid'),
																'msgid' => $msgid));
	if (!$item) {
		return LogUtil::registerError (__('No such item found.', $dom));
	}
	// only if user_from and user_to have deleted the message this message is deleted from database
	$pntables = DBUtil::getTables();
	$c = $pntables['IWmessages_column'];
	$where = "$c[del_msg_to]='1' AND $c[del_msg_from]='1'";
	if (!DBUtil::deleteWhere ('IWmessages', $where)) {
		return LogUtil::registerError (__('Error! Sorry! Deletion attempt failed.', $dom));
	}
	$folder = ModUtil::getVar('IWmessages','uploadFolder');
	for($i = 1; $i < 4; $i++){
		// if the file is not called in other messages delete it from the server
		if($item['file'.$i] != ''){
			$where = "MD5(" . $c['file1'] . ") = '" . md5($item['file' . $i]) . "' OR MD5(" . $c['file2'] . ") = '" . md5($item['file' . $i]) . "' OR MD5(" . $c['file3'] . ") = '" . md5($item['file' . $i]) . "'";
			// get the objects from the db
			$items = DBUtil::selectObjectArray('IWmessages', $where);
			// Check for an error with the database code, and if so set an appropriate
			// error message and return
			if ($items === false) {
				return LogUtil::registerError (__('Error! Could not load items.', $dom));
			}
			if(count($items) == 0){
				//Delete de file from the server
				$fileName = md5($item['file'.$i].$item['from_userid']);
				$sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
				$delete = ModUtil::func('IWmain', 'user', 'deleteFile', array('sv' => $sv,
																			'folder' => $folder,
																			'fileName' => $fileName));
			}
		}
	}
	$sv = ModUtil::func('IWmain', 'user', 'genSecurityValue');
	ModUtil::func('IWmain', 'user', 'userSetVar', array('module' => 'IWmain_block_news',
														'name' => 'have_news',
														'value' => 'me',
														'sv' => $sv));
	//succesfull
	return true;
}

/**
 * create a private message
 * 
 * @author	 Nathan Codding
 * @param 	 $args['to_userid'] id of the recipient
 * @param 	 $args['image'] image for the message
 * @param 	 $args['subject'] subject for the message
 * @param 	 $args['message'] text of the message
 * @return   integer   number of items held by this module
 */
function IWmessages_userapi_create($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}

	extract($args);

	// Needed argument
	if ((!isset($to_userid) || 
		!is_numeric($to_userid)) ||
	    	(!isset($subject)) ||
		(!isset($message))) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}

	// set some defaults
	if(UserUtil::isLoggedIn()) {
		$from_userid = UserUtil::getVar('uid');
	} else {
		$from_userid = 1; // anonymous
	}
	
	$time = date("Y-m-d H:i");

	if (!isset($image)) {
		$image = '';
	}

	$item = array('msg_image' => $image,
			'subject' => $subject,
			'from_userid' => $from_userid,
			'to_userid' => $to_userid,
			'msg_time' => $time,
			'msg_text' => $message,
			'reply' => $reply,
			'file1' => $file1,
			'file2' => $file2,
			'file3' => $file3);

	if (!DBUtil::insertObject($item, 'IWmessages', 'msg_id')) {
		return LogUtil::registerError (__('Error! Creation attempt failed.', $dom));
	}

	// Let any hooks know that we have created a new item.
	ModUtil::callHooks('item', 'create', $item['msg_id'], array('module' => 'IWmessages'));

	// Return the id of the newly created item to the calling process
	return $item['msg_id'];
}

/**
 * simple helper function to replace the [addsig] placeholder with the users signature
 * if it exists.
 * 
 * @author	 Frank Schummertz
 * @param 	 $args['signature'] the users signature, maybe empty
 * @param 	 $args['message'] the message text
 * @return   string   the new message text
 */
function IWmessages_userapi_replacesignature($args)
{
	extract($args);
	unset($args);
    
	if(!empty($signature)) {
		return eregi_replace("\[addsig]", "<br />-----------------<br />" . nl2br($signature), $message);
	} else {
		return eregi_replace("\[addsig]", "", $message);
	}
}

/**
 * check a private message
 * 
 * @author	 Albert Pï¿œrez Monfort
 * @param 	 $args['msgid'] userid to get private message count for
 * @return   integer   number of items held by this module
 */
function IWmessages_userapi_check($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	$msgid = FormUtil::getPassedValue('msgid', isset($args['msgid']) ? $args['msgid'] : null, 'POST');
	$uid = FormUtil::getPassedValue('uid', isset($args['uid']) ? $args['uid'] : null, 'POST');

	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError('');
	}

	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($msgid) || !is_numeric($msgid) || !isset($uid) || !is_numeric($uid) || $uid != UserUtil::getVar('uid')) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}

	$item = ModUtil::apiFunc('IWmessages', 'user', 'get',
	                      array('uid' => UserUtil::getVar('uid'),
								'msgid' => $msgid));
	if (!$item) {
		return LogUtil::registerError (__('No such item found.', $dom));
	}

	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];

	$marcat = ($item['marcat'] == 1) ? 0 : 1;

	$items = array('marcat' => $marcat);

	$where = "$c[msg_id]=$msgid";

	if (!DBUTil::updateObject ($items, 'IWmessages', $where)) {
		return LogUtil::registerError (__('Error! Update attempt failed.', $dom));
	}

	return true;
}


/**
 * get all the users who their username begins like the parameter sended
 * 
 * @author	Albert Pï¿œrez Monfort
 * @param 	$value into the first leters
 * @return   	And array with the users names
 */
function IWmessages_userapi_getUsers($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	$value = FormUtil::getPassedValue('value', isset($args['value']) ? $args['value'] : null, 'POST');

	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}

	$items = array();

	$pntable = DBUtil::getTables();
	$c = $pntable['users_column'];

	$where = "$c[uname] like '".$value."%'";

	// get the objects from the db
	$items = DBUtil::selectObjectArray('users', $where);

	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($items === false) {
		return LogUtil::registerError (__('Error! Could not load items.', $dom));
	}

	// Return the items
	return $items;
}

/**
 * utility function to count the number of items held by this module
 * 
 * @author	 Nathan Codding
 * @param 	 $args['userid'] userid to get private message count for
 * @return   integer   number of items held by this module
 */
function IWmessages_userapi_setreplied($args)
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	$msgid = FormUtil::getPassedValue('msgid', isset($args['msgid']) ? $args['msgid'] : null, 'POST');

	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_OVERVIEW) || !UserUtil::isLoggedIn()) {
		return LogUtil::registerPermissionError();
	}

	// Argument check - make sure that all required arguments are present, if
	// not then set an appropriate error message and return
	if (!isset($msgid) || !is_numeric($msgid)) {
		return LogUtil::registerError (__('Error! Could not do what you wanted. Please check your input.', $dom));
	}

	// Get the item
	$item = ModUtil::apiFunc('IWmessages', 'user', 'get', array('uid' => UserUtil::getVar('uid'),
										'msgid' => $msgid));
	if (!$item) {
		return LogUtil::registerError (__('No such item found.', $dom));
	}

	$items = array('replied' => 1);

	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];
	$where = "$c[msg_id]=$msgid";

	if (!DBUTil::updateObject ($items, 'IWmessages', $where)) {
		return LogUtil::registerError (__('Error! Update attempt failed.', $dom));
	}

	return true;
}

/**
 * Gets all the notes where that the user has flagged
 * @author:     Albert Pï¿œrez Monfort (aperezm@xtec.cat)
 * @return:	And array with the items information
*/
function IWmessages_userapi_getFlagged()
{
	$dom = ZLanguage::getModuleDomain('IWmessages');
	// Security check
	if (!SecurityUtil::checkPermission( 'IWmessages::', '::', ACCESS_READ)) {
		return LogUtil::registerPermissionError();
	}
	
	$pntable = DBUtil::getTables();
	$c = $pntable['IWmessages_column'];

	$where = "$c[to_userid] = ".UserUtil::getVar('uid')." AND $c[marcat] = 1 AND $c[del_msg_to] = 0";
	$orderby = "$c[msg_time] desc";

	// get the objects from the db
	$items = DBUtil::selectObjectArray('IWmessages', $where, $orderby, '-1', '-1', 'msg_id');

	// Check for an error with the database code, and if so set an appropriate
	// error message and return
	if ($items === false) {
		return LogUtil::registerError (__('Error! Could not load items.', $dom));
	}

	// Return the items
	return $items;
}

