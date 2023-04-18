<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Skema_syarat extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('skema_syarat_model');
    }

    function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load->library('grid');
            $grid = $this->grid->set_properties(array('model' => 'skema_syarat_model', 'controller' => 'skema_syarat', 'options' => array('id' => 'skema_syarat', 'pagination', 'rownumber')))->load_model()->set_grid();
          $view = $this->load->view('skema_syarat/index', array('grid' => $grid), true);
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
            $data['total'] = isset($where) ? $this->skema_syarat_model->count_by($where) : $this->skema_syarat_model->count_all();
            $this->skema_syarat_model->limit($row, $offset);
            $order = $this->skema_syarat_model->get_params('_order');
/*            $rows = isset($where) ? $this->skema_syarat_model->order_by($order)->get_many_by($where) : $this->skema_syarat_model->order_by($order)->get_all();*/
            $rows = $this->skema_syarat_model->set_params($params)->with(array('skema', 'id_skema'));
            $data['rows'] = $this->skema_syarat_model->get_selected()->data_formatter($rows);
            echo json_encode($data);
        } else {
            block_access_method();
        }
    }

    function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->skema_syarat_model->set_validation()->validate();
            if ($data !== false) {
                if ($this->skema_syarat_model->check_unique($data)) {
                    if ($this->skema_syarat_model->insert($data) !== false) {
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->skema_syarat_model->get_validation())));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
            }
        } else {
        	$this->load->model('skema_model');
        	$skema = $this->skema_model->dropdown('id','skema');
            $view = $this->load->view('skema_syarat/add', array('skema' => $skema, 'jenis_bukti' => array('1' => 'Pendidikan Formal', '2' => 'Sertifikasi', '3' => 'Pelatihan', '4' => 'Penghargaan', '5' => 'Hasil Kerja / Portofolio', '6' => 'LAIN_LAIN'), 'status' => array('-Pilih-','Wajib','Tambahan')), TRUE);
            echo json_encode(array('msgType' => 'success', 'msgValue' => $view));
        }
    }

    function edit($id = false) {
        if (!$id) {
            data_not_found();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->skema_syarat_model->set_validation()->validate();
            if ($data !== false) {
                if ($this->skema_syarat_model->check_unique($data, intval($id))) {
                    if ($this->skema_syarat_model->update(intval($id), $data) !== false) {
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->skema_syarat_model->get_validation())));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
            }
        } else {
            $batch = $this->skema_syarat_model->get(intval($id));
            if (sizeof($batch) == 1) {
				$this->load->model('skema_model');
				$skema = $this->skema_model->dropdown('id','skema');
				$view = $this->load->view('skema_syarat/edit', array('data' => $this->skema_syarat_model->get_single($batch),'skema' => $skema, 'jenis_bukti' => array('1' => 'Pendidikan Formal', '2' => 'Sertifikasi', '3' => 'Pelatihan', '4' => 'Penghargaan', '5' => 'Hasil Kerja / Portofolio', '6' => 'LAIN_LAIN'), 'status' => array('-Pilih-','Wajib','Tambahan')), TRUE);
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
            $roles = $this->skema_syarat_model->get(intval($id));
            if (sizeof($roles) == 1) {
                if ($this->skema_syarat_model->delete(intval($id))) {
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

}