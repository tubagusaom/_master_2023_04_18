<div role="main" class="main">

        

    <div class="container" style="margin-top: 35px;">
      <div class="row">
            <div class="col-md-12">
              
            <?php
             if($this->session->flashdata('result')!=''){
        ?>
        <div style="margin-top: 25px;" class="alert alert-<?=$this->session->flashdata('mode_alert')?>" role="alert"><?php echo $this->session->flashdata('result'); ?></div>
        <?php
        }
        ?>
        

            
            
            </div>
          </div>

          
        </div>

        
        

      </div>