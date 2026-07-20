<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Carbon\Carbon;

class Product extends Model
{
    protected $table = 'products';

    const DEFAULT_IMAGE = 'images/products/placeholder.jpg';

    protected $fillable = [
        'title',
        'body',
        'equipment_category_id',
        'serial_number',
        'is_available',
        'one_day_price',
        'total_income',
        'created_at',
        'updated_at',
        'is_deleted',
    ];

    public function equipment_category(): BelongsTo
    { //relacja products equipment_category
        return $this->belongsTo(Category::class);
    }

    public function reservation(): hasMany
    { //relacja pomiędzy products a reservation
        return $this->hasMany(Reservation::class,'productId','id');
    }

    public function opinions()
    {
        return $this->hasMany(Opinion::class,'productId','id');
    }

    public function getAverageRating(): float
    {
        return round($this->opinions()->avg('scaleValue') ?? 0, 1);
    }

    public function getOpinionsCount(): int
    {
        return $this->opinions()->count();
    }

    public function getMainImageUrl(): string{ //wymagane wykonanie php artisan storage:link w celu działania aplikacji
        $image = "images/products/$this->id/1.avif";

        if(Storage::disk('public')->exists($image)){
            return Storage::url($image);
        }
        else{
            return Storage::url(self::DEFAULT_IMAGE);
        }
    }

    public function getThumbnailUrl(): string
    {
        $image = "images/products/{$this->id}/{$N}_thumb.avif";

        if (Storage::disk('public')->exists($image)) {
            return Storage::url($image);
        }

        return $this->getMainImageUrl(); // fallback do pełnego zdjęcia
    }

    public function getNImageUrl(int $N=1, bool $returnNullString=FALSE): string{
        $image = "images/products/{$this->id}"."/".$N.".avif";

        
        if(Storage::disk('public')->exists($image)){
            return Storage::url($image);
        }
        else if($returnNullString == TRUE){
            return "";
        }
        else{
            return Storage::url(self::DEFAULT_IMAGE);
        }
    }

    public function getImagesUrls(): array{
        $directory = "images/products/".$this->id;

        if(!Storage::disk('public')->exists($directory)){ //zabezpieczenie przed nieistniejącym folderem
            return [];
        }

        $files = Storage::disk('public')->files($directory); // pobranie listy wszystkich zdjęć produktu


        return array_map(function ($filePath){ //przekształcenie ścieżki na link URL
            return Storage::disk('public')->url($filePath);
        },$files);
    }

    public function isReserved(): bool{
        $now = Carbon::now();

        return $this->reservation()
        ->whereIn('statusOfReservation',['pending','confirmed'])
        ->where('startDate','<=',$now)
        ->where('endDate','>=',$now)
        ->exists();
    }

    public function getStatus(): string{
        return $this->isReserved() ? 'Wypożyczony' : 'Dostępny';
    }

    public function getProductCategoryName(): string{
        return $this->equipment_category?->name ?? '';
    }
}
