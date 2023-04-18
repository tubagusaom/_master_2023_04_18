<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penilaian_asesor extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('penilaian_asesor_model');
        $this->load->model('real_asesmen_model');
    }

    function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load->library('grid');
            $grid = $this->grid->set_properties(array('model' => 'penilaian_asesor_model', 'controller' => 'penilaian_asesor', 'options' => array('id' => 'penilaian_asesor', 'pagination', 'rows_number')))->load_model()->set_grid();
            $view = $this->load->view('penilaian_asesor/index', array('grid' => $grid), true);
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
            $jenis_user = $this->auth->get_user_data()->jenis_user;
            if ($jenis_user == 3) {
                $asesi_id = $this->auth->get_user_data()->pegawai_id;
                $where[kode_lsp() . 'asesi.id_tuk ='] = $asesi_id;
                //$where['administrasi_ujk ='] = '1';
            } else if ($jenis_user == 2) {
                $asesi_id = $this->auth->get_user_data()->pegawai_id;
                $where['id_asesor ='] = $asesi_id;
            } else if ($jenis_user == 1) {
                $asesi_id = $this->auth->get_user_data()->id;
                $where[kode_lsp() . 'asesi.id_users ='] = $asesi_id;
            }
            $where['pra_asesmen ='] = '1';
            if(isset($_POST['nama_lengkap']) && !empty($_POST['nama_lengkap']))
            {
                $where['nama_lengkap like'] = '%' . $this->input->post('nama_lengkap') . '%';
            }
            if (isset($where))
                $params['_where'] = $where;
            $data['total'] = isset($where) ? $this->penilaian_asesor_model->count_by($where) : $this->penilaian_asesor_model->count_all();
            $this->penilaian_asesor_model->limit($row, $offset);
            $order = $this->penilaian_asesor_model->get_params('_order');
            $rows = $this->penilaian_asesor_model->set_params($params)->with(array('skema', 'user', 'jadwal_asesmen', 'tuk', 'asesor'));
            $data['rows'] = $this->penilaian_asesor_model->get_selected()->data_formatter($rows);
            echo json_encode($data);
        } else {
            block_access_method();
        }
    }

    function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->penilaian_asesor_model->set_validation()->validate();
            if ($data !== false) {
                if ($this->penilaian_asesor_model->check_unique($data)) {
                    if ($this->penilaian_asesor_model->insert($data) !== false) {
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->penilaian_asesor_model->get_validation())));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
            }
        } else {
            echo json_encode(array('msgType' => 'success', 'msgValue' => $this->load->view('asesi/add', array('penilaian_asesor' => array('-Pilih-', 'Lanjut', 'Tidak Lanjut')), TRUE)));
        }
    }

