<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SlaPolicy extends Model {
    protected $fillable = ['organization_id','priority','response_time_minutes','resolution_time_minutes'];
    public function organization() { return $this->belongsTo(Organization::class); }
}
