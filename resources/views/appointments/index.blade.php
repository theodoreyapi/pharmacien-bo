@extends('layouts.master', ['title' => 'Rendez-vous'])

@section('content')
    <div class="dashboard-main-body">

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">
                Rendez-vous vaccinations
            </h6>
        </div>

        {{-- Stats --}}
        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-1 gy-4 mb-24">

            <div class="col">
                <div class="card border shadow-none">
                    <div class="card-body">
                        <p>Total</p>
                        <h4>{{ $stats['total'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border shadow-none">
                    <div class="card-body">
                        <p>En attente</p>
                        <h4>{{ $stats['pending'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border shadow-none">
                    <div class="card-body">
                        <p>Confirmés</p>
                        <h4>{{ $stats['confirmed'] }}</h4>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border shadow-none">
                    <div class="card-body">
                        <p>Terminés</p>
                        <h4>{{ $stats['completed'] }}</h4>
                    </div>
                </div>
            </div>

        </div>

        {{-- Table --}}
        <div class="card shadow-none border">

            <div class="card-header">

                <form method="GET">

                    <div class="d-flex flex-wrap gap-2">

                        <input type="text" name="search" class="form-control w-auto" placeholder="Recherche..."
                            value="{{ request('search') }}">

                        <select name="status" class="form-select w-auto">
                            <option value="">Tous statuts</option>

                            <option value="pending">
                                En attente
                            </option>

                            <option value="confirmed">
                                Confirmé
                            </option>

                            <option value="completed">
                                Terminé
                            </option>

                            <option value="cancelled">
                                Annulé
                            </option>
                        </select>

                        <input type="date" name="date" class="form-control w-auto" value="{{ request('date') }}">

                        <button type="submit" class="btn btn-primary">
                            Filtrer
                        </button>

                    </div>

                </form>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table bordered-table mb-0">

                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Patient</th>
                                <th>Vaccin</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($appointments as $rdv)
                                <tr>

                                    <td>
                                        {{ $rdv->reference }}
                                    </td>

                                    <td>

                                        <div>
                                            <span class="fw-semibold d-block">
                                                {{ $rdv->patient_name }}
                                            </span>

                                            <span class="text-secondary-light text-sm">
                                                {{ $rdv->patient_phone }}
                                            </span>
                                        </div>

                                    </td>

                                    <td>
                                        {{ $rdv->vaccine_name ?? '—' }}
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($rdv->appointment_date)->format('d/m/Y') }}
                                    </td>

                                    <td>

                                        @if ($rdv->status == 'pending')
                                            <span class="badge bg-warning-focus text-warning">
                                                En attente
                                            </span>
                                        @elseif($rdv->status == 'confirmed')
                                            <span class="badge bg-primary-focus text-primary">
                                                Confirmé
                                            </span>
                                        @elseif($rdv->status == 'completed')
                                            <span class="badge bg-success-focus text-success">
                                                Terminé
                                            </span>
                                        @else
                                            <span class="badge bg-danger-focus text-danger">
                                                Annulé
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        <div class="d-flex justify-content-center gap-2">

                                            @if ($rdv->status == 'pending')
                                                <form action="{{ route('appointments.confirm', $rdv->id_appointment) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button class="btn btn-sm btn-success">
                                                        Confirmer
                                                    </button>
                                                </form>

                                                <form action="{{ route('appointments.cancel', $rdv->id_appointment) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button class="btn btn-sm btn-danger">
                                                        Annuler
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($rdv->status == 'confirmed')
                                                <form action="{{ route('appointments.complete', $rdv->id_appointment) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <button class="btn btn-sm btn-primary">
                                                        Terminer
                                                    </button>
                                                </form>
                                            @endif

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        Aucun rendez-vous trouvé.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @if ($appointments->hasPages())
                <div class="card-footer">
                    {{ $appointments->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>

    </div>
@endsection
