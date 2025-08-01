<h1 class="h3 mb-0 text-gray-800"><?php if($aksi == 'add'){ ?> Tambah <?php }else{ ?> Edit <?php } ?> Subkriteria</h1>
<div class="card shadow mb-4 mt-2">
    <div class="card-body">
        <form <?php if($aksi == 'add'){ ?> action="<?= base_url('admin/simpan_subkriteria') ?>" <?php }else{ ?> action="<?= base_url('admin/update_subkriteria/'.$subkriteria->id) ?>" <?php } ?>  method="POST">
			<div class="form-group">
				<label for="nama">Nama Subkriteria</label>
				<input type="text" placeholder="Masukkan nama subkriteria" class="form-control" name="nama_sub" <?php if($aksi == 'edit'){ ?> value="<?= $subkriteria->nama_sub ?>" <?php } ?> id="nama" required> 
			</div>
			<div class="form-group">
				<label for="prioritas">Prioritas</label>
				<input type="text" placeholder="Masukkan prioritas subkriteria" class="form-control" name="prioritas" <?php if($aksi == 'edit'){ ?> value="<?= $subkriteria->prioritas ?>" <?php } ?> id="prioritas" required> 
			</div>
			<input type="hidden" name="id_kriteria" <?php if($aksi == 'add'){ ?> value="<?= $kriteria->id ?>" <?php }else{ ?> value="<?= $subkriteria->id_kriteria ?>" <?php } ?>  id="">
			<button type="submit" class="btn btn-primary">Simpan</button>
			<a <?php if($aksi == 'add'){ ?> href="<?= base_url('admin/subkriteria/'.$kriteria->id) ?>" <?php }else{ ?> href="<?= base_url('admin/subkriteria/'.$subkriteria->id_kriteria) ?>" <?php } ?>  class="btn btn-secondary">Batal</a>
		</form>
    </div>
</div>
