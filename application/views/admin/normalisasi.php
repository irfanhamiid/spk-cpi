<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Alternatif</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alternatif</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($penilaian as $nama_alternatif => $nilai): ?>
                <tr>
                    <td><?= $nama_alternatif ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Penilaian Alternatif</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alternatif</th>
                    <?php foreach ($kriteria as $k): ?>
                    <th><?= $k ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($penilaian as $nama_alternatif => $nilai): ?>
                <tr>
                    <td><?= $nama_alternatif ?></td>
                    <?php foreach ($kriteria as $k): ?>
                    <td><?= isset($nilai[$k]) ? $nilai[$k] : '-' ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Normalisasi Matriks</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alternatif</th>
                    <?php foreach ($kriteria as $k): ?>
                    <th><?= $k ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($normalisasi as $id_alternatif => $data): ?>
            <tr>
                <td><?= $data['nama_sunscreen']; ?></td>
                <?php foreach ($data['normalisasi'] as $nilai): ?>
                    <td><?= $nilai; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Normalisasi × Bobot</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Alternatif</th>
                    <?php foreach ($kriteria as $k): ?>
                    <th><?= $k ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($normalisasi_bobot as $id_alternatif => $data): ?>
            <tr>
                <td><?= $data['nama_sunscreen']; ?></td>
                <?php foreach ($data['normalisasi_bobot'] as $nilai): ?>
                    <td><?= $nilai; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
        </table>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Nilai Akhir CPI dan Ranking</h6>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;">Rangking</th>
					<th>Foto</th>
                    <th>Nama Alternatif</th>
                    <th>CPI</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1;foreach ($cpi as $nama_alternatif => $data): ?>
                <tr>
					<td><?= $no++ ?></td>
                    <td><img src="<?= base_url('assets/img/'.$data['image']) ?>" alt="<?= $data['nama_sunscreen'] ?>" style="max-width: 100px;"></td>
                    <td><?= $data['nama_sunscreen'] ?></td>
                    <td><?= round($data['total'],2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
