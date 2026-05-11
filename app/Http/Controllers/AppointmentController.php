<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Liste des rendez-vous
     */
    public function index(Request $request)
    {
        if (!Auth::guard('pharmacien')->check()) {
            return redirect()->route('logout');
        }

        /**
         * Pharmacie connectée
         */
        $pharmacy = Pharmacy::where('id_pharmacy', Auth::guard('pharmacien')->user()->pharmacy_id)
            ->first();

        if (!$pharmacy) {

            return redirect()
                ->back()
                ->with('error', 'Pharmacie introuvable.');
        }

        $search = $request->search;
        $status = $request->status;
        $date = $request->date;

        /**
         * Liste RDV
         */
        $appointments = Appointment::query()

            ->leftJoin(
                'vaccines',
                'appointments.vaccine_id',
                '=',
                'vaccines.id_vaccine'
            )

            ->leftJoin(
                'users_pharma',
                'appointments.user_id',
                '=',
                'users_pharma.id_user'
            )

            ->where(
                'appointments.pharmacy_id',
                $pharmacy->id_pharmacy
            )

            ->select(
                'appointments.*',

                'vaccines.name as vaccine_name',

                DB::raw("
                    CONCAT(
                        users_pharma.first_name,
                        ' ',
                        users_pharma.last_name
                    ) as user_fullname
                "),
            )

            /**
             * Recherche
             */
            ->when($search, function ($query) use ($search) {

                $query->where(function ($q) use ($search) {

                    $q->where(
                        'appointments.reference',
                        'LIKE',
                        "%{$search}%"
                    )

                        ->orWhere(
                            'appointments.patient_name',
                            'LIKE',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'appointments.patient_phone',
                            'LIKE',
                            "%{$search}%"
                        )

                        ->orWhere(
                            'vaccines.name',
                            'LIKE',
                            "%{$search}%"
                        );
                });
            })

            /**
             * Filtre statut
             */
            ->when($status, function ($query) use ($status) {

                $query->where(
                    'appointments.status',
                    $status
                );
            })

            /**
             * Filtre date
             */
            ->when($date, function ($query) use ($date) {

                $query->whereDate(
                    'appointments.appointment_date',
                    $date
                );
            })

            /**
             * Tri
             */
            ->orderBy(
                'appointments.appointment_date',
                'DESC'
            )

            ->paginate(15)

            ->withQueryString();

        /**
         * Stats
         */
        $stats = [

            'total' => Appointment::where(
                'pharmacy_id',
                $pharmacy->id_pharmacy
            )->count(),

            'pending' => Appointment::where(
                'pharmacy_id',
                $pharmacy->id_pharmacy
            )
                ->where('status', 'pending')
                ->count(),

            'confirmed' => Appointment::where(
                'pharmacy_id',
                $pharmacy->id_pharmacy
            )
                ->where('status', 'confirmed')
                ->count(),

            'completed' => Appointment::where(
                'pharmacy_id',
                $pharmacy->id_pharmacy
            )
                ->where('status', 'completed')
                ->count(),
        ];

        return view(
            'appointments.index',
            compact(
                'appointments',
                'stats'
            )
        );
    }

    /**
     * Confirmer RDV
     */
    public function confirm($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {

            return redirect()
                ->back()
                ->with('error', 'Rendez-vous introuvable.');
        }

        $appointment->update([

            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Rendez-vous confirmé.'
            );
    }

    /**
     * Annuler RDV
     */
    public function cancel($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {

            return redirect()
                ->back()
                ->with('error', 'Rendez-vous introuvable.');
        }

        $appointment->update([

            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Rendez-vous annulé.'
            );
    }

    /**
     * Terminer RDV
     */
    public function complete($id)
    {
        $appointment = Appointment::find($id);

        if (!$appointment) {

            return redirect()
                ->back()
                ->with('error', 'Rendez-vous introuvable.');
        }

        $appointment->update([
            'status' => 'completed',
        ]);

        return redirect()
            ->back()
            ->with(
                'success',
                'Rendez-vous terminé.'
            );
    }
}
