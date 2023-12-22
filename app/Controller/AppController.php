<?php

App::uses('Controller', 'Controller');
class AppController extends Controller {

    public $components = array('Session', 'Auth' => [
            'loginRedirect' => [
                    'controller' => 'posts',
                    'action' => 'index',
                    'home'
            ],
            'authenticate' => [
                    'Form' => [
                            'userModel' => 'Users',
                    ]
            ]
    ], 'Paginator');

    public function beforeFilter() {
//        $this->Auth->allow('index', 'users');
//        $this->Auth->loginAction     = array('controller' => 'users', 'action' => 'login');
//        $this->Auth->fields          = array('username' => 'username', 'password' => 'password');
    }
    //...
}
