<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //use SoftDeletes;

    protected $guarded = ['product_id'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';

    protected $casts = [
        'product_color' => 'array',
        'product_size' => 'array',
        'product_price' => 'array',
        'product_specification' => 'array',
        'product_faqs' => 'array'
    ];

    protected $deletedAt = 'product_deleted_at';

    const CREATED_AT = 'product_created_at';
    const UPDATED_AT = 'product_updated_at';

    /** Return Delete Column */
    public function getDeletedAtColumn(){
        return $this->deletedAt;
    }

    // Override newQuery method to always exclude soft-deleted records by default
    public function newQuery($excludeDeleted = true, $withTrashed = false){
        // Exclude soft-deleted records by default
        $query = parent::newQuery($excludeDeleted);

        // Include soft-deleted records if explicitly requested
        if (!$excludeDeleted || $withTrashed) {
            $query->withTrashed();
        }

        return $query;
    }

    public function getColorAttribute()
    {
        return Color::find($this->product_color);
    }

    public function getSizeAttribute()
    {
        return Size::find($this->product_size);
    }
}
