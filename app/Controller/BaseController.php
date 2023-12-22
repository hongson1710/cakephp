<?php

//namespace BaseController;

class BaseController extends \AppController
{
    public $components = array(
            'Flash',
            'Auth' => array(
                    'loginRedirect' => array(
                            'controller' => 'posts',
                            'action' => 'index'
                    ),
                    'logoutRedirect' => array(
                            'controller' => 'pages',
                            'action' => 'display',
                            'home'
                    ),
                    'authenticate' => array(
                            'Form' => array(
                                    'passwordHasher' => 'Blowfish'
                            )
                    )
            )
    );
}
