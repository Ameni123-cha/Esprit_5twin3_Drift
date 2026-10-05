@extends('layouts.frontend')

@section('title', 'Producteurs')

@section('content')
    <section class="py-5 bg-light">
        <div class="container py-5">
            <span class="badge rounded-pill bg-success-subtle text-success-emphasis px-3 py-2 text-uppercase fw-semibold">Acteurs</span>
            <h1 class="mt-4 display-5 fw-bold text-dark">Producteurs engagés</h1>
            <p class="lead text-secondary mt-3 mb-0">Des fermes locales et responsables, avec une démarche de qualité et de transparence.</p>
        </div>
    </section>

    <section class="container py-5">
        @if($producers->isEmpty())
            <x-empty-state title="Aucun producteur" message="Les profils des producteurs apparaîtront ici dès leur création." />
        @else
            <div class="row g-4">
                @foreach($producers as $producer)
                    <div class="col-md-6 col-xl-4">
                        <x-producer-card :producer="$producer" />
                    </div>
                @endforeach
            </div>
            <div class="mt-5 d-flex justify-content-center">
                {{ $producers->links() }}
            </div>
        @endif
    </section>
@endsection
