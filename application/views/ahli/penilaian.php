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
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Suncscreen</th>
                        <?php foreach($kriteria as $krit){ ?>
                        <th><?= $krit->nama_kriteria ?></th>
                        <?php $nilai = $this->db->query("SELECT * FROM penilaian where id_kriteria='$krit->id'")->row(); ?>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1;foreach ($penilaian as $row): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_sunscreen'] ?></td>
                        <?php 
                $nilai = explode(',', $row['nilai']); 
                foreach ($nilai as $n): 
            ?>
                        <td><?= $n ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
