<?php
session_start();

class Database {
    protected $conn;

    public function __construct() {
        $this->conn = mysqli_connect("localhost", "root", "", "db_laundry");
    }

    public function getConnection() {
        return $this->conn;
    }
}

class UserAuth extends Database {
    public function checkLogin($username, $password) {
        $conn = $this->getConnection();
        $username = mysqli_real_escape_string($conn, $username);
        $password = md5($password);

        $query = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
        $row = mysqli_query($conn, $query);
        $data = mysqli_fetch_assoc($row);
        $cek = mysqli_num_rows($row);

        if ($cek > 0) {
            $this->setSession($data);
        } else {
            $message = 'Username atau password salah!!!';
            header('location:login.php?message=' . $message);
        }
    }

    protected function setSession($data) {
        $_SESSION['role'] = $data['role'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['user_id'] = $data['id_user'];
        $_SESSION['outlet_id'] = $data['outlet_id'];

        $this->redirectBasedOnRole($data['role']);
    }

    protected function redirectBasedOnRole($role) {
        switch ($role) {
            case 'admin':
                header('location:admin');
                break;
            case 'kasir':
                header('location:kasir');
                break;
            case 'owner':
                header('location:owner');
                break;
            default:
                header('location:index.php');
                break;
        }
    }
}

// Example usage:
$userAuth = new UserAuth();
$userAuth->checkLogin($_POST['username'], $_POST['password']);
