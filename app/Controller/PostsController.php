<?php

//namespace PostsController;
App::uses('BaseController', 'Controller');

class PostsController extends AppController
{

    public $helpers = array('Html', 'Form');
//    public $components = array('Flash');


    public function beforeRender() {
        parent::beforeRender();

        $this->layout = 'Layouts';
    }

    public function index() {
        $title_for_layout = 'Danh sach bai viet';
        $posts = $this->Post->find('all');
        $this->set(
                compact('posts', 'title_for_layout')
        );
    }

    public function view($id) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
//        print_r('<pre>');
//        print_r($this);
//        print_r('<pre/>');
//        die;
//        $this->Flash->success(__('Unable to add your post.'));
        $this->set('post', $post);
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Post->create();
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to add your post.'));
        }
    }
}
