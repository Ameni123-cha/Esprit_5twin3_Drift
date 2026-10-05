<?php
/**
 * One-shot generator for Trace Verte CRUD scaffolding.
 * Run: php build_traceverte.php
 */
$base = __DIR__;

function writeFile(string $path, string $content): void
{
    $dir = dirname($path);
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    file_put_contents($path, $content);
    echo "Wrote $path\n";
}

// ========== FACTORIES ==========
$factories = [
    'Producer' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Producer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Producer> */
class ProducerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'company_name' => fake()->company().' Ferme',
            'location' => fake()->city().', France',
            'latitude' => fake()->latitude(41, 51),
            'longitude' => fake()->longitude(-5, 8),
            'farming_method' => fake()->randomElement(['biologique', 'raisonnée', 'permaculture', 'conventionnelle']),
            'crop_types' => fake()->randomElements(['blé', 'maïs', 'légumes', 'fruits', 'légumineuses'], rand(1, 3)),
            'production_capacity' => fake()->numberBetween(10, 500).' tonnes/an',
            'certifications' => fake()->randomElements(['AB', 'HVE', 'GlobalGAP'], rand(0, 2)),
        ];
    }

    public function withUser(): static
    {
        return $this->state(fn () => [
            'user_id' => User::factory()->state(['role' => 'producer']),
        ]);
    }
}
PHP,
    'Transformer' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Transformer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Transformer> */
class TransformerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'company_name' => fake()->company().' Transformation',
            'location' => fake()->city().', France',
            'latitude' => fake()->latitude(41, 51),
            'longitude' => fake()->longitude(-5, 8),
            'transformation_type' => fake()->randomElement(['meunerie', 'conserve', 'laiterie', 'huile', 'boulangerie']),
            'process_description' => fake()->paragraph(),
            'production_capacity' => fake()->numberBetween(50, 2000).' tonnes/an',
            'certifications' => fake()->randomElements(['IFS', 'BRC', 'ISO22000'], rand(0, 2)),
        ];
    }

    public function withUser(): static
    {
        return $this->state(fn () => [
            'user_id' => User::factory()->state(['role' => 'transformer']),
        ]);
    }
}
PHP,
    'Distributor' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Distributor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Distributor> */
class DistributorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'company_name' => fake()->company().' Distribution',
            'distributor_type' => fake()->randomElement(['grossiste', 'retail', 'coopérative', 'e-commerce']),
            'location' => fake()->city().', France',
            'latitude' => fake()->latitude(41, 51),
            'longitude' => fake()->longitude(-5, 8),
            'coverage_area' => fake()->randomElement(['régional', 'national', 'europe']),
        ];
    }

    public function withUser(): static
    {
        return $this->state(fn () => [
            'user_id' => User::factory()->state(['role' => 'distributor']),
        ]);
    }
}
PHP,
    'Product' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Producer;
use App\Models\Product;
use App\Models\Transformer;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Product> */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'barcode' => fake()->unique()->ean13(),
            'sku' => strtoupper(fake()->unique()->bothify('TV-####-??')),
            'category' => fake()->randomElement(['fruits', 'légumes', 'céréales', 'produits laitiers', 'boissons', 'épicerie']),
            'origin' => fake()->city().', France',
            'ingredients' => fake()->sentence(8),
            'producer_id' => Producer::factory(),
            'transformer_id' => fake()->boolean(70) ? Transformer::factory() : null,
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => ['status' => 'published']);
    }
}
PHP,
    'EnvironmentalFootprint' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\EnvironmentalFootprint;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<EnvironmentalFootprint> */
class EnvironmentalFootprintFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'co2_emissions' => fake()->randomFloat(2, 0.1, 25),
            'water_usage' => fake()->randomFloat(2, 10, 5000),
            'land_usage' => fake()->randomFloat(2, 0.01, 12),
            'ai_score' => fake()->numberBetween(10, 95),
        ];
    }
}
PHP,
    'SupplyChainTrace' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Distributor;
