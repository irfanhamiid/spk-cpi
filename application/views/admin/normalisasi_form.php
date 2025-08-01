<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Rekomendasi Sunscreen</h1>
</div>
<div class="row">
	<div class="col-12">
    <div class="card">
        <div class="card-body">
            <form action="<?= base_url('penilaian/normalisasi') ?>" method="POST">
				<input type="hidden" name="id_kriteria" value="<?= $kriteria->id ?>" id="">
                <div class="form-group">
					<label for="">Jenis Kulit</label>
					<select name="nilai" id="" class="form-control">
						<option value="">Pilih Jenis Kulit</option>
						<?php foreach($subkriteria as $sub){ ?>
						<option value="<?= $sub->nilai ?>"><?= $sub->nama_sub ?></option>
						<?php } ?>
					</select>
                </div>
				<button class="btn btn-success" type="submit">Proses Rekomendasi</button>
            </form>
        </div>
    </div>
	</div>

</div>
