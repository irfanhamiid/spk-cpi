<h1 class="h3 mb-0 text-gray-800">Penilaian</h1>
<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <form action="<?= base_url('penilaian/update') ?>" method="POST">
			<div class="form-group">
				<label for="nama">Alternatif</label>
				<input type="text" name="id_alternatif" value="<?= $alternatif->id ?>" id="">
				<input type="text" readonly class="form-control" name="" value="<?= $alternatif->nama_sunscreen ?>" id="">
			</div>
			<?php foreach($kriteria as $krit) { ?>
				<?php $penilaian = $this->db->query("SELECT * FROM penilaian WHERE id_alternatif ='$alternatif->id' AND id_kriteria='$krit->id'")->row(); ?>
				<div class="form-group">
				<label for=""><?= $krit->nama_kriteria ?></label>
				<input type="hidden" name="id_kriteria[]" value="<?= $krit->id ?>" id="">
				<select name="nilai[]" class="form-control" id="" required>
					<option value="">Pilih Nilai</option>
					<?php $subkriteria = $this->db->query("SELECT * FROM subkriteria WHERE id_kriteria='$krit->id'")->result();foreach($subkriteria as $sub){ ?>
					<option value="<?= $sub->nilai ?>" <?php if($penilaian->nilai == $sub->nilai) { ?> selected <?php } ?>><?= $sub->nama_sub ?> (<?= $sub->nilai ?>)</option>
					<?php } ?>
				</select>
				</div>
			<?php } ?>
			<button type="submit" class="btn btn-primary">Simpan</button>
			<a href="<?= base_url('penilaian') ?>" class="btn btn-secondary">Batal</a>
		</form>
    </div>
</div>
