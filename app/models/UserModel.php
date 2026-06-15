<?php

namespace App\Models;

use App\Core\Database;
use PDO;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function login($username, $password)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            if ($user['status'] !== 'active') {
                return ['success' => false, 'message' => 'Akun Anda dinonaktifkan.'];
            }
            return ['success' => true, 'user' => $user];
        }
        return ['success' => false, 'message' => 'Username atau password salah.'];
    }

    public function getAll()
    {
        $sql = "SELECT u.*, c.name as church_name 
                FROM users u 
                LEFT JOIN churchs c ON u.id_church = c.id
                ORDER BY u.name ASC";
        $stmt = $this->db->query($sql);
        $users = $stmt->fetchAll();

        // Attach roles for each user
        foreach ($users as &$user) {
            $user['roles'] = $this->getRoles($user['id']);
        }
        return $users;
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT u.*, c.name as church_name 
                                    FROM users u 
                                    LEFT JOIN churchs c ON u.id_church = c.id 
                                    WHERE u.id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        if ($user) {
            $user['roles'] = $this->getRoles($user['id']);
        }
        return $user;
    }

    public function create($data)
    {
        $sql = "INSERT INTO users (id_church, name, username, password, birth_date, phone_number, address, image, gender, status, id_user_created) 
                VALUES (:id_church, :name, :username, :password, :birth_date, :phone_number, :address, :image, :gender, :status, :id_user_created)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_church' => $data['id_church'] ?: null,
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
            'birth_date' => $data['birth_date'] ?: null,
            'phone_number' => $data['phone_number'] ?: null,
            'address' => $data['address'] ?: null,
            'image' => $data['image'] ?: null,
            'gender' => $data['gender'] ?: null,
            'status' => $data['status'] ?: 'active',
            'id_user_created' => $data['id_user_created']
        ]);

        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $sql = "UPDATE users SET 
                    id_church = :id_church,
                    name = :name,
                    username = :username,
                    birth_date = :birth_date,
                    phone_number = :phone_number,
                    address = :address,
                    image = :image,
                    gender = :gender,
                    status = :status,
                    id_user_updated = :id_user_updated
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_church' => $data['id_church'] ?: null,
            'name' => $data['name'],
            'username' => $data['username'],
            'birth_date' => $data['birth_date'] ?: null,
            'phone_number' => $data['phone_number'] ?: null,
            'address' => $data['address'] ?: null,
            'image' => $data['image'],
            'gender' => $data['gender'] ?: null,
            'status' => $data['status'] ?: 'active',
            'id_user_updated' => $data['id_user_updated'],
            'id' => $id
        ]);
    }

    public function changePassword($id, $newPassword)
    {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashed, $id]);
    }

    public function delete($id)
    {
        // First, delete related user roles
        $stmt = $this->db->prepare("DELETE FROM user_role WHERE id_user = ?");
        $stmt->execute([$id]);

        // Then delete the user
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getRoles($userId)
    {
        $stmt = $this->db->prepare("
            SELECT r.* FROM roles r
            JOIN user_role ur ON r.id = ur.id_role
            WHERE ur.id_user = ?
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function updateRoles($userId, $roleIds, $idUserCreated)
    {
        // Delete existing roles
        $stmt = $this->db->prepare("DELETE FROM user_role WHERE id_user = ?");
        $stmt->execute([$userId]);

        // Insert new ones
        if (!empty($roleIds)) {
            $stmt = $this->db->prepare("INSERT INTO user_role (id_user, id_role, id_user_created) VALUES (?, ?, ?)");
            foreach ($roleIds as $roleId) {
                $stmt->execute([$userId, $roleId, $idUserCreated]);
            }
        }
        return true;
    }

    public function getDivisions($userId)
    {
        $stmt = $this->db->prepare("
            SELECT d.*, dm.role as member_role, u.name as coordinator_name
            FROM divisions d
            JOIN division_member dm ON d.id = dm.division_id
            LEFT JOIN users u ON d.coordinator_user_id = u.id
            WHERE dm.user_id = ?
        ");
        // echo "SELECT d.*, dm.role as member_role, u.name as coordinator_name
        //     FROM divisions d
        //     JOIN division_member dm ON d.id = dm.division_id
        //     LEFT JOIN users u ON d.coordinator_user_id = u.id
        //     WHERE dm.user_id = {$userId}
        // ";
        // exit;
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function getBirthdaysToday()
    {
        $stmt = $this->db->prepare("
            SELECT id, name, username, image, birth_date
            FROM users
            WHERE DATE_FORMAT(birth_date, '%m-%d') = DATE_FORMAT(CURDATE(), '%m-%d')
              AND status = 'active'
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
