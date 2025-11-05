<?php

namespace Database\Factories;

use App\Models\Spec; // Đảm bảo đã import model
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Spec>
 */
class SpecFactory extends Factory
{
    /**
     * Chỉ định model mà factory này dùng.
     */
    protected $model = Spec::class;

    /**
     * Định nghĩa trạng thái mặc định của model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Seeder của chúng ta sẽ ghi đè 'name', 'value', và 'product_id',
        // nên phần definition này chỉ cần để placeholder.
        return [
            'name' => $this->faker->word(),
            'value' => $this->faker->word(),
            // Chúng ta không cần 'product_id' ở đây
            // vì Seeder (SpecSeeder.php) sẽ cung cấp nó.
        ];
    }
}