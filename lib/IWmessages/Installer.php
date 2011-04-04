<?php

/**
 * PostNuke Application Framework
 *
 * @copyright (c) 2002, PostNuke Development Team
 * @link http://www.postnuke.com
 * @version $Id: pninit.php 22139 2007-06-01 10:57:16Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package PostNuke_Value_Addons
 * @subpackage Noteboard
 */

/**
 * Initialise the IWmessages module creating module tables and module vars
 * @author Albert Pérez Monfort (aperezm@xtec.cat)
 * @return bool true if successful, false otherwise
 */
class IWmessages_Installer extends Zikula_AbstractInstaller {

    public function Install() {
        // Checks if module IWmain is installed. If not returns error
        $modid = ModUtil::getIdFromName('IWmain');
        $modinfo = ModUtil::getInfo($modid);

        if ($modinfo['state'] != 3) {
            return LogUtil::registerError($this->__('Module IWmain is needed. You have to install the IWmain module before installing it.'));
        }

        // Check if the version needed is correct
        $versionNeeded = '3.0.0';
        if (!ModUtil::func('IWmain', 'admin', 'checkVersion',
                        array('version' => $versionNeeded))) {
            return false;
        }

        // Create module tables
        if (!DBUtil::createTable('IWmessages'))
            return false;

        //Create indexes
        $pntable = DBUtil::getTables();
        $c = $pntable['IWmessages_column'];
        if (!DBUtil::createIndex($c['from_userid'], 'IWmessages', 'from_userid'))
            return false;
        if (!DBUtil::createIndex($c['to_userid'], 'IWmessages', 'to_userid'))
            return false;

        // activate the bbsmile hook for this module if the module is present
        if (ModUtil::available('pn_bbsmile')) {
            ModUtil::apiFunc('Modules', 'admin', 'enablehooks',
                            array('callermodname' => 'IWmessages',
                                'hookmodname' => 'pn_bbsmile'));
        }
        if (ModUtil::available('pn_bbcode')) {
            ModUtil::apiFunc('Modules', 'admin', 'enablehooks',
                            array('callermodname' => 'IWmessages',
                                'hookmodname' => 'pn_bbcode'));
        }

        //Set module vars
        ModUtil::setVar('IWmessages', 'groupsCanUpdate', '$');
        ModUtil::setVar('IWmessages', 'uploadFolder', 'messages');
        ModUtil::setVar('IWmessages', 'multiMail', '$');
        ModUtil::setVar('IWmessages', 'limitInBox', '50');
        ModUtil::setVar('IWmessages', 'limitOutBox', '50');

        return true;
    }

    /**
     * Delete the IWmessages module
     * @author Albert Pérez Monfort (aperezm@xtec.cat)
     * @return bool true if successful, false otherwise
     */
    public function uninstall() {
        // Delete module table
        DBUtil::dropTable('IWmessages');

        //Delete module vars
        ModUtil::delVar('IWmessages', 'groupsCanUpdate');
        ModUtil::delVar('IWmessages', 'uploadFolder');
        ModUtil::delVar('IWmessages', 'multiMail');
        ModUtil::delVar('IWmessages', 'limitInBox');
        ModUtil::delVar('IWmessages', 'limitOutBox');

        //Deletion successfull
        return true;
    }

    /**
     * Update the IWmessages module
     * @author Albert Pérez Monfort (aperezm@xtec.cat)
     * @return bool true if successful, false otherwise
     */
    public function upgrade($oldversion) {
        if (!DBUtil::changeTable('IWmessages'))
            return false;

        if ($oldversion < 1.3) {
            //Create indexes
            $pntable = DBUtil::getTables();
            $c = $pntable['IWmessages_column'];
            if (!DBUtil::createIndex($c['from_userid'], 'IWmessages', 'from_userid'))
                return false;
            if (!DBUtil::createIndex($c['to_userid'], 'IWmessages', 'to_userid'))
                return false;
        }
        return true;
    }

}