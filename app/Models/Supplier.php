<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';
    protected $primaryKey = 'supplier_id';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'description',
        'logo',
    ];

    // Một nhà cung cấp có thể cung cấp nhiều sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id', 'supplier_id');
    }

    public static function searchByName($search)
    {
        return self::where('name', 'like', '%' . $search . '%');
    }

    public function handleLogoUpload($file)
    {
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);
        return $filename;
    }

    public function deleteOldLogo()
    {
        if (
            $this->logo && $this->logo !== 'placeholder.png'
            && file_exists(public_path('uploads/' . $this->logo))
        ) {
            unlink(public_path('uploads/' . $this->logo));
        }
    }

    public static function createSupplier(array $validated)
    {
        if (isset($validated['logo'])) {
            $validated['logo'] = (new self)->handleLogoUpload($validated['logo']);
        } else {
            $validated['logo'] = 'placeholder.png';
        }

        return self::create($validated);
    }

    public static function updateSupplier($id, array $validated)
    {
        $supplier = self::findOrFail($id);

        if (isset($validated['logo'])) {
            $supplier->deleteOldLogo();
            $validated['logo'] = $supplier->handleLogoUpload($validated['logo']);
        }

        $supplier->update($validated);
        return $supplier;
    }

    public static function deleteSupplier($id)
    {
        $supplier = self::findOrFail($id);

        $supplier->delete();
    }

}
