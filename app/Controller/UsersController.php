<?php

//App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class UsersController extends AppController
{

    function beforeFilter()
    {
        $this->Auth->allow('login', 'logout');

        parent::beforeFilter();
    }

    public function beforeRender()
    {
        parent::beforeRender();

        $this->layout = 'Layouts';
    }

    public $components = array('Flash');

    public $helpers = ['Html', 'Form'];

    public function index()
    {
        $this->set('title_for_layout', 'Login');
    }

    public function login(){
        if ($this->request->is('get')) {
            $this->Auth->logout();
            $this->Session->destroy();

        }else if ($this->request->is('post')) {
            try {
                if ($this->Auth->login()) {
                    return $this->redirect('/posts/index');
                }
                $this->Flash->error(__('Invalid username or password, try again'));
            }catch (Exception $e){
               debug($e);
            }
        }
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }
}
