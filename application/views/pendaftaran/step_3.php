<style>
  .form-upload-tb {
    padding: 10px;
    /* height: 150px; */
  }
  .form-link-tb {
    display: block;
    width: 100px;
    /* height: 150px; */
    /* color: #555; */
    /* background-color: #eee; */
    background-image: none;
    /* border: 1px solid #ccc;
    border-radius: 5px; */
    padding: 5px 98px 5px 5px;
    text-decoration: none!important;

    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
  }
  .form-images-tb {
    width: 80px;
  }
  .form-link-delete {
    color:red;
    position: absolute;
    top: 7px;
    left: 94px;
    text-decoration: none!important;

    padding: 5px;
    cursor: pointer;
  }
</style>


<section>
  <div class="inner">

    <div class="wizard-header">
      <h3 class="heading">UPLOAD BUKTI PENDUKUNG</h3>
      <p>Pada bagian ini, Anda diminta untuk mengunggah Persyaratan Wajib yang Anda anggap relevan terhadap setiap elemen/KUK unit kompetensi.</p>
    </div>

    <div class="" id="div_unit_dipilih">
        <div id="unit_dipilih"></div>
    </div>

    <div class="wizard-header">
      <p>Upload beberapa dokumen yang menunjukan bukti pemenuhan persyaratan dasar sertifikasi yang ditetapkan dalam dokumen skema sertifikasi.</p>
    </div>

    <!-- <div class="form-row">
      <div class="form-holder form-holder-1">
        <input type="text" name="find_bank" id="find_bank" placeholder="Find Your Bank" class="form-control" required>
      </div>
    </div> -->

    <div class="form-row">
      <div class="form-holder form-holder-2">
        <fieldset>
          <legend>Pasfoto (4x6) <b class="harus_diisi">*</b></legend>
          <input type="file" id="pasfoto" accept="image/*" required>
          <div id="dpasfoto"></div>
        </fieldset>
      </div>
    </div>

    <div class="form-row">
      <div class="form-holder form-holder-2">
        <fieldset>
          <legend>Identitas Pribadi (KTP/SIM) <b class="harus_diisi">*</b></legend>
          <input type="file" id="ktp" accept="image/*" required>
        </fieldset>
      </div>
    </div>

    <div class="form-row">
      <div class="form-holder form-holder-2">
        <fieldset>
          <legend>CV (Daftar Riwayat Hidup) <b class="harus_diisi">*</b></legend>
          <input type="file" id="cv" accept="image/*" required>
        </fieldset>
      </div>
    </div>

    <div class="form-row">
      <div class="form-holder form-holder-2">
        <fieldset>
          <legend>Ijazah <b class="harus_diisi">*</b></legend>
          <input type="file" id="ijazah" accept="image/*" required>
        </fieldset>
      </div>
    </div>

    <div class="form-row-total">
      <div class="form-row">
        <div class="form-holder form-holder-2 form-holder-3">
          <input type="radio" class="radio" name="bank-1" id="bank-1" value="bank-1" checked>
          <label class="bank-images label-above bank-1-label" for="bank-1">
            <img src="<?=base_url()?>assets/_tera_byte/form/images/form-v1-1.png" alt="bank-1">
          </label>

          <input type="radio" class="radio" name="bank-2" id="bank-2" value="bank-2">
          <label class="bank-images label-above bank-2-label" for="bank-2">
            <img src="<?=base_url()?>assets/_tera_byte/form/images/form-v1-2.png" alt="bank-2">
          </label>

          <input type="radio" class="radio" name="bank-3" id="bank-3" value="bank-3">
          <label class="bank-images label-above bank-3-label" for="bank-3">
            <img src="<?=base_url()?>assets/_tera_byte/form/images/form-v1-3.png" alt="bank-3">
          </label>
        </div>
      </div>

      <div class="form-row">
        <div class="form-holder form-holder-2 form-holder-3">
          <input type="radio" class="radio" name="bank-4" id="bank-4" value="bank-4">
          <label class="bank-images bank-4-label" for="bank-4">
            <img src="<?=base_url()?>assets/_tera_byte/form/images/form-v1-4.png" alt="bank-4">
          </label>

          <input type="radio" class="radio" name="bank-5" id="bank-5" value="bank-5">
          <label class="bank-images bank-5-label" for="bank-5">
            <img src="<?=base_url()?>assets/_tera_byte/form/images/form-v1-5.png" alt="bank-5">
          </label>
          
          <input type="radio" class="radio" name="bank-6" id="bank-6" value="bank-6">
          <label class="bank-images bank-6-label" for="bank-6">
            <img src="<?=base_url()?>assets/_tera_byte/form/images/form-v1-6.png" alt="bank-6">
          </label>
        </div>
      </div>
    </div>

  </div>
</section>

<script>
  var baseUrl = "<?= base_url(); ?>";

  $("#pasfoto").on('change', function (e) {
        e.preventDefault();
        var urlTarget = baseUrl + "welcome/upload_ajax/foto";
        var f = $(this);
        var listFiles = f[0].files;
        var formData = new FormData();
        formData.append('file', listFiles[0]);
        $('#myOverlay').show();
        $('#loadingGIF').show();
        $.ajax({
            url: urlTarget,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
               data = JSON.parse(data);
               if(data.error){
                $('#myOverlay').hide();
                $('#loadingGIF').hide();
                $("#pasfoto").val("");
                alert(data.error);
               }else{
                 
                var idUpload = data.upload_data.file_name;
                var acuanId = idUpload.replace(".", "-");

                 var txt0 = "<div id='box-"+acuanId+"' class='col col-md-3 col-sm-4 col-xs-6 form-upload-tb'>";
                 var txt1 = "<input id='nd-' type='hidden' name='nama_dokumen[]' class='nama_dokumen' value='foto' />";
                 var txt2 = "<input id='fd-' type='hidden' name='file_data[]' id='foto' class='form-control input-sm uploadData' value='" + data.upload_data.file_name + "' />";
                 var txt3 = "<a id='al-' target='_blank' class='form-link-tb uploadData' href='" + baseUrl + 'repo/asesi/' + data.upload_data.file_name + "'>";
                 var txt4 = "<img id='img-"+data.upload_data.file_name+"' class='form-images-tb' src='" + baseUrl + 'repo/asesi/' + data.upload_data.file_name + "' alt='bank-6'></a>";
                 var txt5 = "<span id='span-' class='form-link-delete' title='Hapus Foto' datatb='"+data.upload_data.file_name+"' onclick='deleteImage(this)'><i class='fa fa-times'></i></span></div>";

                $("#dpasfoto").append(txt0 + txt1 + txt2 + txt3 + txt4 + txt5);
                $('#myOverlay').hide();
                $('#loadingGIF').hide();
               }
            },
            error: function (request, status, error) {
                alert(request.responseText);
            }
        });
        return false;
    });

    function deleteImage(d) {
      var getImg  = d.getAttribute("datatb");
      var getId  = "#box-" + getImg;
      var resId = getId.replace(".", "-");
      
      var urlTarget = baseUrl + "welcome/delete_ajax/" + getImg;
      $('#myOverlay').show();
      $('#loadingGIF').show();

      $.ajax({
        type: 'POST',
        url: urlTarget,
        // data: data,
        success: function() {
          $(resId).remove();
          $('#myOverlay').hide();
          $('#loadingGIF').hide();
        }
      });
      return false;
      
      // alert(resId);

    };
    
</script>
