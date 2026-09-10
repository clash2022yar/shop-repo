<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Category extends Model
{
    protected $fillable = ['name','slug','parent_id','image','icon','is_active','sort_order'];
    protected $casts = ['is_active' => 'boolean'];
    public function products(): HasMany { return $this->hasMany(Product::class); }
    public function children(){ return $this->hasMany(Category::class,'parent_id'); }
    public function parent(){ return $this->belongsTo(Category::class,'parent_id'); }
    public function getRouteKeyName(){ return 'slug'; }
}
