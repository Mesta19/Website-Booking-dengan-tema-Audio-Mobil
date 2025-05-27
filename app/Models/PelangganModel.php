<?php namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $useAutoIncrement = false; // Important
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_pelanggan', 'nama_pelanggan', 'no_hp',
        'email_pelanggan', 'password_pelanggan', 'is_delete_pelanggan',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['generateId', 'hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function generateId(array $data)
    {
        $prefix = 'PLG';
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
        if (isset($data['data']['password_pelanggan'])) {
            $data['data']['password_pelanggan'] = password_hash($data['data']['password_pelanggan'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}