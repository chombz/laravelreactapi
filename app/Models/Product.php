<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable =
    [
        'category_id',
        'meta_title',
        'meta_keyword',
        'meta_description',

        'slug',
        'name',
        'small_description',
        'long_description',
        'brand',

        'selling_price',
        'original_price',
        'quantity',
        'image',
        'featured',
        'popular',
        'status',
    ];


    // Define relationship with Category
    protected $with = ['category'];

    public function category()
    {
        // The second argument is the foreign key on the 'products' table.
        // The third is the primary key on the 'categories' table.
        // Laravel can infer these if you follow conventions, but being explicit is fine.
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