use App\Models\Product;
use App\Models\SupplyChainTrace;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<SupplyChainTrace> */
class SupplyChainTraceFactory extends Factory
{
    public function definition(): array
    {
        $stages = ['production', 'transformation', 'stockage', 'transport', 'distribution', 'vente'];
        $current = fake()->randomElement($stages);

        return [
            'product_id' => Product::factory(),
            'distributor_id' => Distributor::factory(),
            'current_stage' => $current,
            'current_location_lat' => fake()->latitude(41, 51),
            'current_location_lon' => fake()->longitude(-5, 8),
            'path_history' => [
                ['stage' => 'production', 'location' => fake()->city(), 'at' => now()->subDays(10)->toIso8601String()],
                ['stage' => $current, 'location' => fake()->city(), 'at' => now()->subDays(2)->toIso8601String()],
            ],
            'status' => fake()->randomElement(['in_transit', 'delivered', 'delayed', 'completed']),
            'total_distance_km' => fake()->randomFloat(2, 5, 1200),
        ];
    }
}
PHP,
    'Certificate' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Certificate> */
class CertificateFactory extends Factory
{
    public function definition(): array
    {
        $issue = fake()->dateTimeBetween('-2 years', '-1 month');

        return [
            'product_id' => Product::factory(),
            'certificate_type' => fake()->randomElement(['Bio AB', 'Fairtrade', 'Label Rouge', 'AOP', 'Rainforest Alliance']),
            'issuer' => fake()->company(),
            'certificate_number' => strtoupper(fake()->bothify('CERT-####-????')),
            'issue_date' => $issue,
            'expiry_date' => (clone $issue)->modify('+2 years'),
            'status' => fake()->randomElement(['pending', 'verified', 'expired', 'rejected']),
        ];
    }
}
PHP,
    'Review' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Review> */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'user_id' => User::factory()->state(['role' => 'consumer']),
            'rating' => fake()->numberBetween(1, 5),
            'title' => fake()->sentence(4),
            'comment' => fake()->paragraph(),
            'verified_purchase' => fake()->boolean(40),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
PHP,
    'Alert' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Alert;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Alert> */
class AlertFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'alert_type' => fake()->randomElement(['greenwashing', 'label_incoherent', 'origine_douteuse', 'empreinte_élevée']),
            'severity' => fake()->randomElement(['low', 'medium', 'high', 'critical']),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(),
            'detected_at' => fake()->dateTimeBetween('-6 months', 'now'),
            'status' => fake()->randomElement(['open', 'investigating', 'resolved', 'dismissed']),
            'resolution' => null,
        ];
    }
}
PHP,
    'AIAnalysis' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\AIAnalysis;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AIAnalysis> */
class AIAnalysisFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'analysis_type' => fake()->randomElement(['greenwashing_scan', 'credibility_check', 'label_consistency']),
            'greenwashing_score' => fake()->numberBetween(0, 100),
            'credibility_rating' => fake()->randomElement(['faible', 'moyenne', 'élevée']),
            'ai_summary' => '[Démonstration] '.fake()->paragraph(),
            'model_used' => fake()->randomElement(['demo-rules-v1', 'demo-heuristic-v2']),
            'is_demo' => true,
        ];
    }
}
PHP,
    'Consumer' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Consumer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Consumer> */
class ConsumerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'consumer']),
            'preferences' => fake()->randomElements(['local', 'bio', 'faible_co2', 'équitable', 'saison'], rand(1, 3)),
            'sustainability_level' => fake()->randomElement(['débutant', 'intermédiaire', 'engagé', 'expert']),
            'dietary_restrictions' => fake()->randomElements(['végétarien', 'végétalien', 'sans gluten', 'halal'], rand(0, 2)),
            'allergies' => fake()->randomElements(['gluten', 'lactose', 'arachides', 'fruits à coque'], rand(0, 2)),
            'budget_range' => fake()->randomElement(['éco', 'moyen', 'premium']),
        ];
    }
}
PHP,
    'PersonalRating' => <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Consumer;
use App\Models\PersonalRating;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<PersonalRating> */
class PersonalRatingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'consumer_id' => Consumer::factory(),
            'product_id' => Product::factory(),
            'personalized_score' => fake()->numberBetween(20, 98),
            'reason' => fake()->sentence(),
            'recommendation_reason' => 'Correspond à vos préférences enregistrées (démonstration).',
        ];
    }
}
PHP,
];

foreach ($factories as $name => $code) {
    writeFile("$base/database/factories/{$name}Factory.php", $code);
}

echo "Factories done.\n";
