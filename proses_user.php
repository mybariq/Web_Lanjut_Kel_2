<?php
include 'koneksi.php'; // Pastikan koneksi database sudah benar

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';

try {
    if ($proses == 'insert') {
        // Ambil data dari form
        $email = $_POST['email'];
        $password = $_POST['password'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $level_id = $_POST['level_id'];
        $notelp = $_POST['notelp'];
        $alamat = $_POST['alamat'];
        $photo = '';

        // Proses upload file
        if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
            $target_dir = "upload/";
            $photo = basename($_FILES["fileToUpload"]["name"]);
            $target_file = $target_dir . $photo;
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        }

        // Simpan data ke database
        $query = "INSERT INTO user (email, password, level_id, nama_lengkap, notelp, alamat, photo) 
                  VALUES (:email, :password, :level_id, :nama_lengkap, :notelp, :alamat, :photo)";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':email' => $email,
            ':password' => $password,
            ':level_id' => $level_id,
            ':nama_lengkap' => $nama_lengkap,
            ':notelp' => $notelp,
            ':alamat' => $alamat,
            ':photo' => $photo
        ]);
        header("Location: index.php?p=user&aksi=list");
    } elseif ($proses == 'edit') {
        // Ambil data dari form
        $id = $_POST['id'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $level_id = $_POST['level_id'];
        $nama_lengkap = $_POST['nama_lengkap'];
        $notelp = $_POST['notelp'];
        $alamat = $_POST['alamat'];
        $photo = '';

        // Proses upload file jika ada
        if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['error'] == 0) {
            $target_dir = "upload/";
            $photo = basename($_FILES["fileToUpload"]["name"]);
            $target_file = $target_dir . $photo;
            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
        }

        // Update data ke database
        if ($photo) {
            $query = "UPDATE user SET email=:email, password=:password, level_id=:level_id, 
                      nama_lengkap=:nama_lengkap, notelp=:notelp, alamat=:alamat, photo=:photo WHERE id=:id";
            $params = [
                ':email' => $email,
                ':password' => $password,
                ':level_id' => $level_id,
                ':nama_lengkap' => $nama_lengkap,
                ':notelp' => $notelp,
                ':alamat' => $alamat,
                ':photo' => $photo,
                ':id' => $id
            ];
        } else {
            $query = "UPDATE user SET email=:email, password=:password, level_id=:level_id, 
                      nama_lengkap=:nama_lengkap, notelp=:notelp, alamat=:alamat WHERE id=:id";
            $params = [
                ':email' => $email,
                ':password' => $password,
                ':level_id' => $level_id,
                ':nama_lengkap' => $nama_lengkap,
                ':notelp' => $notelp,
                ':alamat' => $alamat,
                ':id' => $id
            ];
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        header("Location: index.php?p=user&aksi=list");
    } elseif ($proses == 'delete') {
        $id = $_GET['id'];
        $file = isset($_GET['file']) ? $_GET['file'] : '';

        // Hapus file jika ada
        if ($file) {
            unlink("upload/" . $file);
        }

        // Hapus data dari database
        $query = "DELETE FROM user WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->execute([':id' => $id]);
        header("Location: index.php?p=user&aksi=list");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
