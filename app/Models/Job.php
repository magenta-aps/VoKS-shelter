<?php namespace BComeSafe\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends BaseModel
{

    public $timestamps = true;

    protected $fillable = ['queue', 'payload', 'attempts', 'reserved', 'reserved_at', 'available_at'];

    protected $guarded = [];
}
