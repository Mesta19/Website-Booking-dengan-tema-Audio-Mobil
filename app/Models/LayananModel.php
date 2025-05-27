<?php namespace App\Models;

use CodeIgniter\Model;

class LayananModel extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    protected $useAutoIncrement = false; // Important
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_layanan', 'nama_layanan', 'harga', 'is_delete_layanan',
        'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $beforeInsert = ['generateId'];

    protected function generateId(array $data)
    {
        $prefix = 'LYN';
        $lastId = $this->selectMax($this->primaryKey)->like($this->primaryKey, $prefix . '%', 'after')->first();

        $newNumber = 0;
        if ($lastId && isset($lastId[$this->primaryKey])) {
            $numericPart = (int) substr($lastId[$this->primaryKey], strlen($prefix));
            $newNumber = $numericPart + 1;
        }

        $data['data'][$this->primaryKey] = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        return $data;
    }

    public function scopeNotDeleted($query)
    {
        return $query->where('is_delete_layanan', '0');
    }
}