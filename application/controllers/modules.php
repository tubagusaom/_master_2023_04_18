<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Modules extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('asesi_model');
    }

    function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load->library('grid');
            $grid = $this->grid->set_properties(array('model' => 'asesi_model', 'controller' => 'pra_asesmen', 'options' => array('id' => 'pra_asesmen', 'pagination', 'rows_number')))->load_model()->set_grid();
            $view = $this->load->view('asesi/index', array('grid' => $grid), true);
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
            $data['total'] = isset($where) ? $this->asesi_model->count_by($where) : $this->asesi_model->count_all();
            $this->asesi_model->limit($row, $offset);
            $order = $this->asesi_model->get_params('_order');
            $rows = isset($where) ? $this->asesi_model->order_by($order)->get_many_by($where) : $this->asesi_model->order_by($order)->get_all();
            $data['rows'] = $this->asesi_model->get_selected()->data_formatter($rows);
            echo json_encode($data);
        } else {
            block_access_method();
        }
    }

    function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->asesi_model->set_validation()->validate();
            if ($data !== false) {
                if ($this->asesi_model->check_unique($data)) {
                    if ($this->asesi_model->insert($data) !== false) {
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->asesi_model->get_validation())));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
            }
        } else {
            echo json_encode(array('msgType' => 'success', 'msgValue' => $this->load->view('asesi/add', array('pra_asesmen' => array('-Pilih-', 'Lanjut', 'Tidak Lanjut')), TRUE)));
        }
    }

    function edit($id = false) {
        if (!$id) {
            data_not_found();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->asesi_model->set_validation()->validate();
            if ($data !== false) {
                if ($this->asesi_model->check_unique($data, intval($id))) {
                    if ($this->asesi_model->update(intval($id), $data) !== false) {
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->asesi_model->get_validation())));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
            }
        } else {
            $asesi = $this->asesi_model->get(intval($id));
            if (sizeof($asesi) == 1) {
                $view = $this->load->view('asesi/edit', array('data' => $this->asesi_model->get_single($asesi),'pra_asesmen' => array('-Pilih-', 'Lanjut', 'Tidak Lanjut'),'jenis_kelamin'=>array('Pria','Wanita')), TRUE);
                echo json_encode(array('msgType' => 'success', 'msgValue' => $view));
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat ditemukan !'));
            }
        }
    }

    function delete($id = false) {
        if (!$id) {
            data_not_found();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $roles = $this->asesi_model->get(intval($id));
            if (sizeof($roles) == 1) {
                if ($this->asesi_model->delete(intval($id))) {
                    echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil dihapus'));
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak berhasil dihapus !'));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat ditemukan !'));
            }
        } else {
            block_access_method();
        }
    }
    function combogrid($id = false)
	{
		$this->load->model('v_asesi_model');
		$row = intval($this->input->post('rows')) == 0 ? 20 : intval($this->input->post('rows')) ;
		$page = intval($this->input->post('page'))== 0 ? 1 : intval($this->input->post('page'));
		$offset = $row * ($page - 1);
		$data = array();
		$params = array('_return'=>'data');
		if(isset($_POST['q']))
		{
			$where['asesi_name LIKE'] = "%" . $this->input->post('q') . "%";
		}
		if(isset($where)) $params['_where'] = $where;
		$data['total'] = isset($where) ? $this->v_asesi_model->count_by($where) : $this->v_asesi_model->count_all();
		$this->v_asesi_model->limit($row, $offset);
		$order_criteria = "ASC";
		$_order_escape = TRUE;
		if($id)
		{
			$order = "FIELD(id, " . intval($id) . ")";
			$order_criteria = "DESC";
			$_order_escape = FALSE;
		}
		else
		{
			$order = $this->v_asesi_model->get_params('_order');
		}		
		$rows = isset($where) ? $this->v_asesi_model->order_by($order, $order_criteria, $_order_escape)->get_many_by($where) : $this->v_asesi_model->order_by($order, $order_criteria, $_order_escape)->get_all();
		$data['rows'] = $this->v_asesi_model->get_selected()->data_formatter($rows);
        //var_dump($data);
		echo json_encode($data);
	}
}