//    function edit($id = false) {
//        if (!$id) {
//            data_not_found();
//            exit;
//        }
//
//        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//            $data = $this->penilaian_asesor_model->set_validation()->validate();
//            if ($data !== false) {
//                if ($this->penilaian_asesor_model->check_unique($data, intval($id))) {
//                    $data['mak01'] = serialize($this->input->post('mak01'));
//                    $data['mak02'] = serialize($this->input->post('mak02'));
//                    $data['mak02a'] = serialize($this->input->post('mak02a'));
//                    $data['mak03'] = serialize($this->input->post('mak03'));
//                    $data['mak04'] = serialize($this->input->post('mak04'));
//                    $data['mak04a'] = serialize($this->input->post('mak04a'));
//                    $data['mak05'] = serialize($this->input->post('mak05'));
//                    $data['mak05a'] = serialize($this->input->post('mak05a'));
//                    $data['mak06'] = serialize($this->input->post('mak06'));
//                    $data['mak06a'] = serialize($this->input->post('mak06a'));
//                    $data['mak06b'] = serialize($this->input->post('mak06b'));
//                    $data['mak07'] = serialize($this->input->post('mak07'));
//                    $data['penilaian_perangkat'] = serialize($this->input->post('penilaian_perangkat'));
//
//                    if ($this->penilaian_asesor_model->update(intval($id), $data) !== false) {
//                        $this->db->where('asesi_id', $id);
//                        $asesi = $this->db->get(kode_lsp() . 'asesi_detail')->result_array();
//                        $v = $this->input->post('v');
//                        $a = $this->input->post('a');
//                        $t = $this->input->post('t');
//                        $m = $this->input->post('m');
//
//                        foreach ($asesi as $key => $value) {
//                            if (isset($v[$value['id']])) {
//                                $v_value = '1';
//                            } else {
//                                $v_value = '0';
//                            }
//                            if (isset($a[$value['id']])) {
//                                $a_value = '1';
//                            } else {
//                                $a_value = '0';
//                            }
//                            if (isset($t[$value['id']])) {
//                                $t_value = '1';
//                            } else {
//                                $t_value = '0';
//                            }
//                            if (isset($m[$value['id']])) {
//                                $m_value = '1';
//                            } else {
//                                $m_value = '0';
//                            }
//                            $data_update = array(
//                                'v' => $v_value,
//                                'a' => $a_value,
//                                't' => $t_value,
//                                'm' => $m_value,
//                            );
//
//                            $this->db->where('id', $value['id']);
//                            $this->db->update(kode_lsp() . 'asesi_detail', $data_update);
//                        }
//                        $sms = isset($_POST['notifikasi']) ? $this->input->post('notifikasi') : "";
//                        if ($sms != "") {
//                            $rekomendasi_description = $this->input->post('rekomendasi_description');
//                            $telp = $this->input->post('telp');
//                            $id_users = $this->input->post('id_users');
//                            $rekomendasi_asesor = $this->input->post('rekomendasi_asesor');
//
//                            $this->sms($rekomendasi_description, $telp, $id_users, $rekomendasi_asesor);
//                        }
//                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
//                    } else {
//                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
//                    }
//                } else {
//                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->penilaian_asesor_model->get_validation())));
//                }
//            } else {
//                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
//            }
//        } else {
//            $asesi = $this->penilaian_asesor_model->get(intval($id));
//            if (sizeof($asesi) == 1) {
//                $data_aplikasi = $this->db->get('r_konfigurasi_aplikasi')->row();
//                $this->load->model('asesi_model');
//                $this->load->library('combogrid');
//                $jadwal_grid = $this->combogrid->set_properties(array('model' => 'jadwal_asesmen_model', 'controller' => 'jadwal_asesmen', 'fields' => array('jadual', 'tanggal'), 'options' => array('id' => 'jadwal_id', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'jadual', 'panelWidth' => 500)))->load_model()->set_grid();
//                $asesor_grid = $this->combogrid->set_properties(array('model' => 'asesor_model', 'controller' => 'asesor', 'fields' => array('users', 'no_reg'), 'options' => array('id' => 'id_asesor', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'users', 'panelWidth' => 500)))->load_model()->set_grid();
//                $tuk_grid = $this->combogrid->set_properties(array('model' => 'tuk_model', 'controller' => 'tuk', 'fields' => array('tuk', 'alamat'), 'options' => array('id' => 'id_tuk', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'tuk', 'panelWidth' => 500)))->load_model()->set_grid();
//                $this->db->where('asesi_id', $id);
//                $detail_asesi = $this->db->get(kode_lsp() . 'asesi_detail')->result_array();
//                foreach ($detail_asesi as $key => $value) {
//                    $jenis_bukti[] = $value['jenis_bukti'];
//                }
//                $jenis_bukti = implode(',', array_unique($jenis_bukti));
//
//                $unit_kompetensi = $this->asesi_model->data_unit_kompetensi($asesi->skema_sertifikasi);
//                //unserialize data mak
//                $mak01 = @unserialize($asesi->mak01);
//                $mak02 = @unserialize($asesi->mak02);
//                $mak02a = @unserialize($asesi->mak02a);
//                $mak03 = @unserialize($asesi->mak03);
//                $mak04 = @unserialize($asesi->mak04);
//                $mak04a = @unserialize($asesi->mak04a);
//                $mak05 = @unserialize($asesi->mak05);
//                $mak05a = @unserialize($asesi->mak05a);
//                $mak06 = @unserialize($asesi->mak06);
//                $mak06a = @unserialize($asesi->mak06a);
//                $mak06b = @unserialize($asesi->mak06b);
//                $mak07 = @unserialize($asesi->mak07);
//                $perangkat = @unserialize($asesi->perangkat);
//                if (!empty($perangkat)) {
//                    $this->db->where_in('id', $perangkat);
//                    $array_perangkat = array('Pertanyaan Lisan', 'Pertanyaan Tulisan', 'Observasi / Praktek', 'Wawancara', 'Cek Portofolio');
//                } else {
//                    $array_perangkat = array('Pertanyaan Lisan', 'Pertanyaan Tulisan', 'Observasi / Praktek', 'Wawancara', 'Cek Portofolio');
//                }
//                $penilaian_perangkat = @unserialize($asesi->penilaian_perangkat);
//
//
//                $view = $this->load->view('penilaian_asesor/edit', array('data_aplikasi' => $data_aplikasi, 'detail_asesi' => $detail_asesi, 'data' => $this->penilaian_asesor_model->get_single($asesi)
//                    , 'array_perangkat' => $array_perangkat
//                    , 'perangkat' => $perangkat
//                    , 'penilaian_perangkat' => $penilaian_perangkat
//                    , 'jadwal_grid' => $jadwal_grid
//                    , 'asesor_grid' => $asesor_grid
//                    , 'tuk_grid' => $tuk_grid
//                    , 'mak01' => $mak01
//                    , 'mak02' => $mak02
//                    , 'mak02a' => $mak02a
//                    , 'mak03' => $mak03
//                    , 'mak04' => $mak04
//                    , 'mak04a' => $mak04a
//                    , 'mak05' => $mak05
//                    , 'mak05a' => $mak05a
//                    , 'mak06' => $mak06
//                    , 'mak06a' => $mak06a
//                    , 'mak06b' => $mak06b
//                    , 'mak07' => $mak07
//                    , 'jenis_bukti' => $jenis_bukti
//                    , 'unit_kompetensi' => $unit_kompetensi
//                    , 'rekomendasi_asesor' => array('-Pilih-', 'Kompeten', 'Belum Kompeten', 'Asesmen Lanjut')
//                        ), TRUE);
//                echo json_encode(array('msgType' => 'success', 'msgValue' => $view));
//            } else {
//                echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat ditemukan !'));
//            }
//        }
//    }


    function edit($id = false) {
        if (!$id) {
            data_not_found();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->penilaian_asesor_model->set_validation()->validate();
            if ($data !== false) {
                if ($this->penilaian_asesor_model->check_unique($data, intval($id))) {
                    $data['mak01'] = serialize($this->input->post('mak01'));
                    $data['mak02'] = serialize($this->input->post('mak02'));
                    $data['mak02a'] = serialize($this->input->post('mak02a'));
                    $data['mak03'] = serialize($this->input->post('mak03'));
                    $data['mak04'] = serialize($this->input->post('mak04'));
                    $data['mak04a'] = serialize($this->input->post('mak04a'));
                    $data['mak05'] = serialize($this->input->post('mak05'));
                    $data['mak05a'] = serialize($this->input->post('mak05a'));
                    $data['mak06'] = serialize($this->input->post('mak06'));
                    $data['mak06a'] = serialize($this->input->post('mak06a'));
                    $data['mak06b'] = serialize($this->input->post('mak06b'));
                    $data['mak07'] = serialize($this->input->post('mak07'));
                    $data['penilaian_perangkat'] = serialize($this->input->post('penilaian_perangkat'));
                    $data['pencapaian_mak02'] = serialize($this->input->post('pencapaian_mak02'));
                    $data['keputusan_mak02'] = serialize($this->input->post('keputusan_mak02'));
                    if ($this->penilaian_asesor_model->update(intval($id), $data) !== false) {
                        $this->db->where('asesi_id', $id);
                        $asesi = $this->db->get(kode_lsp() . 'asesi_detail')->result_array();
                        $v = $this->input->post('v');
                        $a = $this->input->post('a');
                        $t = $this->input->post('t');
                        $m = $this->input->post('m');

                        foreach ($asesi as $key => $value) {
                            if (isset($v[$value['id']])) {
                                $v_value = '1';
                            } else {
                                $v_value = '0';
                            }
                            if (isset($a[$value['id']])) {
                                $a_value = '1';
                            } else {
                                $a_value = '0';
                            }
                            if (isset($t[$value['id']])) {
                                $t_value = '1';
                            } else {
                                $t_value = '0';
                            }
                            if (isset($m[$value['id']])) {
                                $m_value = '1';
                            } else {
                                $m_value = '0';
                            }
                            $data_update = array(
                                'v' => $v_value,
                                'a' => $a_value,
                                't' => $t_value,
                                'm' => $m_value,
                            );

                            $this->db->where('id', $value['id']);
                            $this->db->update(kode_lsp() . 'asesi_detail', $data_update);
                        }
                        $sms = isset($_POST['notifikasi']) ? $this->input->post('notifikasi') : "";
                        if ($sms != "") {
                            $rekomendasi_description = $this->input->post('rekomendasi_description');
                            $telp = $this->input->post('telp');
                            $id_users = $this->input->post('id_users');
                            $rekomendasi_asesor = $this->input->post('rekomendasi_asesor');

                            $this->sms($rekomendasi_description, $telp, $id_users, $rekomendasi_asesor);
                        }
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->penilaian_asesor_model->get_validation())));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
            }
        } else {
            $asesi = $this->penilaian_asesor_model->get(intval($id));
            if (sizeof($asesi) == 1) {
                $data_aplikasi = $this->db->get('r_konfigurasi_aplikasi')->row();
                $this->load->model('asesi_model');
                $this->load->library('combogrid');
                $bukti_array = json_decode(str_replace('|', '"', $asesi->bukti_pendukung));
                foreach ($bukti_array as $value) {
                    $array_hasil_bukti[] = $value[0];
                }
                //var_dump($array_hasil_bukti);die();
                $jenis_bukti = implode('<br/>', $array_hasil_bukti);
                $jadwal_grid = $this->combogrid->set_properties(array('model' => 'jadwal_asesmen_model', 'controller' => 'jadwal_asesmen', 'fields' => array('jadual', 'tanggal'), 'options' => array('id' => 'jadwal_id', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'jadual', 'panelWidth' => 500)))->load_model()->set_grid();
                $asesor_grid = $this->combogrid->set_properties(array('model' => 'asesor_model', 'controller' => 'asesor', 'fields' => array('users', 'no_reg'), 'options' => array('id' => 'id_asesor', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'users', 'panelWidth' => 500)))->load_model()->set_grid();
                $tuk_grid = $this->combogrid->set_properties(array('model' => 'tuk_model', 'controller' => 'tuk', 'fields' => array('tuk', 'alamat'), 'options' => array('id' => 'id_tuk', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'tuk', 'panelWidth' => 500)))->load_model()->set_grid();
                $this->db->where('asesi_id', $id);
                $detail_asesi = $this->db->get(kode_lsp() . 'asesi_detail')->result_array();
//                foreach($detail_asesi as $key=>$value){
//                    $jenis_bukti[]=$value['jenis_bukti'];
//                }
                //$jenis_bukti = @implode(',',array_unique($jenis_bukti));


                $unit_kompetensi = $this->asesi_model->data_unit_kompetensi($asesi->skema_sertifikasi);
                // var_dump($unit_kompetensi); die();
                //unserialize data mak
                $mak01 = @unserialize($asesi->mak01);
                $mak02 = @unserialize($asesi->mak02);
                $mak02a = @unserialize($asesi->mak02a);
                $mak03 = @unserialize($asesi->mak03);
                $mak04 = @unserialize($asesi->mak04);
                $mak04a = @unserialize($asesi->mak04a);
                $mak05 = @unserialize($asesi->mak05);
                $mak05a = @unserialize($asesi->mak05a);
                $mak06 = @unserialize($asesi->mak06);
                $mak06a = @unserialize($asesi->mak06a);
                $mak06b = @unserialize($asesi->mak06b);
                $mak07 = @unserialize($asesi->mak07);
                $pencapaian_mak02 = @unserialize($asesi->pencapaian_mak02);
                $keputusan_mak02 = @unserialize($asesi->keputusan_mak02);

                $perangkat = @unserialize($asesi->perangkat_yang_digunakan);

                $this->db->select('a.*,b.perangkat_detail');
                $this->db->from('t_uji a');
                $this->db->join(kode_lsp() . 'perangkat_asesmen_detail b', 'a.id_perangkat_detail=b.id');
                $this->db->where('a.id_asesi', $id);
                $datauji = $this->db->get()->result();

                $lengkap_asesi = $this->asesi_model->asesi_lengkap($id);

                if (!empty($perangkat)) {
                    $this->db->where_in('id', $perangkat);
                    $array_perangkat = array('Pertanyaan Lisan', 'Pertanyaan Tulisan', 'Observasi / Praktek', 'Wawancara', 'Cek Portofolio');
                } else {
                    $array_perangkat = array('Pertanyaan Lisan', 'Pertanyaan Tulisan', 'Observasi / Praktek', 'Wawancara', 'Cek Portofolio');
                }
                $penilaian_perangkat = @unserialize($asesi->penilaian_perangkat);
                $array_kesenjangan_kuk = explode('#', $asesi->kesenjangan_kuk);
                $table_pilihan = '';
                if ($asesi->saran_tindak_lanjut != "y") {

                    $unit_kom = "";
                    $table_pilihan = '<table border="1" style="width:95%;" cellpadding="2" cellsapcing="2"><tr><td><b>Unit Kompetensi</b></td><td><b>Aspek Kritis</b></td></tr>';
                    foreach ($array_kesenjangan_kuk as $key => $value) {
                        $result = "";
                        $result = explode('|', $value);
                        // var_dump($result);
                        if ($unit_kom != $result[0]) {
                            $table_pilihan .= '<tr><td><b>' . $result[0] . '</b></td><td>' . $result[2] . '</td></tr><tr><td colspan="2">' . $result[1] . '</td></tr>';
                        } else {
                            $table_pilihan .= '<tr><td colspan="2">' . $result[1] . '</td></tr>';
                        }
                        $unit_kom = $result[0];
                    };
                    $table_pilihan .= '</table>';
                } else {
                    $table_pilihan = '';
                }
                $array_asesmen_lanjut = explode('#', $asesi->asesmen_lanjut);
                // var_dump($array_asesmen_lanjut);die();
                if ($asesi->pelihara_kompetensi != "y") {
                    $unit_kom = "";
                    $table_unit = '<table border="1" style="width:95%;" cellpadding="2" cellsapcing="2"><tr><td><b>Kode Unit Kompetensi</b></td><td><b>Unit Kompetensi</b></td></tr>';
                    foreach ($array_asesmen_lanjut as $key => $value) {
                        $result = "";
                        $result = explode('|', $value);
                        $table_unit .= '<tr><td>' . $result[0] . '</td><td>' . $result[1] . '</td></tr>';
                        $unit_kom = $result[0];
                    };
                    $table_unit .= '</table>';
                } else {
                    $table_unit = "";
                }
                // var_dump($asesi->perangkat_yang_digunakan);die();
                $view = $this->load->view('penilaian_asesor/edit', array('data_aplikasi' => $data_aplikasi, 'detail_asesi' => $detail_asesi, 'data' => $lengkap_asesi
                    , 'array_perangkat' => $array_perangkat
                    , 'perangkat' => $perangkat
                    , 'penilaian_perangkat' => $penilaian_perangkat
                    , 'jadwal_grid' => $jadwal_grid
                    , 'asesor_grid' => $asesor_grid
                    , 'tuk_grid' => $tuk_grid
                    , 'mak01' => $mak01
                    , 'mak02' => $mak02
                    , 'mak02a' => $mak02a
                    , 'mak03' => $mak03
                    , 'mak04' => $mak04
                    , 'mak04a' => $mak04a
                    , 'mak05' => $mak05
                    , 'mak05a' => $mak05a
                    , 'mak06' => $mak06
                    , 'mak06a' => $mak06a
                    , 'mak06b' => $mak06b
                    , 'mak07' => $mak07
                    , 'pencapaian_mak02' => $pencapaian_mak02
                    , 'keputusan_mak02' => $keputusan_mak02
                    , 'jenis_bukti' => $jenis_bukti
                    , 'unit_kompetensi' => $unit_kompetensi
                    , 'datauji' => $datauji
                    , 'table_pilihan' => $table_pilihan
                    , 'table_asesmen_ulang' => $table_unit
                    , 'rekomendasi_asesor' => array('-Pilih-', 'Kompeten', 'Belum Kompeten', 'Asesmen Lanjut')
                        ), TRUE);
                echo json_encode(array('msgType' => 'success', 'msgValue' => $view));
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat ditemukan !'));
            }
        }
    }
    function form_ak($id = false) {
        if (!$id) {
            data_not_found();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->penilaian_asesor_model->set_validation()->validate();
            if ($data !== false) {
                if ($this->penilaian_asesor_model->check_unique($data, intval($id))) {

                  $data['mak01'] = serialize($this->input->post('mak01'));
                  $data['mak02'] = serialize($this->input->post('mak02'));
                  $data['mak02a'] = serialize($this->input->post('mak02a'));
                  $data['mak03'] = serialize($this->input->post('mak03'));
                  $data['mak05'] = serialize($this->input->post('mak05'));
                  $data['mak05a'] = serialize($this->input->post('mak05a'));
                  $data['mak06'] = serialize($this->input->post('mak06'));
                  $data['mak06a'] = serialize($this->input->post('mak06a'));
                  $data['mak06b'] = serialize($this->input->post('mak06b'));
                  $data['mak07'] = serialize($this->input->post('mak07'));

                  $data['frak02'] = serialize($this->input->post('frak02'));

                  // var_dump($data);die();

                    if ($this->penilaian_asesor_model->update(intval($id), $data) !== false) {
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->penilaian_asesor_model->get_validation())));
                }
            }
        } else {
            $asesi = $this->penilaian_asesor_model->get(intval($id));
            if (sizeof($asesi) == 1) {

              $data = $this->penilaian_asesor_model->get_single($asesi);

                $this->load->model('asesi_model');
                $this->load->library('combogrid');

                $jadwal_grid = $this->combogrid->set_properties(array('model' => 'jadwal_asesmen_model', 'controller' => 'jadwal_asesmen', 'fields' => array('jadual', 'tanggal'), 'options' => array('id' => 'jadwal_id', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'jadual', 'panelWidth' => 500)))->load_model()->set_grid();
                $asesor_grid = $this->combogrid->set_properties(array('model' => 'asesor_model', 'controller' => 'asesor', 'fields' => array('users', 'no_reg'), 'options' => array('id' => 'id_asesor', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'users', 'panelWidth' => 500)))->load_model()->set_grid();
                $tuk_grid = $this->combogrid->set_properties(array('model' => 'tuk_model', 'controller' => 'tuk', 'fields' => array('tuk', 'alamat'), 'options' => array('id' => 'id_tuk', 'pagination', 'rownumber', 'idField' => 'id', 'textField' => 'tuk', 'panelWidth' => 500)))->load_model()->set_grid();
                $unit_kompetensi = $this->asesi_model->data_unit_kompetensi($asesi->skema_sertifikasi);

                $mak01 = @unserialize($asesi->mak01);
                $mak03 = @unserialize($asesi->mak03);
                $mak05 = @unserialize($asesi->mak05);
                $mak05a = @unserialize($asesi->mak05a);
                $mak02 = @unserialize($asesi->mak02);
                $mak02a = @unserialize($asesi->mak02a);
                $mak02b = @unserialize($asesi->mak02b);
                $mak06 = @unserialize($asesi->mak06);
                $mak06a = @unserialize($asesi->mak06a);
                $mak06b = @unserialize($asesi->mak06b);
                $mak07 = @unserialize($asesi->mak07);
                $frak02 = @unserialize($asesi->frak02);

                // dump($asesi); die();


                $view = $this->load->view(
                  'rekomendasi_asesor/form_ak',
                    array(
                      'data' => $data,
                      'jadwal_grid' => $jadwal_grid,
                      'asesor_grid' => $asesor_grid,
                      'tuk_grid' => $tuk_grid,
                      'mak01' => $mak01,
                      'mak02' => $mak02,
                      'mak02a' => $mak02a,
                      'mak02b' => $mak02b,
                      'mak03' => $mak03,
                      // 'mak04' => $mak04,
                      // 'mak04a' => $mak04a,
                      'mak05' => $mak05,
                      'mak05a' => $mak05a,
                      'mak06' => $mak06,
                      'mak06a' => $mak06a,
                      'mak06b' => $mak06b,
                      'mak07' => $mak07,
                      'frak02' => $frak02,
                      'unit_kompetensi' => $unit_kompetensi,
                      'rekomendasi_asesor' => array('-Pilih-','Kompeten','Belum Kompeten')
                    ),
                    TRUE
                );
                echo json_encode(array('msgType' => 'success', 'msgValue' => $view));
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat ditemukan !'));
            }
        }
    }
    function cetak_ak($kategori=1,$id,$type = "pdf") {
        // error_reporting(E_ALL);
        $this->load->model('asesi_model');
        $this->load->model('pra_asesmen_model');
        ini_set('memory_limit', '51208M');

        $data['aplikasi'] = $this->db->get('r_konfigurasi_aplikasi')->row();

        $asesi = $this->asesi_model->asesi_lengkap($id);
        $data['data_asesi'] = $asesi;
        $data['asesor_pra_asesmen'] = $this->pra_asesmen_model->asesor_pra_asesmen($asesi->pra_asesmen_checked);
        $asesi_detail = $this->asesi_model->asesi_lengkap($id);
        // dump($asesi->rekomendasi_description);die();
        $unit_kompetensi = $this->asesi_model->data_unit_kompetensi($asesi->skema_sertifikasi);
        $data['unit_kompetensi'] = $unit_kompetensi;

        $this->db->where('id',$asesi->id_asesor);
        $data_asesor = $this->db->get(kode_lsp().'users')->row();
        $data['detail_asesor'] = $data_asesor;

        $mak01 = @unserialize($asesi->mak01);
        $mak03 = @unserialize($asesi->mak03);
        $mak05 = @unserialize($asesi->mak05);
        $mak05a = @unserialize($asesi->mak05a);
        $mak02 = @unserialize($asesi->mak02);
        $mak02a = @unserialize($asesi->mak02a);
        $mak02b = @unserialize($asesi->mak02b);
        $mak04 = @unserialize($asesi->mak04);
        $mak04a = @unserialize($asesi->mak04a);
        $mak06 = @unserialize($asesi->mak06);
        $mak06a = @unserialize($asesi->mak06a);
        $mak06b = @unserialize($asesi->mak06b);
        $mak07 = @unserialize($asesi->mak07);
        $frak02 = @unserialize($asesi->frak02);
// dump($mak06a);
        $data['mak01'] = $mak01;
        $data['mak02'] = $mak02;
        $data['mak02a'] = $mak02a;
        $data['mak02b'] = $mak02b;
        $data['mak03'] = $mak03;
        $data['mak04'] = $mak04;
        $data['mak04a'] = $mak04a;
        $data['mak05'] = $mak05;
        $data['mak05a'] = $mak05a;
        $data['mak06'] = $mak06;
        $data['mak06a'] = $mak06a;
        $data['mak06b'] = $mak06b;
        $data['mak07'] = $mak07;
        $data['frak02'] = $frak02;
        $data['qr_asesi'] = $asesi->nama_lengkap." - ".$asesi->no_uji_kompetensi."\r\n\n\n".$data['aplikasi']->url_aplikasi."/qrcode/asesi/".$id;
        $data['ttd_asesor_uji'] = $data['detail_asesor']->users." - ".$data['detail_asesor']->no_reg."\r\n\n\n".$data['aplikasi']->url_aplikasi."/qrcode/asesor_uji/".$asesi->id_asesor;
        $data['ttd_manajer'] = $data['aplikasi']->mananajer_sertifikasi;
        $data['perangkat_uji'] = $this->pra_asesmen_model->perangkat_uji($asesi->perangkat_yang_digunakan);
        // dump($data['perangkat_uji']);die();
        $kode_unit = '';
        $unit = '';
        foreach($unit_kompetensi as $key=>$value){
            $kode_unit.=($key+1).'. '.$value->id_unit_kompetensi.'<br/>';
            $unit.=($key+1).'. '.$value->unit_kompetensi.'<br/>';
        }
        $data['kode_unit'] = $kode_unit;
        $data['unit'] = $unit;
        if($kategori==1){
            $view = $this->load->view('penilaian_asesor/cetak_ak01',$data , true);
         }else if($kategori==2){
             $view = $this->load->view('penilaian_asesor/cetak_ak02',$data , true);
         }else if($kategori==3){
             $view = $this->load->view('penilaian_asesor/cetak_ak03',$data , true);
         }else if($kategori==4){
             $view = $this->load->view('penilaian_asesor/cetak_ak04',$data , true);
         }else if($kategori==5){
             $view = $this->load->view('penilaian_asesor/cetak_ak05',$data , true);
         }else if($kategori==6){
             $view = $this->load->view('penilaian_asesor/cetak_ak06',$data , true);
         }
        if($type=="pdf") {
            $this->load->library("htm12pdf");
            $this->htm12pdf->pdf_create($view, "data_asesi" . date('YmdHis') . ".pdf", false, true);

        }
    }
    function edit_upload($id = false) {
        if (!$id) {
            data_not_found();
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $this->penilaian_asesor_model->set_validation()->validate();

            if ($data !== false) {
                if ($this->penilaian_asesor_model->check_unique($data, intval($id))) {
                    if (isset($_FILES['fileToUpload']['tmp_name']) && !empty($_FILES['fileToUpload']['tmp_name'])) {
                        $siswa = $this->penilaian_asesor_model->get(intval($id));
                        $data['bukti_pembayaran'] = $siswa->no_identitas . '_' . str_replace(' ', '_', $_FILES['fileToUpload']['name']);
                        $config['upload_path'] = substr(__dir__, 0, strpos(__dir__, "application")) . 'assets/files/administrasi/';
                        $config['allowed_types'] = 'bmp|jpg|png|gif|jpeg|pdf|doc|docx';
                        $config['file_name'] = $data['bukti_pembayaran'];
                        $config['max_size'] = '51200000';

                        $this->load->library('upload', $config);

                        if ($this->upload->do_upload('fileToUpload')) {
                            $current_file = substr(__dir__, 0, strpos(__dir__, "application")) . 'assets/files/administrasi/' . $siswa->bukti_pembayaran;
                            if (is_file($current_file)) {
                                unlink($current_file);
                            }
                        } else {
                            echo json_encode(array('msgType' => 'error', 'msgValue' => strip_tags($this->upload->display_errors())));
                            exit();
                        }
                    } else {
                        $data['bukti_pembayaran'] = $this->input->post('foto_hidden');
                    }
                    if ($this->penilaian_asesor_model->update(intval($id), $data) !== false) {
                        echo json_encode(array('msgType' => 'success', 'msgValue' => 'Data berhasil disimpan !'));
                    } else {
                        echo json_encode(array('msgType' => 'error', 'msgValue' => 'Data tidak dapat disimpan !'));
                    }
                } else {
                    echo json_encode(array('msgType' => 'error', 'msgValue' => implode("<br/>", $this->penilaian_asesor_model->get_validation())));
                }
            } else {
                echo json_encode(array('msgType' => 'error', 'msgValue' => validation_errors()));
            }
        }
    }

    function delete($id = false) {
        if (!$id) {
            data_not_found();
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $roles = $this->penilaian_asesor_model->get(intval($id));
            if (sizeof($roles) == 1) {
                if ($this->penilaian_asesor_model->delete(intval($id))) {
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

    function combogrid($id = false) {
        $this->load->model('v_penilaian_asesor_model');
        $row = intval($this->input->post('rows')) == 0 ? 20 : intval($this->input->post('rows'));
        $page = intval($this->input->post('page')) == 0 ? 1 : intval($this->input->post('page'));
        $offset = $row * ($page - 1);
        $data = array();
        $params = array('_return' => 'data');
        if (isset($_POST['q'])) {
            $where['asesi_name LIKE'] = "%" . $this->input->post('q') . "%";
        }
        if (isset($where))
            $params['_where'] = $where;
        $data['total'] = isset($where) ? $this->v_penilaian_asesor_model->count_by($where) : $this->v_penilaian_asesor_model->count_all();
        $this->v_penilaian_asesor_model->limit($row, $offset);
        $order_criteria = "ASC";
        $_order_escape = TRUE;
        if ($id) {
            $order = "FIELD(id, " . intval($id) . ")";
            $order_criteria = "DESC";
            $_order_escape = FALSE;
        } else {
            $order = $this->v_penilaian_asesor_model->get_params('_order');
        }
        $rows = isset($where) ? $this->v_penilaian_asesor_model->order_by($order, $order_criteria, $_order_escape)->get_many_by($where) : $this->v_penilaian_asesor_model->order_by($order, $order_criteria, $_order_escape)->get_all();
        $data['rows'] = $this->v_penilaian_asesor_model->get_selected()->data_formatter($rows);
        //var_dump($data);
        echo json_encode($data);
    }

    function cetakss($id, $type = "pdf") {
        $this->load->model('asesi_model');
        $asesi = $this->penilaian_asesor_model->data_asesi($id);
        $data['data_asesi'] = $asesi;
        $unit_kompetensi = $this->asesi_model->data_unit_kompetensi($asesi->skema_sertifikasi);
        $data['unit_kompetensi'] = $unit_kompetensi;
        $asesi_detail = $this->asesi_model->asesi_detail($id);
        $data['asesi_detail'] = $asesi_detail;

        $kode_unit = '';
        $unit = '';
        $elemen_kuk = "";
        $unit_mak = "";
        //var_dump($unit_kompetensi);die();
        foreach ($unit_kompetensi as $key => $value) {
            $kode_unit .= ($key + 1) . '. ' . $value->id_unit_kompetensi . '<br/>';
            $unit .= ($key + 1) . '. ' . $value->unit_kompetensi . '<br/>';
            $query_elemen = $this->asesi_model->elemen($value->id_unit_kompetensi);
            $detail_elemen = "";
            if (count($query_elemen) > 0) {
                foreach ($query_elemen as $keys => $values) {
                    $query_kuk = $this->asesi_model->kuk($values->id);
                    if (count($query_kuk) > 0) {
                        $detail_kuk = "";
                        foreach ($query_kuk as $k => $v) {
                            $detail_kuk .= '<tr>
                            <td style="width:45%;">' . ($k + 1) . '. ' . $v->kuk . '</td>
                            <td style="width:13%;"></td>
                            <td style="width:13%;"></td>
                            <td style="width:13%;"></td>
                            <td style="width:5%;"></td>
                            <td style="width:5%;"></td>
                            <td style="width:5%;"></td>
                          </tr>';
                        }
                    } else {
                        $detail_kuk .= '<tr>
                            <td></td>
                            <td style="width:13%"></td>
                            <td style="width:13%"></td>
                            <td style="width:13%"></td>
                            <td style="width:5%"></td>
                            <td style="width:5%"></td>
                            <td style="width:5%"></td>
                          </tr>';
                    }
                    $detail_elemen .= '<tr>
                            <td style="width:45%;font-weight:bold;"> Elemen Kompetensi </td>
                            <td colspan="7" style="width:55%;">' . ($keys + 1) . '. ' . $values->elemen_kompetensi . '</td>
                          </tr>
                          <tr>
                            <td rowspan="2" style="width:45%;text-align:center;background-color:grey;font-weight:bold;"> Kriteria Unjuk Kerja </td>
                            <td colspan="3" style="width:40%;text-align:center;background-color:grey;font-weight:bold;">Jenis Bukti</td>
                            <td colspan="3" style="width:15%;text-align:center;background-color:grey;font-weight:bold;">Keputusan</td>

                          </tr>
                          <tr>

                            <td style="width:13%;text-align:center;text-align:center;background-color:grey;font-weight:bold;">Bukti Langsung</td>
                            <td style="width:13%;text-align:center;text-align:center;background-color:grey;font-weight:bold;">Bukti Tidak Langsung</td>
                            <td style="width:13%;text-align:center;background-color:grey;font-weight:bold;">Bukti Tambahan</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">K</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">BK</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">AL</td>
                          </tr>
                          ' . $detail_kuk;
                }
            } else {
                $detail_elemen .= '<tr>
                            <td style="width:45%;font-weight:bold;"> Elemen Kompetensi </td>
                            <td colspan="7" style="width:55%;"></td>
                          </tr>
                          <tr>
                            <td rowspan="2" style="width:45%;text-align:center;background-color:grey;font-weight:bold;"> Kriteria Unjuk Kerja </td>
                            <td colspan="3" style="width:40%;text-align:center;background-color:grey;font-weight:bold;">Jenis Bukti</td>
                            <td colspan="3" style="width:15%;text-align:center;background-color:grey;font-weight:bold;">Keputusan</td>

                          </tr>
                          <tr>

                            <td style="width:13%;text-align:center;text-align:center;background-color:grey;font-weight:bold;">Bukti Langsung</td>
                            <td style="width:13%;text-align:center;text-align:center;background-color:grey;font-weight:bold;">Bukti Tidak Langsung</td>
                            <td style="width:13%;text-align:center;background-color:grey;font-weight:bold;">Bukti Tambahan</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">K</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">BK</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">AL</td>
                          </tr>';
            }
            $elemen_kuk .= '<table style="width:100%;font-size:10px;border-collapse: collapse;" border="1" >
                          <tr>
                            <td style="width:45%;font-weight:bold;"> Kode Unit Kompetensi </td>
                            <td colspan="7" style="width:55%;">' . $value->id_unit_kompetensi . '</td>
                          </tr>
                          <tr>
                            <td style="width:45%;font-weight:bold;"> Unit Kompetensi </td>
                            <td colspan="7" style="width:55%;">' . $value->unit_kompetensi . '</td>
                          </tr>
                          ' . $detail_elemen . '
                        </table><br/>';
            $unit_mak .= '<tr>
                            <td colspan="2" style="width:45%">
                            ' . $value->unit_kompetensi . '
                            </td>
                            <td style="width:10%">  </td>
                            <td style="width:10%">  </td>
                            <td style="width:35%">  </td>
                          </tr>';
        }
        //'.//$detail_elemen.'
        $data['unit_mak'] = $unit_mak;
        $data['elemen_kuk'] = $elemen_kuk;
        foreach ($asesi_detail as $key => $value) {
            $jenis_bukti[] = $value->jenis_bukti;
        }

        $jenis_bukti = implode(',', array_unique($jenis_bukti));
        $data['jenis_bukti'] = $jenis_bukti;
        $data['kode_unit'] = $kode_unit;
        $data['unit'] = $unit;

        $view = $this->load->view('penilaian_asesor/cetak', $data, true);
        if ($type == "pdf") {
            $this->load->library("htm12pdf");
            $this->htm12pdf->pdf_create($view, "data_asesi" . date('YmdHis') . ".pdf", false, true);
        }
    }

    function sms($rekomendasi_description, $telp, $id_users, $rekomendasi_asesor) {
        //var dt = {id_users:id_users,rekomendasi:rekomendasi,pra_asesmen_description:pra_asesmen_description};
        if ($rekomendasi_asesor == 1) {
            $hasil_rekomendasi_asesor = 'Kompeten';
        } else if ($rekomendasi_asesor == 2) {
            $hasil_rekomendasi_asesor = 'Belum Kompeten';
        } else {
            $hasil_rekomendasi_asesor = 'Belum ada hasil';
        }

        $datax['sender_id'] = $this->auth->get_user_data()->id;
        $datax['reciepent_id'] = $id_users;
        $datax['title'] = 'Hasil Rekomendasi Asesor';
        $datax['message'] = 'Rekomendasi Asesor adalah ' . $hasil_rekomendasi_asesor . '.  ' . $rekomendasi_description;

        $this->load->model('Pesan_Model');
        $this->Pesan_Model->insert($datax);

        return smssend($telp, $datax['message']);
    }

    function cetak($id, $type = "pdf") {
        $data['aplikasi'] = $this->db->get('r_konfigurasi_aplikasi')->row();

        $this->load->model('asesi_model');
        $asesi = $this->real_asesmen_model->data_asesi($id);
        // if ($asesi->no_uji_kompetensi == "") {
        //     echo json_encode(array('msgType' => 'error', 'msgValue' => 'Belum di jadwalkan. Edit terlebih dahulu !'));
        //     exit;
        // }
        $data['data_asesi'] = $asesi;
        $unit_kompetensi = $this->asesi_model->data_unit_kompetensi($asesi->skema_sertifikasi);
        $data['unit_kompetensi'] = $unit_kompetensi;
        $asesi_detail = $this->asesi_model->asesi_detail($id);
        $data['asesi_detail'] = $asesi_detail;
        //Nama Asesor dan No reg
        $this->db->where('id', $asesi->id_asesor);
        $data_asesor = $this->db->get(kode_lsp() . 'users')->row();
        $data['nama_asesor'] = $data_asesor->users;
        $data['no_reg_asesor'] = $data_asesor->no_reg;
        $kode_unit = '';
        $unit = '';
        $elemen_kuk = "";
        $unit_mak = "";
        //var_dump($unit_kompetensi);die();
        foreach ($unit_kompetensi as $key => $value) {
            $kode_unit .= ($key + 1) . '. ' . $value->id_unit_kompetensi . '<br/>';
            $unit .= '<label style="font-size:10px;">' . ($key + 1) . '. ' . $value->unit_kompetensi . '</label><br/>';
            $query_elemen = $this->asesi_model->elemen($value->id_unit_kompetensi);
            $detail_elemen = "";
            if (count($query_elemen) > 0) {
                foreach ($query_elemen as $keys => $values) {
                    $query_kuk = $this->asesi_model->kuk($values->id);
                    if (count($query_kuk) > 0) {
                        $detail_kuk = "";
                        foreach ($query_kuk as $k => $v) {
                            $detail_kuk .= '<tr>
                            <td style="width:45%;">' . ($k + 1) . '. ' . $v->kuk . '</td>
                            <td style="width:13%;"></td>
                            <td style="width:13%;"></td>
                            <td style="width:13%;"></td>
                            <td style="width:5%;"></td>
                            <td style="width:5%;"></td>
                            <td style="width:5%;"></td>
                          </tr>';
                        }
                    } else {
                        $detail_kuk .= '<tr>
                            <td></td>
                            <td style="width:13%"></td>
                            <td style="width:13%"></td>
                            <td style="width:13%"></td>
                            <td style="width:5%"></td>
                            <td style="width:5%"></td>
                            <td style="width:5%"></td>
                          </tr>';
                    }
                    $detail_elemen .= '<tr>
                            <td style="width:45%;font-weight:bold;"> Elemen Kompetensi </td>
                            <td colspan="7" style="width:55%;">' . ($keys + 1) . '. ' . $values->elemen_kompetensi . '</td>
                          </tr>
                          <tr>
                            <td rowspan="2" style="width:45%;text-align:center;background-color:grey;font-weight:bold;"> Kriteria Unjuk Kerja </td>
                            <td colspan="3" style="width:40%;text-align:center;background-color:grey;font-weight:bold;">Jenis Bukti</td>
                            <td colspan="3" style="width:15%;text-align:center;background-color:grey;font-weight:bold;">Keputusan</td>

                          </tr>
                          <tr>

                            <td style="width:13%;text-align:center;text-align:center;background-color:grey;font-weight:bold;">Bukti Langsung</td>
                            <td style="width:13%;text-align:center;text-align:center;background-color:grey;font-weight:bold;">Bukti Tidak Langsung</td>
                            <td style="width:13%;text-align:center;background-color:grey;font-weight:bold;">Bukti Tambahan</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">K</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">BK</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">AL</td>
                          </tr>
                          ' . $detail_kuk;
                }
            } else {
                $detail_elemen .= '<tr>
                            <td style="width:45%;font-weight:bold;"> Elemen Kompetensi </td>
                            <td colspan="7" style="width:55%;"></td>
                          </tr>
                          <tr>
                            <td rowspan="2" style="width:45%;text-align:center;background-color:grey;font-weight:bold;"> Kriteria Unjuk Kerja </td>
                            <td colspan="3" style="width:40%;text-align:center;background-color:grey;font-weight:bold;">Jenis Bukti</td>
                            <td colspan="3" style="width:15%;text-align:center;background-color:grey;font-weight:bold;">Keputusan</td>

                          </tr>
                          <tr>

                            <td style="width:13%;text-align:center;text-align:center;background-color:grey;font-weight:bold;">Bukti Langsung</td>
                            <td style="width:13%;text-align:center;text-align:center;background-color:grey;font-weight:bold;">Bukti Tidak Langsung</td>
                            <td style="width:13%;text-align:center;background-color:grey;font-weight:bold;">Bukti Tambahan</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">K</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">BK</td>
                            <td style="width:5%;text-align:center;background-color:grey;font-weight:bold;">AL</td>
                          </tr>';
            }
            $elemen_kuk .= '<table style="width:100%;font-size:10px;border-collapse: collapse;" border="1" >
                          <tr>
                            <td style="width:45%;font-weight:bold;"> Kode Unit Kompetensi </td>
                            <td colspan="7" style="width:55%;">' . $value->id_unit_kompetensi . '</td>
                          </tr>
                          <tr>
                            <td style="width:45%;font-weight:bold;"> Unit Kompetensi </td>
                            <td colspan="7" style="width:55%;">' . $value->unit_kompetensi . '</td>
                          </tr>
                          ' . $detail_elemen . '
                        </table><br/>';
            $unit_mak .= '<tr>
                            <td colspan="2" style="width:45%">
                            ' . $value->unit_kompetensi . '
                            </td>
                            <td style="width:10%">  </td>
                            <td style="width:10%">  </td>
                            <td style="width:35%">  </td>
                          </tr>';
        }
        //'.//$detail_elemen.'
        $data['unit_mak'] = $unit_mak;
        $data['elemen_kuk'] = $elemen_kuk;
        foreach ($asesi_detail as $key => $value) {
            $jenis_bukti[] = $value->jenis_bukti;
        }

        $jenis_bukti = implode(',', array_unique($jenis_bukti));
        $data['jenis_bukti'] = $jenis_bukti;
        $data['kode_unit'] = $kode_unit;
        $data['unit'] = $unit;
        $data['qr_asesi'] = $asesi->nama_lengkap . " - " . $asesi->no_uji_kompetensi . "\r\n\n\n" . $data['aplikasi']->url_aplikasi . "/qrcode/asesi/" . $id;
        $this->load->model('pra_asesmen_model');
        $data['asesor_pra_asesmen'] = $this->pra_asesmen_model->asesor_pra_asesmen($asesi->pra_asesmen_checked);
        $data['ttd_asesor'] = $data['asesor_pra_asesmen']->users . " - " . $data['asesor_pra_asesmen']->no_reg . "\r\n\n\n" . $data['aplikasi']->url_aplikasi . "/qrcode/asesor/" . $asesi->pra_asesmen_checked;

        $data['ttd_asesor_uji'] = $data['nama_asesor'] . " - " . $data['no_reg_asesor'] . "\r\n\n\n" . $data['aplikasi']->url_aplikasi . "/qrcode/asesor_uji/" . $asesi->id_asesor;
        $view = $this->load->view('real_asesmen/cetak', $data, true);
        if ($type == "pdf") {
//            error_reporting(E_ALL);
//            ini_set('display_errors', TRUE);
//            ini_set('display_startup_errors', TRUE);
            $this->load->library("htm12pdf");
            $this->htm12pdf->pdf_create($view, "data_asesi" . date('YmdHis') . ".pdf", false, true);
        }
    }

    function search() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load->model('penilaian_asesor_model');
            $view = $this->load->view('penilaian_asesor/search', array(), TRUE);
            echo json_encode(array('msgType' => 'success', 'msgValue' => $view));

        } else {
            block_access_method();
        }
    }

    function proses_clo(){

    }

    function proses_dpl(){

    }

}
