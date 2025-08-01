<h1 class="h3 mb-0 text-gray-800"><?php if($aksi == 'add'){ ?> Tambah <?php }else{ ?> Edit <?php } ?> Kriteria</h1>
<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <form <?php if($aksi == 'add'){ ?> action="<?= base_url('admin/simpan_kriteria') ?>" <?php }else{ ?> action="<?= base_url('admin/update_kriteria/'.$kriteria->id) ?>" <?php } ?>  method="POST">
			<div class="form-group">
				<label for="nama">Nama Kriteria</label>
				<input type="text" placeholder="Masukkan nama kriteria" class="form-control" name="nama_kriteria" <?php if($aksi == 'edit'){ ?> value="<?= $kriteria->nama_kriteria ?>" <?php } ?> id="nama" required> 
			</div>
			<div class="form-group">
				<label for="nama">Tren</label>
				<select name="tren" class="form-control" id="" required>
					<option value="">Pilih</option>
					<option value="Positif" <?php if($aksi == 'edit' && $kriteria->tren == 'Positif'){ ?>selected <?php } ?>>Positif</option>
					<option value="Negatif" <?php if($aksi == 'edit' && $kriteria->tren == 'Negatif'){ ?>selected <?php } ?>>Negatif</option>
				</select>	
			</div>
			<div class="form-group">
				<label for="prioritas">Prioritas</label>
				<input type="text" placeholder="Masukkan prioritas kriteria" class="form-control" name="prioritas" <?php if($aksi == 'edit'){ ?> value="<?= $kriteria->prioritas ?>" <?php } ?> id="prioritas" required> 
			</div>
			<button type="submit" class="btn btn-primary">Simpan</button>
			<a href="<?= base_url('admin/kriteria') ?>" class="btn btn-secondary">Batal</a>
		</form>
    </div>
</div>
