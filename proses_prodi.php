<?php 
include 'koneksi.php';

if ($_GET['proses'] == 'insert') {
    try {
        $stmt = $db->prepare("INSERT INTO prodi (nama_prodi, jenjang_std) VALUES (:nama_prodi, :jenjang)");
        $stmt->bindParam(':nama_prodi', $_POST['nama_prodi']);
        $stmt->bindParam(':jenjang', $_POST['jenjang']);
        $stmt->execute();
        echo "<script>window.location='index.php?p=prodi'</script>";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

if ($_GET['proses'] == 'edit') {
    try {
        $stmt = $db->prepare("UPDATE prodi SET nama_prodi = :nama_prodi, jenjang_std = :jenjang WHERE id = :id");
        $stmt->bindParam(':nama_prodi', $_POST['nama_prodi']);
        $stmt->bindParam(':jenjang', $_POST['jenjang']);
        $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $stmt->execute();
        echo "<script>window.location='index.php?p=prodi'</script>";
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}

if ($_GET['proses'] == 'delete') {
    try {
        $stmt = $db->prepare("DELETE FROM prodi WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();
        header('Location: index.php?p=prodi'); // Redirect
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
}
?>
