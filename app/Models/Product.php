<?php
namespace App\Models;
use \App\Models\Category;
use App\Models\Comment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Product extends Model
{
    use HasFactory;

    protected $table = "products";
    protected $primaryKey = "id";

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'price',
        'description',
        'price' ,
       'exp_date',
        'img_url',
        'quantity',
        'category_id',
        'owner_id',
        'views',
    ];
    public $timestamps = true;
    public function discounts(){
        return $this->hasMany(Discount::class,'product_id')->orderBy('date');
    }
public function category(){
    return $this->belongsTo( Category::class,'category_id');
}
    public function user(){
        return $this->belongsTo( User::class,'owner_id');
    }

    public function Comments(){
        return $this->hasMany( Comment::class,'product_id');
    }

    public function Likes(){
        return $this->hasMany( Like::class,'product_id');
    }
}
