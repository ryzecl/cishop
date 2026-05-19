<?php

defined('BASEPATH') or exit('No direct scripts access allowed');

class Product extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($page = null)
    {
        $data['title'] = 'Admin: Product';
        $data['content'] = $this->product->select(
            ['products.id', 'products.title as product_title', 'products.description', 'products.price', 'products.is_available', 'products.image', 'categories.title as category_title']
        )
        ->join('categories')
        ->orderBy('products.id', 'desc')
        ->paginate($page)->get();
        $data['total_rows'] = $this->product->count();
        $data['pagination'] = $this->product->makePagination(base_url('product'), 2, $data['total_rows']);
        $data['page'] = 'pages/product/index';

        $this->view($data);
    }
}