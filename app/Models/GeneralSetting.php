<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = ['website_name','email','phone','address','website_url','description','location_url','gst_number','favicon','header_logo','footer_logo'];
}
