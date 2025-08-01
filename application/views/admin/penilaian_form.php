<h1 class="h3 mb-0 text-gray-800">Penilaian</h1>
<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <form action="<?= base_url('penilaian/simpan') ?>" method="POST">
			<div class="form-group">
				<label for="nama">Alternatif</label>
				<select name="id_alternatif" class="form-control" id="" required>
					<option value="">Pilih Alternatif</option>
					<?php foreach($alternatif as $alt){ ?>
						<option value="<?= $alt->id ?>"><?= $alt->nama_sunscreen ?></option>
					<?php } ?>
				</select>	
			</div>
			<?php foreach($kriteria as $krit) { ?>
				<div class="form-group">
				<label for=""><?= $krit->nama_kriteria ?></label>
				<input type="hidden" name="id_kriteria[]" value="<?= $krit->id ?>" id="">
				<select name="nilai[]" class="form-control" id="" required>
					<option value="">Pilih Nilai</option>
					<?php $subkriteria = $this->db->query("SELECT * FROM subkriteria WHERE id_kriteria='$krit->id'")->result();foreach($subkriteria as $sub){ ?>
					<option value="<?= $sub->nilai ?>"><?= $sub->nama_sub ?> (<?= $sub->nilai ?>)</option>
					<?php } ?>
				</select>
				</div>
			<?php } ?>
			<button type="submit" class="btn btn-primary">Simpan</button>
			<a href="<?= base_url('penilaian') ?>" class="btn btn-secondary">Batal</a>
		</form>
    </div>
</div>
