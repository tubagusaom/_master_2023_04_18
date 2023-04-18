<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Narasumber_diklat extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}
    
    function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load->library('grid');
            $grid = $this->grid->set_properties(array('model' => 'narasumber_diklat_model', 'controller' => 'narasumber_diklat', 'options' => array('id' => 'narasumber_diklat','pagination','rows_number')))->load_model()->set_grid();
            $view = $this->load->view('narasumber_diklat/index', array('grid' => $grid), true);
            echo json_encode(array('msgType' => 'success', 'msgValue' => $view));
        } else {
            block_access_method();
        }
    }

    function datagrid() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $row = intval($this->input->post('rows')) == 0 ? 20 : intval($this->input->post('rows'));
            $page = intval($this->input->post('page')) == 0 ? 1 : intval($this->input->post('page'));
            $offset = $row * ($page - 1);
            $data = array();
            $params = array('_return' => 'data');
            if (isset($where))
                $params['_where'] = $where;
            $url = "https://www.jimlyschool.com/jadwal-kegiatan/api_narasumber/10/".$offset;
            $conn = json_decode(file_get_contents($url));

            $url_count = "https://www.jimlyschool.com/jadwal-kegiatan/api_narasumber/1000/0";
            $conn_count = json_decode(file_get_contents($url_count));
            
            $rowx = array();
            $rowxs = array();
            foreach ($conn as $key => $value) {
                $rowx[] = array('id'=>$value->id,'dk_name_full'=>$value->dk_name_full);
            }
            $rowxs[] = $rowx;
            //var_dump((object)$conn);

            $data['total'] = count($conn_count);
            //$rows = (object)$conn;
            $data['rows'] = $rowx;
            echo json_encode($data);
        }
        else {
            block_access_method();
        }
    }
    
    function edit($id = false) {
        if (!$id) {
            data_not_found();
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->narasumber_diklat_model->set_validation()->validate();
            if ($data !== false) {
                if ($this->narasumber_diklat_model->check_unique($data, intval($id))) {
                    if ($this->narasumber_diklat_model->update(intval($id), $data) !== false) {
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->narasumber_diklat_model->get_validation())));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
            }
        } else {
            $con_method = $this->narasumber_diklat_model->get(intval($id));
            if (sizeof($con_method) == 1) {
                $this->load->model('Artikel_Kategori_Model');
                $kategori = $this->Artikel_Kategori_Model->dropdown('id', 'kategori');

                $data = $this->narasumber_diklat_model->get_single($con_method);
                $view = $this->load->view('narasumber_diklat/edit', array('kategori' => $kategori, 'data' => $data,'url' => base_url() . 'narasumber_diklat/edit_upload/' . $id), TRUE);
                echo json_encode(array('msgType' => 'success', 'msgValue' => $view));
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat ditemukan !'));
            }
        }
    }
    
    
}