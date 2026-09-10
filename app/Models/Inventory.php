<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Inventory extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'boolean', 'data' => 'array', 'specs' => 'array'];
}
