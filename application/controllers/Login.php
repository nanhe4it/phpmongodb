<?php
/**
 * @package PHPmongoDB
 * @version 1.0.0
 * @link http://www.phpmongodb.org
 */
defined('PMDDA') or die('Restricted access');

class LoginController extends Controller {

    public function Index() {
        if ($this->request->isPost()) {
            if ($this->request->getParam('username') == Config::$authentication['user'] && $this->request->getParam('password') == Config::$authentication['password']) {
                Application::getInstance('Session')->isLogedIn = TRUE;
                $this->request->redirect(Theme::URL('Collection/Index'));
            }
            $this->message->error = I18n::t('AUTH_FAIL');
        } 
        $data = array();
        $this->display('index', $data);
    }

    public function Logout() {
        Application::getInstance('Session')->destroy();
        $this->request->redirect(Theme::URL('Login/Index'));
    }

}
