<?php
include 'koneksi.php'; // Pastikan koneksi menggunakan PDO
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'list';

switch ($aksi) {
    case 'list':
?>
<!-- Bagian list tetap -->
<section class="content">
    <div class="">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">DataTable USER</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="index.php?p=user&aksi=input" class="btn btn-success btn-lg">
                            <i class="fas fa-plus-circle"></i> Tambah USER
                        </a>
                    </div>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Nama Lengkap</th>
                                <th>Level</th>
                                <th>No telpon</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $db->prepare("SELECT user.*, level.nama_level 
                                                  FROM user 
                                                  INNER JOIN level ON level.id = user.level_id");
                            $stmt->execute();
                            $no = 1;
                            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <tr>
                                    <td><?= $no ?></td>
                                    <td><?= htmlspecialchars($data['email']) ?></td>
                                    <td><?= htmlspecialchars($data['nama_lengkap']) ?></td>
                                    <td><?= htmlspecialchars($data['nama_level']) ?></td>
                                    <td><?= htmlspecialchars($data['notelp']) ?></td>
                                    <td><?= htmlspecialchars($data['alamat']) ?></td>
                                    <td>
                                        <a href="index.php?p=user&aksi=edit&id=<?= $data['id'] ?>" class="btn btn-success">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="proses_user.php?proses=delete&id=<?= $data['id'] ?>" class="btn btn-danger" 
                                           onclick="return confirm('Yakin dihapus?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                $no++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
break;

case 'input':
?>

<div class="container">
    <div class="row">
        <div class="col-md-5 offset-md">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Input User</h1>
            </div>
            <form action="proses_user.php?proses=insert" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="level_id" class="form-label">Level ID</label>
                    <select name="level_id" class="form-select" id="level_id" required>
                        <option value="">-Pilih level-</option>
                        <?php
                        $stmt = $db->prepare("SELECT * FROM level");
                        $stmt->execute();
                        while ($data_level = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=" . $data_level['id'] . ">" . htmlspecialchars($data_level['nama_level']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama_lengkap" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">No Telp</label>
                    <input type="tel" class="form-control" name="notelp" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" required></textarea>
                </div>
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Photo</label>
                    <div class="col-sm-10">
                        <input type="file" name="fileToUpload" class="form-control" id="file-upload" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
</div>

<?php
break;

// Case edit mirip dengan input, hanya ada pengambilan data berdasarkan ID yang diubah menggunakan PDO
case 'edit':
$stmt = $db->prepare("SELECT * FROM user WHERE id = :id");
$stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
$stmt->execute();
$data_user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!-- Isi form edit di sini dengan data $data_user -->

<?php
break;
}
?>
