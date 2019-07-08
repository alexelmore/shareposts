<?php

class Pages extends Controller
{
    public function __construct()
    { }

    public function index()
    {
        if (isLoggedIn()) {
            redirect('posts');
        }

        $data = [
            'title' => 'Shareposts',
            'description' => 'Simple social network built on the ElmoreMvc PHP framework'

        ];

        $this->view('pages/index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'about',
            'description' => 'An application to shareposts with other users'
        ];
        $this->view('pages/about', $data);
    }
}
