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
                <div class="d-flex align-items-center justify-content-between flex-column flex-md-row gap-4">
                    <div>
                        <i class="fas fa-user-graduate fa-4x cv-primary mb-3"></i>
                        <h2 class="h4 fw-bold">Bienvenido al portal del alumno</h2>
                        <p class="text-muted mb-2">{{ $phrase }}</p>
                        <p class="small text-muted mb-0">{{ $fact }}</p>
                    </div>
                    <div class="text-start text-md-end">
                        <span class="badge bg-primary rounded-pill px-3 py-2">Dato curioso</span>
                        <p class="mt-3 mb-0 small text-muted" style="max-width: 360px;">
                            Hoy es {{ $today->isoFormat('D [de] MMMM') }}.
                            {{ $special ? $special['subtitle'] : 'Aprovecha el día para descubrir algo nuevo y curioso.' }}
                        </p>
                    </div>
                </div>
                <div class="dashboard-animation-wrapper mt-4 pt-3 border-top">
                    <div class="dashboard-animation {{ $special ? 'dashboard-animation--' . $special['key'] : 'dashboard-animation--default' }}">
                        <div class="dashboard-animation-icon">
                            <i class="fas {{ $special['icon'] ?? 'fa-lightbulb' }}"></i>
                        </div>
                        <div class="dashboard-animation-text">
                            <strong>{{ $special['title'] ?? 'Ritmo de aprendizaje' }}</strong>
                            <span>{{ $special['subtitle'] ?? 'Las fechas especiales añaden color al estudio y la imaginación.' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            @php
                $carouselSlides = [
                    [
                        'title' => $special['title'] ?? 'Hoy brilla tu aprendizaje',
                        'subtitle' => $special['subtitle'] ?? 'Cada fecha es una oportunidad para encender tu curiosidad y avanzar con energía.',
                        'icon' => $special['icon'] ?? 'fa-star',
                        'theme' => $special ? $special['key'] : 'default',
                    ],
                    [
                        'title' => 'Momentos inolvidables',
                        'subtitle' => 'Las fechas especiales también son un motivo para aprender y celebrar juntos.',
                        'icon' => 'fa-calendar-check',
                        'theme' => 'celebrate',
                    ],
                    [
                        'title' => 'Imagina y descubre',
                        'subtitle' => 'Cada día trae una nueva historia, una nueva pregunta y un nuevo descubrimiento.',
                        'icon' => 'fa-magic',
                        'theme' => 'magic',
                    ],
                ];
            @endphp

            <div id="dashboardHeroCarousel" class="carousel slide dashboard-hero-panel" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($carouselSlides as $index => $slide)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }} dashboard-hero-slide dashboard-hero-slide--{{ $slide['theme'] }}">
                            <div class="dashboard-hero-slide-content row align-items-center gy-3">
                                <div class="col-lg-5 order-lg-2">
                                    <div class="dashboard-hero-illustration">
                                        <div class="hero-player">
                                            <div class="player-head"></div>
                                            <div class="player-body"></div>
                                            <div class="player-arm player-arm--left"></div>
                                            <div class="player-arm player-arm--right"></div>
                                            <div class="player-leg player-leg--left"></div>
                                            <div class="player-leg player-leg--right"></div>
                                        </div>
                                        <div class="hero-ball"></div>
                                        <div class="hero-ball-trail"></div>
                                    </div>
                                </div>
                                <div class="col-lg-7 order-lg-1">
                                    <h3 class="mb-2">{{ $slide['title'] }}</h3>
                                    <p class="mb-0">{{ $slide['subtitle'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#dashboardHeroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#dashboardHeroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                </button>
            </div>
        </div>
    </div>
@endsection
