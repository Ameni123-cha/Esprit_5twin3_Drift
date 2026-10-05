<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('NutriTrace Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Bienvenue -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-2">Bienvenue sur NutriTrace !</h3>
                    <p>Gérez l'ensemble de la chaîne d'approvisionnement et environnementale depuis ce tableau de bord.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Membre 1 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-green-500">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-green-700">M1 : Produits & Empreinte</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">📦 Gestion des Produits</a></li>
                            <li><a href="{{ route('admin.environmental-footprints.index') }}" class="text-blue-600 hover:underline">🌱 Empreintes Carbone</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Membre 2 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-yellow-500">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-yellow-700">M2 : Producteurs & Usines</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('admin.producers.index') }}" class="text-blue-600 hover:underline">🚜 Producteurs Agricoles</a></li>
                            <li><a href="{{ route('admin.transformers.index') }}" class="text-blue-600 hover:underline">🏭 Transformateurs</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Membre 3 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-blue-500">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-blue-700">M3 : Logistique & Distributeurs</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('admin.distributors.index') }}" class="text-blue-600 hover:underline">🏪 Distributeurs</a></li>
                            <li><a href="{{ route('admin.supply-chain-traces.index') }}" class="text-blue-600 hover:underline">🚚 Traçabilité Logistique</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Membre 4 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-indigo-700">M4 : Certifications & Avis</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('admin.certificates.index') }}" class="text-blue-600 hover:underline">📜 Certifications</a></li>
                            <li><a href="{{ route('admin.reviews.index') }}" class="text-blue-600 hover:underline">⭐ Avis Consommateurs</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Membre 5 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-red-500">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-red-700">M5 : Alertes & IA</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('admin.alerts.index') }}" class="text-blue-600 hover:underline">🚨 Alertes Greenwashing</a></li>
                            <li><a href="{{ route('admin.ai-analyses.index') }}" class="text-blue-600 hover:underline">🤖 Analyses IA</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Membre 6 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-purple-500">
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-purple-700">M6 : Consommateurs</h3>
                        <ul class="space-y-2">
                            <li><a href="{{ route('admin.consumers.index') }}" class="text-blue-600 hover:underline">👥 Profils Consommateurs</a></li>
                            <li><a href="{{ route('admin.personal-ratings.index') }}" class="text-blue-600 hover:underline">🎯 Recommandations</a></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
