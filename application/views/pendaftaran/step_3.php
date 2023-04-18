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
          <input type="file" id="file_bukti_pendukung" accept="image/*" required>
        </fieldset>
      </div>
    </div>

    <div class="form-row">
      <div class="form-holder form-holder-2">
        <fieldset>
          <legend>Identitas Pribadi (KTP/SIM) <b class="harus_diisi">*</b></legend>
          <input type="file" id="images" accept="image/*" required>
        </fieldset>
      </div>
    </div>

    <div class="form-row">
      <div class="form-holder form-holder-2">
        <fieldset>
          <legend>CV (Daftar Riwayat Hidup) <b class="harus_diisi">*</b></legend>
          <input type="file" id="images" accept="image/*" required>
        </fieldset>
      </div>
    </div>

    <div class="form-row">
      <div class="form-holder form-holder-2">
        <fieldset>
          <legend>Ijazah <b class="harus_diisi">*</b></legend>
          <input type="file" id="images" accept="image/*" required>
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
