<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends MY_Model
{
    // protected $perPage = 5;
    protected $table = 'products';

    public function getDefaultValues()
    {
        return [
            'id_category' => '',
            'slug' => '',
            'title' => '',
            'description' => '',
            'price' => '',
            'is_available' => 1,
            'image' => ''
        ];
    }

    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'id_category',
                'label' => 'Kategori',
                'rules' => 'required'
            ],
            [
                'field' => 'slug',
                'label' => 'Slug',
                'rules' => 'trim|required|callback_unique_slug'
            ],
            [
                'field' => 'title',
                'label' => 'Nama Produk',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'description',
                'label' => 'Deskripsi',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'price',
                'label' => 'Harga',
                'rules' => 'trim|required|numeric'
            ],
            [
                'field' => 'is_available',
                'label' => 'Ketersediaan',
                'rules' => 'required'
            ],
        ];

        return $validationRules;
    }

    public function uploadImage($fieldName, $fileName)
    {
        $config = [
            'upload_path' => './images/products/',
            'file_name' => $fileName,
            'allowed_types' => 'jpg|png|jpeg|JPG|PNG|JPEG',
            'max_size' => 1024,
            'max_width' => 0,
            'max_height' => 0,
            'overwrite' => true,
            'file_ext_tolower' => true,
        ];

        $this->load->library('upload', $config);

        if ($this->upload->do_upload($fieldName)) {
            $uploadData = $this->upload->data();

            // Kompres gambar menggunakan image_lib
            $this->load->library('image_lib');
            $configResize = [
                'image_library'   => 'gd2',
                'source_image'    => './images/products/' . $uploadData['file_name'],
                'create_thumb'    => FALSE,
                'maintain_ratio'  => TRUE,
                'quality'         => '70%',
                'width'           => 500,
                'height'          => 500,
            ];
            $this->image_lib->initialize($configResize);
            $this->image_lib->resize();
            $this->image_lib->clear();

            return $uploadData;
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors('', '<br>'));
            return false;
        }
    }
}
