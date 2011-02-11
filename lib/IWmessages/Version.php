<?php
 /**
 * Load the module version information
 *
 * @author		Albert Pérez Monfort (intraweb@xtec.cat)
 * @return		The version information
 */
$dom = ZLanguage::getModuleDomain('IWmessages');
$modversion['name'] = 'IWmessages';
$modversion['version'] = '2.0';
$modversion['description'] = __('Allow to send private messages between users', $dom);
$modversion['displayname']    = __('IWMessages', $dom);
$modversion['url']    = __('IWmessages', $dom);
$modversion['credits'] = 'pndocs/credits.txt';
$modversion['help'] = 'pndocs/help.txt';
$modversion['changelog'] = 'pndocs/changelog.txt';
$modversion['license'] = 'pndocs/license.txt';
$modversion['official'] = 0;
$modversion['author'] = 'Richard Tirtadji & Albert Pérez Monfort';
$modversion['contact'] = 'rtirtadji@hotmail.com & aperezm@xtec.es';
$modversion['admin'] = 1;
$modversion['securityschema'] = array('IWmessages::' => '::');
$modversion['dependencies'] = array(array('modname' => 'IWmain',
											'minversion' => '2.0',
											'maxversion' => '',
											'status' => ModUtil::DEPENDENCY_REQUIRED));
