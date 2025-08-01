<h1 class="h3 mb-0 text-gray-800">Alternatif</h1>
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
        <button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#tambah">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Altenatif</span>
        </button>
        <div class="modal fade" id="tambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Alternatif</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url('admin/simpan_alternatif') ?>" method="POST"
                            enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="form-label" for="basic-icon-default-fullname">Foto</label>
                                <div style="width: 80%; height: 80%; overflow: hidden;">
                                    <img class="centered" height="100%" id="uploadPreview" width="100%">
                                </div>
                                <hr>
                                <input id="upload" onchange="PreviewImage()" type="file" class="form-control"
                                    name="image" accept=".png,.jpg,.jpeg">
                            </div>
                            <div class="form-group">
                                <label for="">Nama Sunscreen</label>
                                <input type="text" class="form-control" name="nama_sunscreen" required
                                    placeholder="Masukkan nama sunscreen" id="">
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th>Foto</th>
                        <th>Nama Suncscreen</th>
                        <th style="width: 10%;">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no=1;foreach($alternatif as $data){ ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><img src="<?= base_url('assets/img/' . $data->image) ?>" alt=""
                                style="max-width:100px"></td>
                        <td><?= $data->nama_sunscreen ?></td>
                        <td>
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#edit<?= $data->id ?>">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <div class="modal fade" id="edit<?= $data->id ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Alternatif</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?= base_url('admin/update_alternatif/' . $data->id) ?>"
                                                method="POST" enctype="multipart/form-data">
                                                <div class="form-group">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Foto</label>
                                                    <div style="width: 80%; height: 80%; overflow: hidden;">
                                                        <img class="centered preview-image" height="100%"
                                                            src="<?= base_url('assets/img/' . $data->image) ?>"
                                                            width="100%">
                                                    </div>
                                                    <hr>
                                                    <input type="file" class="form-control upload-input"
                                                        name="image" accept=".png,.jpg,.jpeg">
                                                </div>
                                                <script>
                                                    // Tambahkan event listener ke semua input dengan class "upload-input"
                                                    document.querySelectorAll('.upload-input').forEach((input, index) => {
                                                        input.addEventListener('change', function() {
                                                            const file = input.files[0];
                                                            const previewImage = input.closest('.form-group').querySelector('.preview-image');

                                                            if (file) {
                                                                const fileReader = new FileReader();
                                                                fileReader.onload = function(event) {
                                                                    previewImage.src = event.target.result;
                                                                };
                                                                fileReader.readAsDataURL(file);
                                                            } else {
                                                                // Reset preview jika file dibatalkan
                                                                previewImage.src = "<?= base_url('assets/img/' . $data->image) ?>";
                                                            }
                                                        });
                                                    });
                                                </script>

                                                <div class="form-group">
                                                    <label for="">Nama Sunscreen</label>
                                                    <input type="text" class="form-control"
                                                        value="<?= $data->nama_sunscreen ?>" name="nama_sunscreen"
                                                        required placeholder="Masukkan nama sunscreen" id="">
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-danger" data-toggle="modal"
                                data-target="#exampleModal<?= $data->id ?>">
                                <i class="fas fa-trash"></i>
                            </button>
                            <div class="modal fade" id="exampleModal<?= $data->id ?>" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Hapus <?= $title ?> ?</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Pilih "Hapus" untuk menghapus <?= $title ?> <?= $data->nama_sunscreen ?>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Batal</button>
                                            <a href="<?= base_url('admin/hapus_alternatif/' . $data->id) ?>"
                                                class="btn btn-primary">Hapus</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    function PreviewImage() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("upload").files[0]);

        oFReader.onload = function(oFREvent) {
            document.getElementById("uploadPreview").src = oFREvent.target.result;
        };
    };
</script>
