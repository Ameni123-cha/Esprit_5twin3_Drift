<?php
$basePath = __DIR__;
require $basePath . '/vendor/autoload.php';

$entities = [
    ['name' => 'Product', 'route' => 'products', 'ctrl' => 'ProductAdminController', 'fields' => ['name', 'category', 'status']],
    ['name' => 'EnvironmentalFootprint', 'route' => 'environmental-footprints', 'ctrl' => 'EnvironmentalFootprintController', 'fields' => ['co2_emissions', 'ai_score']],
    ['name' => 'Producer', 'route' => 'producers', 'ctrl' => 'ProducerController', 'fields' => ['company_name', 'farming_method']],
    ['name' => 'Transformer', 'route' => 'transformers', 'ctrl' => 'TransformerController', 'fields' => ['company_name', 'transformation_type']],
    ['name' => 'Distributor', 'route' => 'distributors', 'ctrl' => 'DistributorController', 'fields' => ['company_name', 'distributor_type']],
    ['name' => 'SupplyChainTrace', 'route' => 'supply-chain-traces', 'ctrl' => 'SupplyChainTraceController', 'fields' => ['current_stage', 'status']],
    ['name' => 'Certificate', 'route' => 'certificates', 'ctrl' => 'CertificateController', 'fields' => ['certificate_type', 'status']],
    ['name' => 'Review', 'route' => 'reviews', 'ctrl' => 'ReviewController', 'fields' => ['rating', 'title']],
    ['name' => 'Alert', 'route' => 'alerts', 'ctrl' => 'AlertController', 'fields' => ['alert_type', 'severity']],
    ['name' => 'AIAnalysis', 'route' => 'ai-analyses', 'ctrl' => 'AIAnalysisController', 'fields' => ['analysis_type', 'greenwashing_score']],
    ['name' => 'Consumer', 'route' => 'consumers', 'ctrl' => 'ConsumerController', 'fields' => ['sustainability_level', 'budget_range']],
    ['name' => 'PersonalRating', 'route' => 'personal-ratings', 'ctrl' => 'PersonalRatingController', 'fields' => ['personalized_score', 'reason']],
];

foreach ($entities as $e) {
    // 1. UPDATE MODEL FOR MASS ASSIGNMENT
    $modelPath = $basePath . '/app/Models/' . $e['name'] . '.php';
    if(file_exists($modelPath)) {
        $modelCode = file_get_contents($modelPath);
        if(strpos($modelCode, 'protected $guarded = []') === false && strpos($modelCode, 'protected $fillable') === false) {
            $replacement = "use Illuminate\Database\Eloquent\Model;\n\nclass {$e['name']} extends Model\n{\n    protected \$guarded = [];\n";
            $modelCode = str_replace("use Illuminate\Database\Eloquent\Model;\n\nclass {$e['name']} extends Model\n{\n", $replacement, $modelCode);
            file_put_contents($modelPath, $modelCode);
        }
    }

    $viewDir = $basePath . '/resources/views/admin/' . $e['route'];
    
    // GENERATE FORMS
    $createInputs = "";
    $editInputs = "";
    foreach($e['fields'] as $f) {
        $label = ucfirst(str_replace('_', ' ', $f));
        $createInputs .= <<<HTML
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{$label}</label>
            <input type="text" name="{$f}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
HTML;
        $editInputs .= <<<HTML
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">{$label}</label>
            <input type="text" name="{$f}" value="{{ \$item->{$f} ?? '' }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
HTML;
    }

    // CREATE VIEW
    $createHtml = <<<HTML
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Ajouter {$e['name']}</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <form method="POST" action="{{ route('admin.{$e['route']}.store') }}">
                @csrf
                {$createInputs}
                <div class="flex gap-4">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
                    <a href="{{ route('admin.{$e['route']}.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Annuler</a>
                </div>
            </form>
        </div>
    </div></div>
</x-app-layout>
HTML;
    file_put_contents($viewDir . '/create.blade.php', $createHtml);

    // EDIT VIEW
    $editHtml = <<<HTML
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifier {$e['name']}</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <form method="POST" action="{{ route('admin.{$e['route']}.update', \$item->id) }}">
                @csrf @method('PUT')
                {$editInputs}
                <div class="flex gap-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Mettre à jour</button>
                    <a href="{{ route('admin.{$e['route']}.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Annuler</a>
                </div>
            </form>
        </div>
    </div></div>
</x-app-layout>
HTML;
    file_put_contents($viewDir . '/edit.blade.php', $editHtml);
}

echo "CRUD Forms fully generated!";
