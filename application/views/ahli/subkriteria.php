<h1 class="h3 mb-0 text-gray-800">Subkriteria <?= $kriteria->nama_kriteria ?></h1>
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
        <a href="<?= base_url('ahli/hitung_nilai/'.$kriteria->id) ?>" class="btn btn-success">Update Nilai</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Nama Subkriteria</th>
                        <th>Prioritas</th>
                        <th>Nilai</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1;foreach($subkriteria as $data){ ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data->nama_sub ?></td>
                        <td><?= $data->prioritas ?></td>
                        <td><?= $data->nilai ?></td>
                        <td>
                            <a href="<?= base_url('ahli/edit_subkriteria/' . $data->id) ?>" class="btn btn-primary"><i
                                    class="fas fa-pencil-alt" title="Edit"></i></a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
