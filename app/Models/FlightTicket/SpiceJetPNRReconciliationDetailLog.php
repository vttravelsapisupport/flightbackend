<?php

namespace App\Models\FlightTicket;

use Illuminate\Database\Eloquent\Model;

class SpiceJetPNRReconciliationDetailLog extends Model
{
    protected  $fillable = [
        'spice_jet_p_n_r_reconciliation_summary_logs_id',
        'passenger_name',
        'pax_type',
        'gender',
    ];
}
