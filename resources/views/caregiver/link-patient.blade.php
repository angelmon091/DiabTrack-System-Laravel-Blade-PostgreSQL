@extends('layouts.app')

@section('title', 'DiabTrack - Vincular Paciente')

@section('content')
<main class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-6">
            <div class="diab-card p-4 p-md-5 animate-fade-in">
                <div class="text-center mb-4">
                    <div class="admin-card-icon-wrapper mx-auto bg-diab-warning-light mb-3">
                        <i class="fa-solid fa-link fs-4 text-diab-warning"></i>
                    </div>
                    <h3 class="fw-bold">Vincular Paciente</h3>
                    <p class="text-muted small">Ingresa el código de 6 dígitos que te compartió tu paciente.</p>
                </div>

                <form method="POST" action="{{ route('caregiver.link.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase" for="invite_code">Código de Invitación</label>
                        <input id="invite_code" name="invite_code" type="text" class="form-control diab-input text-center fw-bold fs-4 letter-spacing-1" maxlength="6" placeholder="ABC123" style="text-transform: uppercase;" required />
                        @if($errors->has('invite_code'))
                            <span class="text-danger extra-small mt-1 d-block">{{ $errors->first('invite_code') }}</span>
                        @endif
                    </div>

                    <button type="submit" class="btn-diab-primary w-100">
                        <i class="fa-solid fa-check me-2"></i>Vincular
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
