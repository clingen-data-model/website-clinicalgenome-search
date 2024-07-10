<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Uuid;

class PanelActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'ident', 'panel_id', 'activity', 'activity_date'
    ];

    public function __construct(array $attributes = array())
    {
        $this->attributes['ident'] = (string) Uuid::generate(4);
        parent::__construct($attributes);
    }

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }

}
