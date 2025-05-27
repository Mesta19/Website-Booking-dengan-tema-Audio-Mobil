<?php namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'id_booking';
    protected $useAutoIncrement = false; // Important
    protected $returnType = 'array';
    protected $allowedFields = ['id_booking', 'id_pelanggan', 'tanggal_booking', 'total_harga'];
    // No timestamps in your SQL for this table, if needed, add them,
    // set $useTimestamps = true, and add to $allowedFields.

    protected $beforeInsert = ['generateId'];

    protected function generateId(array $data)
    {
        $prefix = 'BOK'; // Defining a prefix for Booking
        $lastId = $this->selectMax($this->primaryKey)->like($this->primaryKey, $prefix . '%', 'after')->first();

        $newNumber = 0;
        if ($lastId && isset($lastId[$this->primaryKey])) {
            $numericPart = (int) substr($lastId[$this->primaryKey], strlen($prefix));
            $newNumber = $numericPart + 1;
        }

        $data['data'][$this->primaryKey] = $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        return $data;
    }

    public function pelanggan()
    {
        return $this->belongsTo(PelangganModel::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function detailBooking()
    {
        return $this->hasMany(DetailBookingModel::class, 'id_booking', 'id_booking');
    }
}