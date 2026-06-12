<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'email',
        'gender',
        'birth_date',
        'city',
        'commune',
        'height_cm',
        'qr_code',
        'status',
        'active',
        'consent_suivi',
        'consent_whatsapp',
        'consent_sms',
        'consent_reseau',
        'pharmacy_id',
        'created_by',
        'user_id',
    ];

    protected $primaryKey = 'id_patient';

    public function rappels()
    {
        return $this->hasMany(Rappel::class, 'patient_id', 'id_patient');
    }

    public function pathologies()
    {
        return $this->belongsToMany(
            Pathologies::class,
            'patient_pathologies',
            'patient_id',
            'pathologie_id'
        );
    }

    public function mesures()
    {
        return $this->hasMany(Mesure::class, 'patient_id', 'id_patient');
    }
}
