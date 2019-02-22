<?php
/**
 * DokuWiki Plugin authkrb (Auth Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Will Gnann <gnann@ime.usp.br>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) {
    die();
}

class auth_plugin_authkrb extends DokuWiki_Auth_Plugin {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct();

        if (!class_exists('KRB5CCache')) {
            $this->success = false;
            return;
        }

        $this->success = true;
    }

    /**
     * Check user+password
     *
     * This module relies on /etc/krb5.conf!
     *
     * @param   string $user the user name
     * @param   string $pass the clear text password
     * @return  bool
     */
    public function checkPass($user, $pass) {
        if (empty($pass)) return false;

        $ticket = new KRB5CCache();

        try {
            $ticket->initPassword($user, $pass);
            return true;
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * Return user info
     *
     * Returns info about the given user needs to contain
     * at least these fields:
     *
     * name string  full name of the user
     * mail string  email addres of the user
     * grps array   list of groups the user is in
     *
     * @param   string $user          the user name
     * @param   bool   $requireGroups whether or not the returned data must include groups
     * @return  array  containing user data or false
     */
    public function getUserData($user, $requireGroups=true) {
        $info = array();
        $info['name'] = $user;
        $info['mail'] = $user;
        $info['grps'] = array();

        return $info;
    }
}
