<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusGuru extends Model
{
    use HasFactory;

    protected $table = 'status_gurus';
    protected $fillable = ['guru_id', 'status', 'tanggal', 'keterangan', 'ruang_kelas'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
