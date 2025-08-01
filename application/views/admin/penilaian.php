<h1 class="h3 mb-0 text-gray-800">Penilaian</h1>
<?php if($this->session->flashdata('pesan')){ ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong><?= $this->session->flashdata('pesan') ?></strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php } ?>
<div class="card shadow mb-4 mt-2">
    <div class="card-header py-3">
        <a href="<?= base_url('penilaian/tambah') ?>" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Penilaian</span>
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Suncscreen</th>
                        <?php foreach($kriteria as $krit){ ?>
                        <th><?= $krit->nama_kriteria ?></th>
                        <?php } ?>
                        <th style="width: 10%;">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1;foreach ($penilaian as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_sunscreen'] ?></td>
                        <!-- <?php 
                $nilai = explode(',', $row['nilai']); 
                foreach ($nilai as $n): 
            ?>
                        <td><?= $n ?></td>
                        <?php endforeach; ?> -->
						<?php 
							$nilai = explode(',', $row['nilai']);
							$id_kriterida = explode(',',$row['id_kriteria']);

							foreach($id_kriterida as $index => $kriteria_id):
								$nilai_sekarang = $nilai[$index];
								$this->db->select('nama_sub');
								$this->db->from('subkriteria');
								$this->db->where('id_kriteria',$kriteria_id);
								$this->db->where('nilai',$nilai_sekarang);
								$query_subkriteria = $this->db->get();

								if($query_subkriteria->num_rows() > 0){
									$subkriteria = $query_subkriteria->row()->nama_sub;
								}

							
						?>
						<td><?= $nilai_sekarang ?> (<?= $subkriteria ?>)</td>
						<?php endforeach ?>
						<td>
							<a href="<?= base_url('penilaian/edit/'.$row['id_alternatif']) ?>" class="btn btn-primary"><i class="fas fa-edit"></i></a>
							<a href="<?= base_url('penilaian/hapus/'.$row['id_alternatif']) ?>" class="btn btn-danger"><i class="fas fa-trash"></i></a>
						</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
