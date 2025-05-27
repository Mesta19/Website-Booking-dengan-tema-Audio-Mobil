<?php namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    protected $useAutoIncrement = false; // Important
    protected $returnType = 'array'; // Or 'object' based on your preference
    protected $allowedFields = [
        'id_admin', 'nama_admin', 'username_admin',
        'password_admin', 'akses_level', 'is_delete_admin',
        'created_at', 'updated_at' // Ensure these are fillable if you manage them manually without $useTimestamps
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['generateId', 'hashPassword'];
    protected $beforeUpdate = ['hashPassword']; // Password hashing only on update if needed

    protected function generateId(array $data)
    {
        $prefix = 'ADM';
        $lastId = $this->selectMax($this->primaryKey)->like($this->primaryKey, $prefix . '%', 'after')->first();

        $newNumber = 0;
        if ($lastId && isset($lastId[$this->primaryKey])) {
            $numericPart = (int) substr($lastId[$this->primaryKey], strlen($prefix));
            $newNumber = $numericPart + 1;
        }

        $data['data'][$this->primaryKey] = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        return $data;
    }

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password_admin'])) {
            $data['data']['password_admin'] = password_hash($data['data']['password_admin'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}