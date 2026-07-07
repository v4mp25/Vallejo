@extends('layouts.alumno')

@section('title', 'Mi panel')
@section('page_title', 'Mi panel')
@section('page_subtitle', 'Tu espacio de aprendizaje siempre activo')

@section('content')
    @php
        $phrases = [
            'Cada paso pequeño es un gran avance en tu viaje hacia el conocimiento.',
            'Aprender hoy es encender el motor que te llevará a tus metas mañana.',
            'La curiosidad te hace preguntar, la constancia te permite responder.',
            'El talento se descubre estudiando, la disciplina se construye a diario.',
            'La mente se expande cuando la alimentas con nuevas ideas cada día.',
            'Tu mejor versión se construye con paciencia, esfuerzo y asombro.',
        ];

        $facts = [
            'En la antigüedad, la gente pensaba que el cometa Halley era un mensajero divino con noticias importantes.',
            'Los antiguos egipcios creían que el sol era un dios que navegaba cada noche por el inframundo.',
            'Las medusas no tienen cerebro, pero pueden coordinar movimientos y reaccionar al entorno.',
            'El fuego fue considerado en muchas culturas como un regalo sagrado que encendía la mente y el alma.',
            'Durante siglos, la gente pensó que las constelaciones influían en el carácter y el destino humano.',
            'En la mitología nórdica, el arcoíris era un puente brillante entre dioses y humanos.',
        ];

        $today = \Carbon\Carbon::now();
        $phrase = $phrases[$today->dayOfYear % count($phrases)];
        $fact = $facts[$today->dayOfYear % count($facts)];

        $specialDates = [
            '06-12' => ['key' => 'worldcup', 'title' => 'Días de Mundial', 'subtitle' => 'Las grandes competencias inspiran fuerza de equipo y emoción global.', 'icon' => 'fa-futbol'],
            '31-10' => ['key' => 'halloween', 'title' => 'Noche de misterio', 'subtitle' => 'Las fechas especiales también son una oportunidad para aprender leyendas y simbolismos.', 'icon' => 'fa-ghost'],
            '25-12' => ['key' => 'holiday', 'title' => 'Fiestas brillantes', 'subtitle' => 'Las celebraciones son perfectas para compartir historias, sueños y buenos deseos.', 'icon' => 'fa-gift'],
            '01-01' => ['key' => 'newyear', 'title' => 'Año nuevo', 'subtitle' => 'Cada año trae nuevas metas y nuevas razones para crecer.', 'icon' => 'fa-star'],
        ];

        $dateKey = $today->format('m-d');
        $special = $specialDates[$dateKey] ?? null;
    @endphp

    <div class="row g-4">
        <div class="col-12">
            <div class="cv-card card-body p-5">
                <div class="d-flex align-items-start justify-content-between flex-column flex-md-row gap-4">
                    <div class="flex-grow-1">
                        <i class="fas fa-user-graduate fa-4x cv-primary mb-3"></i>
                        <h2 class="h4 fw-bold">Bienvenido al portal del alumno</h2>
                        <p class="text-muted mb-2">{{ $phrase }}</p>
                    </div>
                    <div class="text-start text-md-end">
                        <span class="badge bg-primary rounded-pill px-3 py-2">Dato curioso</span>
                        <p class="mt-3 mb-1 small text-muted" style="max-width: 360px;">{{ $fact }}</p>
                        <p class="mb-0 text-secondary" style="font-size:.85rem;">Hoy es {{ $today->isoFormat('D [de] MMMM') }}.</p>
                    </div>
                </div>
                <div class="dashboard-scene-wrapper mt-4 pt-4 border-top">
                    <div class="dashboard-scene {{ $special ? 'dashboard-scene--' . $special['key'] : 'dashboard-scene--default' }}">
                        <div class="scene-horizon"></div>
                        <div class="scene-vehicle" aria-hidden="true">
                            <div class="vehicle-body"></div>
                            <div class="vehicle-cab"></div>
                            <div class="vehicle-wheel vehicle-wheel--left"></div>
                            <div class="vehicle-wheel vehicle-wheel--right"></div>
                        </div>
                        <div class="dashboard-scene-copy">
                            <strong>{{ $special['title'] ?? 'Ritmo de aprendizaje' }}</strong>
                            <span>{{ $special['subtitle'] ?? 'Las fechas especiales añaden color al estudio y la imaginación.' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
