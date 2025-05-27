<?php namespace App\Models;

use CodeIgniter\Model;

class DetailBookingModel extends Model
{
    protected $table = 'detail_booking';
    protected $primaryKey = 'id_detail_booking';
    protected $useAutoIncrement = false; // Important
    protected $returnType = 'array';
    protected $allowedFields = ['id_detail_booking', 'id_booking', 'id_layanan'];
    // No timestamps in your SQL for this table

    protected $beforeInsert = ['generateId'];

    protected function generateId(array $data)
    {
        $prefix = 'DTB'; // Defining a prefix for Detail Booking
        $lastId = $this->selectMax($this->primaryKey)->like($this->primaryKey, $prefix . '%', 'after')->first();

        $newNumber = 0;
        if ($lastId && isset($lastId[$this->primaryKey])) {
            $numericPart = (int) substr($lastId[$this->primaryKey], strlen($prefix));
            $newNumber = $numericPart + 1;
        }

        $data['data'][$this->primaryKey] = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        return $data;
    }

    public function booking()
    {
        return $this->belongsTo(BookingModel::class, 'id_booking', 'id_booking');
    }

    public function layanan()
    {
        return $this->belongsTo(LayananModel::class, 'id_layanan', 'id_layanan');
    }
}