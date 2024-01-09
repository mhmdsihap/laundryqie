<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laundry Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-4">
        <h1>Cari Status Laundry</h1>
        <form method="post">
            <div class="mb-3">
                <label for="keyword" class="form-label">Search by Name:</label>
                <input type="text" class="form-control" id="keyword" name="keyword" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <?php
        class LaundrySearch
        {
            private $conn;

            public function __construct($servername, $username, $password, $dbname)
            {
                $this->conn = new mysqli($servername, $username, $password, $dbname);

                if ($this->conn->connect_error) {
                    die("Connection failed: " . $this->conn->connect_error);
                }
            }

            public function searchPelanggan($keyword)
            {
                $stmt = $this->conn->prepare("SELECT nama_pelanggan, alamat_pelanggan, telp_pelanggan, status, status_bayar, tgl_pembayaran FROM pelanggan JOIN transaksi on pelanggan.id_pelanggan = transaksi.id_pelanggan WHERE nama_pelanggan = ?");
                $stmt->bind_param("s", $keyword);

                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $this->displayResults($result);
                } else {
                    echo '<p class="mt-4">Data laporan tidak ditemukan.</p>';
                }

                $stmt->close();
            }

            private function displayResults($result)
            {
                echo '<div class="mt-4">';
                echo '<h5>Informasi Pelanggan</h5>';
                echo '<div class="table-responsive">';
                echo '<table class="table table-striped table-bordered">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th>Nama</th>';
                echo '<th>Alamat</th>';
                echo '<th>Status</th>';
                echo '<th>Status Bayar</th>';
                echo '<th>Nomor Telepon</th>';
                echo '<th>Tanggal Pembayaran</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['nama_pelanggan'] . '</td>';
                    echo '<td>' . $row['alamat_pelanggan'] . '</td>';
                    echo '<td>' . $row['status'] . '</td>';
                    echo '<td>' . $row['status_bayar'] . '</td>';
                    echo '<td>' . $row['telp_pelanggan'] . '</td>';
                    echo '<td>' . $row['tgl_pembayaran'] . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
                echo '</div>';
            }

            public function closeConnection()
            {
                $this->conn->close();
            }
        }

        if (isset($_POST['keyword'])) {
            $keyword = $_POST['keyword'];

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "db_laundry";

            $laundrySearch = new LaundrySearch($servername, $username, $password, $dbname);
            $laundrySearch->searchPelanggan($keyword);
            $laundrySearch->closeConnection();
        }
        ?>

        <a href="index.php" class="btn btn-sm btn-primary"><span class="fas fa-arrow-left mr-2"></span>Back</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
