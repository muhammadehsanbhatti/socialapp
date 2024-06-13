<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CsvImportData extends Model
{
    use HasFactory;
    protected $fillable = ['csv_filename', 'csv_header', 'csv_data', 'table_name'];
}
