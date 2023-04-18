<link href="<?= base_url() ?>assets/global/plugins/datatables/datatables.min.css" rel="stylesheet" type="text/css" />

    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper">
        <!-- BEGIN CONTENT BODY -->
        <div class="page-content">
            <div class="page-bar">
                <ul class="page-breadcrumb">
                    <li>
                        <a href="<?=base_url() ?>">Home</a>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Sertifikasi</span>
                        <i class="fa fa-circle"></i>
                    </li>
                    <li>
                        <span>Proses Sertifikasi</span>
                    </li>
                </ul>
                <div class="page-toolbar">
                    <div><?= tgl_indo(date('Y-m-d')) ?>
                        <i class="icon-calendar"></i>&nbsp;
                        <span class="thin uppercase hidden-xs"></span>&nbsp;
                    </div>
                </div>
            </div>

            <div class="row" style="margin-top: 10px;">
                <div class="col-md-12">
                    <!-- BEGIN EXAMPLE TABLE PORTLET-->
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption font-dark col-md-6">
                                <i class="icon-settings font-dark"></i>
                                <span class="caption-subject bold uppercase"> Proses Sertifikasi</span>
                            </div>


                        </div>
                        <div class="portlet-body">

                            <table class="table table-responsive table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th> Skema Sertifikasi </th>
                                        <th> Tanggal Daftar </th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($riwayat_sertifikasi as $key => $value) {
                                        if ($value->pra_asesmen_checked == '') {
                                            $label = 'Registrasi Baru';
                                        } else if ($value->pra_asesmen_checked == '1') {
                                            $label = 'Pra Asesmen';
                                        } else {
                                            $label = '';
                                        }
                                        ?>
                                        <tr class="odd gradeX">

                                            <td> <?= $value->skema ?> </td>
                                            <td><?= $value->u_date_create ?>
                                            </td>
                                           
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END EXAMPLE TABLE PORTLET-->
                </div>
            </div>

          </div>
         </div>



            <script src="<?= base_url() ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
            <script src="<?= base_url() ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
            <script src="<?= base_url() ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
            <!-- END PAGE LEVEL PLUGINS -->
            <!-- BEGIN THEME GLOBAL SCRIPTS -->
            <script src="<?= base_url() ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
            <!-- END THEME GLOBAL SCRIPTS -->
            <!-- BEGIN PAGE LEVEL SCRIPTS -->
            <script src="<?= base_url() ?>assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
