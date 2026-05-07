<?php

defined('BASEPATH') or exit('No direct scripts access allowed');

/**
 * @property Category_model $category
 */
class Category extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($page = null)
    {
        $data['title'] = 'Admin: Category';
        $data['content'] = $this->category->paginate($page)->get();
        $data['total_rows'] = $this->category->count();
        $data['pagination'] = $this->category->makePagination(base_url('category'), 2, $data['total_rows']);
        $data['page'] = 'pages/category/index';

        $this->view($data);
    }
}
