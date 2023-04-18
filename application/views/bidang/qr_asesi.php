<link href="<?php echo base_url() ?>_assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    
    <style type="text/css">
	.user-row {
    margin-bottom: 14px;
}

.user-row:last-child {
    margin-bottom: 0;
}

.dropdown-user {
    margin: 13px 0;
    padding: 5px;
    height: 100%;
}

.dropdown-user:hover {
    cursor: pointer;
}

.table-user-information > tbody > tr {
    border-top: 1px solid rgb(221, 221, 221);
}

.table-user-information > tbody > tr:first-child {
    border-top: 0;
}


.table-user-information > tbody > tr > td {
    border-top: 0;
}
.toppad
{margin-top:20px;
}

</style>
<?php
if(count($data) > 0){
?>
<div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xs-offset-0 col-sm-offset-0  toppad" >
   
   
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"><?=$data->nama_lengkap?></h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> <img alt="<?=$data->nama_lengkap?> Picture" src="<?=base_url().'assets/img/men.jpg'?>" class="img-circle img-responsive"> </div>
                
                <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
                  <dl>
                    <dt>DEPARTMENT:</dt>
                    <dd>Administrator</dd>
                    <dt>HIRE DATE</dt>
                    <dd>11/12/2013</dd>
                    <dt>DATE OF BIRTH</dt>
                       <dd>11/12/2013</dd>
                    <dt>GENDER</dt>
                    <dd>Male</dd>
                  </dl>
                </div>-->
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>No Identitas :</td>
                        <td><?=$data->no_identitas?></td>
                      </tr>
                      <tr>
                        <td>Nama Lengkap:</td>
                        <td><?=$data->nama_lengkap?></td>
                      </tr>
                      <tr>
                        <td>No Uji Kompetensi</td>
                        <td><?=$data->no_uji_kompetensi?></td>
                      </tr>
                   
                         <tr>
                             <tr>
                        <td>Jenis Kelamin</td>
                        <td><?=$data->jenis_kelamin=='1' ? 'Pria' : 'Wanita';?></td>
                      </tr>
                        <tr>
                        <td>Skema Sertifikat</td>
                        <td><?=$data->skema?></td>
                      </tr>
                      <tr>
                        <td>Tempat Uji Kompetensi</td>
                        <td><?=$data->tuk?></a></td>
                      </tr>
                       
                     
                    </tbody>
                  </table>
                  
                  
                </div>
              </div>
            </div>
                
            
          </div>
        </div>
      </div>
    </div>

    

<?php }else{ ?>

	<h3>TIDAK TERDAFTAR SEBAGAI ASESI / PESERTA</h3>

	<?php } ?>
 <script src="<?php echo base_url() ?>_assets/jquery/js/jquery.min.js"></script>	    
 <script src="<?php echo base_url() ?>_assets/bootstrap/js/bootstrap.min.js"></script>
   
<script type="text/javascript">
	$(document).ready(function() {
    var panels = $('.user-infos');
    var panelsButton = $('.dropdown-user');
    panels.hide();

    //Click dropdown
    panelsButton.click(function() {
        //get data-for attribute
        var dataFor = $(this).attr('data-for');
        var idFor = $(dataFor);

        //current button
        var currentButton = $(this);
        idFor.slideToggle(400, function() {
            //Completed slidetoggle
            if(idFor.is(':visible'))
            {
                currentButton.html('<i class="glyphicon glyphicon-chevron-up text-muted"></i>');
            }
            else
            {
                currentButton.html('<i class="glyphicon glyphicon-chevron-down text-muted"></i>');
            }
        })
    });


    $('[data-toggle="tooltip"]').tooltip();

    $('button').click(function(e) {
        e.preventDefault();
        alert("This is a demo.\n :-)");
    });
});
</script>