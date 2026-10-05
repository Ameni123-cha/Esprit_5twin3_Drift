<?php

namespace Database\Seeders;

use App\Models\Alert;
use App\Models\ComplianceCheck;
use App\Models\EnvironmentalClaim;
use App\Models\Product;
use Illuminate\Database\Seeder;

class M5DemoSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('status', 'published')->get();

        if ($products->isEmpty()) {
            return;
        }

        foreach ($products->take(5) as $product) {
            $claim = EnvironmentalClaim::create([
                'product_id' => $product->id,
                'claim_type' => fake()->randomElement(['100% écologique', 'Produit local', 'Sans pesticide', 'Biodégradable', 'Fabrication responsable']),
                'title' => 'Déclaration environnementale '.$product->name,
                'description' => 'Cette déclaration a été formulée à partir des données disponibles sur la production et le conditionnement du produit.',
                'source_document' => 'certificat_fabricant_'.strtolower(str_replace(' ', '_', $product->name)).'.pdf',
                'status' => fake()->randomElement(['pending', 'verified', 'rejected']),
                'confidence_score' => rand(55, 96),
            ]);

            $check = ComplianceCheck::create([
                'environmental_claim_id' => $claim->id,
                'product_id' => $product->id,
                'check_type' => fake()->randomElement(['audit_documentaire', 'inspection_terrain', 'analyse_empreinte', 'vérification_fournisseurs']),
                'status' => fake()->randomElement(['passed', 'failed', 'needs_review', 'pending']),
                'result_summary' => 'Vérification de conformité effectuée à partir des pièces disponibles et des données de suivi de production.',
                'notes' => fake()->sentence(),
                'checked_at' => now()->subDays(rand(1, 60))->toDateString(),
            ]);

            Alert::create([
                'product_id' => $product->id,
                'compliance_check_id' => $check->id,
                'alert_type' => 'environmental_claim',
                'severity' => fake()->randomElement(['low', 'medium', 'high']),
                'title' => 'Contrôle de conformité M5',
                'description' => 'Des éléments de la déclaration environnementale doivent faire l’objet d’un contrôle complémentaire.',
                'detected_at' => now()->subDays(rand(1, 30)),
                'status' => fake()->randomElement(['open', 'investigating', 'resolved']),
                'resolution' => null,
            ]);
        }
    }
}
