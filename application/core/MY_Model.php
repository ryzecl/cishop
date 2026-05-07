<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $table = '';
    protected $perPage = 5;

    public function __construct()
    {
        parent::__construct();

        if (!$this->table) {
            $this->table = strtolower(
                str_replace('_model', '', get_class($this))
            );
        }
    }

    /**
     * Fungsi Validasi Input
     * Rules: Dideklarasikan dalam masing-masing model
     * 
     * @return void
     */
    public function validate()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters(
            '<small class="form-text text-danger">',
            '</small>'
        );
        $validationRules = $this->getValidationRules();

        $this->form_validation->set_rules($validationRules);

        return $this->form_validation->run();
    }

    /**
     * Memilih kolom yang ingin ditampilkan
     * 
     * @param string|array $columns
     * @return $this
     */
    public function select($columns)
    {
        $this->db->select($columns);
        return $this;
    }

    /**
     * Memberikan kondisi pencarian (WHERE)
     * 
     * @param string $column
     * @param mixed $condition
     * @return $this
     */
    public function where($column, $condition)
    {
        $this->db->where($column, $condition);
        return $this;
    }

    /**
     * Memberikan kondisi pencarian kemiripan (LIKE)
     * 
     * @param string $column
     * @param string $condition
     * @return $this
     */
    public function like($column, $condition)
    {
        $this->db->like($column, $condition);
        return $this;
    }

    /**
     * Memberikan kondisi pencarian kemiripan alternatif (OR LIKE)
     * 
     * @param string $column
     * @param string $condition
     * @return $this
     */
    public function orLike($column, $condition)
    {
        $this->db->or_like($column, $condition);
        return $this;
    }

    /**
     * Menggabungkan tabel (JOIN)
     * Asumsi konvensi: produk.id_kategori = kategori.id
     * 
     * @param string $table Tabel yang ingin digabung
     * @param string $type Tipe join (left, right, inner, dll)
     * @return $this
     */
    public function join($table, $type = 'left')
    {
        $this->db->join($table, "$this->table.id_$table = $table.id", $type);
        return $this;
    }

    /**
     * Mengurutkan data
     * 
     * @param string $column
     * @param string $order (asc|desc)
     * @return $this
     */
    public function orderBy($column, $order = 'asc')
    {
        $this->db->order_by($column, $order);
        return $this;
    }

    /**
     * Mengambil satu baris data saja (Terminator)
     * 
     * @return object|null
     */
    public function first()
    {
        return $this->db->get($this->table)->row();
    }

    /**
     * Mengambil semua baris data (Terminator)
     * 
     * @return array
     */
    public function get()
    {
        return $this->db->get($this->table)->result();
    }

    /**
     * Menghitung total jumlah data (Terminator)
     * 
     * @return int
     */
    public function count()
    {
        return $this->db->count_all_results($this->table);
    }

    /**
     * Menyimpan data baru (Insert)
     * 
     * @param array $data
     * @return int ID dari data yang baru disimpan
     */
    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Mengubah data yang ada (Update)
     * PENTING: Pastikan sudah memanggil where() sebelumnya!
     * 
     * @param array $data
     * @return bool
     */
    public function update($data)
    {
        return $this->db->update($this->table, $data);
    }

    /**
     * Menghapus data (Delete)
     * PENTING: Pastikan sudah memanggil where() sebelumnya!
     * 
     * @return int Jumlah baris yang terhapus
     */
    public function delete()
    {
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    public function paginate($page)
    {
        $this->db->limit(
            $this->perPage,
            $this->calculateRealOffset($page),
        );

        return $this;
    }

    public function calculateRealOffset($page)
    {
        if (is_null($page) || empty($page)) {
            $offset = 0;
        } else {
            $offset = ($page * $this->perPage) - $this->perPage;
        }

        return $offset;
    }

    public function makePagination($baseUrl, $uriSegment, $totalRows = null)
    {
        $this->load->library('pagination');

        $config = [
            'base_url' => $baseUrl,
            'uri_segment' => $uriSegment,
            'per_page' => $this->perPage,
            'total_rows' => $totalRows,
            'use_page_numbers' => true,
            'cur_page' => $this->uri->segment($uriSegment) ?: 1,

            'full_tag_open' => '<ul class="pagination">',
            'full_tag_close' => '</ul>',
            'attributes' => ['class' => 'page-link'],
            'first_link' => false,
            'last_link' => false,
            'first_tag_open' => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'prev_link' => '&laquo',
            'prev_tag_open' => '<li class="page-item">',
            'prev_tag_close' => '</li>',
            'next_link' => '&raquo',
            'next_tag_open' => '<li class="page-item">',
            'next_tag_close' => '</li>',
            'last_tag_open' => '<li class="page-item">',
            'last_tag_close' => '</li>',
            'cur_tag_open' => '<li class="page-item active"><a href="#" class="page-link">',
            'cur_tag_close' => '<span class="visually-hidden">(current)</span></a></li>',
            'num_tag_open' => '<li class="page-item">',
            'num_tag_close' => '</li>'
        ];

        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}
