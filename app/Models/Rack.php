<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TracksUserActions;


class Rack extends Model
{
    use HasRoles, SoftDeletes, TracksUserActions;
    protected $fillable = ['name', 'location', 'created_by', 'updated_by', 'deleted_by'];
}
