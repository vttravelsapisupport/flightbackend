<?php

namespace App\Http\Controllers;

use Exception;

use App\Models\RestorePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MaintananceController extends Controller
{
    public $db;
    public $prc;
    public $fn;

    public function __construct()
    {
        $this->middleware('permission: maintanance-module', ['only' => ['index']]);        
        
        $this->prc = "call prc_opt_";
        $this->fn  = "select fn_opt_";
    }

    public function ManageRefunds(Request $request)
    {
        $bill_no = $request->bill_no;
        $data    = $this->ExSP("get_airticket_refunds_by_billNo('$bill_no')");

        return view('maintanance.manage-refunds.index', compact('data'));
    }

    public function ManageRefundDetails(Request $request, $id, $ticket_id, $created_at)
    {
        $created_at                   = DATE("Y-m-d H:i:s", $created_at);
        $data                         = [];
        $data['AirTicketRefundID']    = $id;
        $data['AccountTransactions']  = $this->ExSP("account_transactions_by_ticketId('$ticket_id', '$created_at')");
        $data['SupplierTransactions'] = $this->ExSP("supplier_transactions_by_ticketId('$ticket_id', '$created_at')");
        $data['AgentDetails']         = $this->ExSP("agent_details_by_ticketId('$ticket_id')");
        $data['PurchaseEntryDetails'] = $this->ExSP("get_purchase_entry_details_by_ticketId('$ticket_id')");

        return view('maintanance.manage-refunds.manage-refund-details', compact('data'));
    }

    public function ManageAgentBalanceCalculations(Request $request)
    {
        $data                        = [];
        $data['FinancialYears']      = $this->ExSP("get_financial_years()");
        $data['AgentCodes']          = $this->ExSP("get_all_agents()");
        $data['BalanceCalculations'] = [];

        if (isset($request->fys_id)) {
            $fys_id          = $request->fys_id;
            $financial_years = $this->ExSP("get_financial_year_details($fys_id)");
            $fys_start       = date('Y-m-d', strtotime($request->start_date));      //'2021-04-01'; //date('Y', strtotime($financial_years[0]->financial_year_start)) . '-04-01';
            $fys_end         = date('Y-m-d', strtotime($request->end_date));        //'2023-06-30'; //$request->end_date;
        }
        $agent_codes = $request->agent_codes;

        if (!empty($agent_codes)) {
            foreach ($agent_codes as $val) {
                $balance_calculation = $this->ExSP("get_agent_balance_calculations_for_given_range('$val', $request->fys_id, '$fys_start', '$fys_end')");

                if (isset($request->fys_id)) {
                    $data['BalanceCalculations'][$val] = $balance_calculation;
                }
            }
        }
        return view('maintanance.manage-agent-balance-calculations.index', compact('data'));
    }

    public function UpdateAgentBalance()
    {
        set_time_limit(0);

        $financial_years = $this->GetFinancialYears();
        $agents          = $this->ExSP("get_all_agents()");
        $query           = [];

        /*foreach ($financial_years as $fy_id => $range) {
            $start = $range['start'];
            $end   = $range['end'];

            foreach ($agents as $ag) {                       
                $transactions     = $this->ExSP("get_agent_balance_calculations_for_given_range('$ag->code', $fy_id, '$start', '$end')");     
                $query[$ag->code] = $this->InsertIntoAtMod($transactions); 
            }  
        }*/
        foreach ($agents as $ag) {
            $transactions     = $this->ExSP("get_agent_balance_calculations_for_given_range('$ag->code', 3, '2021-04-01', '2023-06-30')");
            $query[$ag->code] = $this->InsertIntoAtMod($transactions);
        }
        echo '<pre>';
        print_r($query);
        die;
    }

    public function UpdateAT()
    {
        $this->ExSP("update_account_transaction_table()");
    }

    public function GetFinancialYears()
    {
        $data = [
            '1' => [
                'start' => '2021-04-01',
                'end'   => '2022-06-30'
            ],
            '2' => [
                'start' => '2022-04-01',
                'end'   => '2023-06-30'
            ],
            '3' => [
                'start' => '2023-04-01',
                'end'   => '2024-06-30'
            ]
        ];
        return $data;
    }

    public function InsertIntoAtMod($transactions)
    {
        if (!empty($transactions)) {
            $sql    = "";
            $sql    = "INSERT INTO account_transaction_updated (at_id, agent_id, reference_no, type, ticket_id, amount, balance, owner_id, status, payment_mode, exp_date, created_at, updated_at) ";
            $values = [];

            foreach ($transactions as $transaction) {
                //$transaction->ClosingBalance = ($transaction->ClosingBalance == '') ? 0 : $transaction->ClosingBalance;
                $values[$transaction->TransactionID] = "(
                    '$transaction->TransactionID',
                    '$transaction->AgentID',
                    '$transaction->RefNo',
                    '$transaction->Type',
                    '$transaction->TicketID',
                    '$transaction->Amount',
                    '$transaction->ClosingBalance',                    
                    '$transaction->OwnerID',
                    '$transaction->Status',
                    '$transaction->PaymentMode',
                    '$transaction->ExpDate',
                    '$transaction->CreatedAT',
                    '$transaction->UpdatedAT'
                )";
            }
            $val  = implode(',', $values);

            if ($val != '') {
                $sql .=  " VALUES $val;";
                DB::statement($sql);
                return $sql;
            }
            return "";
        }
        return "";
    }

    public function PrepareAtModInsertables($transactions)
    {
        $data = [];
        foreach ($transactions as $transaction) {
            $data[] = [
                'at_id'        => $transaction->TransactionID,
                'agent_id'     => $transaction->AgentID,
                'reference_no' => $transaction->RefNo,
                'type'         => $transaction->Type,
                'ticket_id'    => $transaction->TicketID,
                'amount'       => $transaction->Amount,
                'balance'      => $transaction->ClosingBalance,
                'remarks'      => $transaction->Remarks,
                'owner_id'     => $transaction->OwnerID,
                'status'       => $transaction->Status,
                'payment_mode' => $transaction->PaymentMode,
                'exp_date'     => $transaction->ExpDate,
                'created_at'   => $transaction->CreatedAT,
                'updated_at'   => $transaction->UpdatedAT,
                'deleted_at'   => $transaction->DeletedAT
            ];
        }
        return $data;
    }

    public function ManageSupplierBalanceCalculations(Request $request)
    {
        $data                   = [];
        $data['FinancialYears'] = $this->ExSP("get_financial_years()");
        $data['Suppliers']      = $this->ExSP("get_all_suppliers()");

        if (isset($request->fys_id)) {
            $fys_id          = $request->fys_id + 1;
            $financial_years = $this->ExSP("get_financial_year_details($fys_id)");
            $fys_start       = date('Y-m-d', strtotime($financial_years[0]->financial_year_end . ' +1 day'));
            $fys_end         = $request->end_date;
        }
        $supplier_ids = $request->supplier_ids;

        if (!empty($supplier_ids)) {
            foreach ($supplier_ids as $val) {
                if (isset($request->fys_id)) {
                    $data['BalanceCalculations'][$val] = $this->ExSP("get_supplier_balance_calculations_for_given_range('$val', $request->fys_id, '$fys_start', '$fys_end')");
                }
            }
        }

        return view('maintanance.manage-supplier-balance-calculations.index', compact('data'));
    }

    public function ManageAccountTransactions()
    {
        return view('maintanance.manage-account-transactions.index');
    }

    public function ManageDuplicateBookingDetails(Request $request)
    {
        $data                   = [];
        $data['BookingDetails'] = $this->ExSP("get_duplicates_in_book_ticket_details_for_given_billno('$request->bill_no')");

        return view('maintanance.manage-duplicate-booking-details.index', compact('data'));
    }

    public function ManageInfantReport(Request $request)
    {
        $data                 = [];
        $data['InfantReport'] = $this->ExSP("get_infant_report_for_given_range('$request->start_date', '$request->end_date', '$request->bill_no')");

        return view('maintanance.manage-infant-report.index', compact('data'));
    }

    public function ManagePnrHistory(Request $request)
    {
        $data               = [];
        $data['PnrHistory'] = $this->ExSP("get_pnr_history('$request->start_date', '$request->end_date')");

        return view('maintanance.manage-pnr-history.index', compact('data'));
    }

    public function GetRestorePoints(Request $request)
    {
        $data = [];

        if (isset($request->start_date) && isset($request->end_date)) {
            $start                 = date('Y-m-d', strtotime($request->start_date));
            $end                   = date('Y-m-d', strtotime($request->end_date));
            $data['RestorePoints'] = $this->ExSP("get_restore_points('$start', '$end')");
        }
        return view('maintanance.manage-restore-points.index', compact('data'));
    }

    public function AjaxRunRestoreOperation(Request $request)
    {
        $last_used = DB::statement("UPDATE restore_points SET last_used_by = " . Auth::id() . ", last_used_at = NOW() WHERE id IN($request->ids)") ? 1 : 0;
        $roleback  = DB::unprepared($request->statement) ? 1 : 0;

        return ($last_used == 1 && $roleback == 1) ? 1 : 0;
    }

    public function AjaxDeleteRefunds(Request $request)
    {
        $mode    = $request->mode;                        // Delete mode
        $type    = 'fix_duplicate_refund';                // Type of operation
        $comment = 'Duplicate Refund History';            // Comment for the operation
        $atr_id  = $request->atr_id;                      // Air Ticket Refund ID
        $at_ids  = explode(',', $request->at_ids);        // Account Transaction IDS
        $st_ids  = explode(',', $request->st_ids);        // Supplier Transaction IDS
        $data    = [
            'atr_id'     => $atr_id,
            'at_ids'     => $at_ids,
            'st_ids'     => $st_ids,
            'comment'    => $comment,
            'created_by' => Auth::id()
        ];

        # Create restore point
        $res = $this->CreateRollBackStatement($type, $data);

        if ($res) {
            # Fix duplicate account transactions
            try {
                foreach ($at_ids as $id) {
                    $this->ExSP("fix_duplicate_account_transactions($id)");
                }
            } catch (Exception $e) {
                Log::error('Error fixing duplicate supplier transactions: ' . $e->getMessage());
            }

            # Fix duplicate supplier transactions
            try {
                foreach ($st_ids as $id) {
                    $this->ExSP("fix_duplicate_supplier_transactions($id)");
                }
            } catch (Exception $e) {
                Log::error('Error fixing duplicate supplier transactions: ' . $e->getMessage());
            }

            # Fix purchase entries
            try {
                $this->ExSP("fix_purchase_entries($atr_id)");
            } catch (Exception $e) {
                Log::error('Error fixing purchase entries: ' . $e->getMessage());
            }

            # Fix air ticket refund
            if ($mode == 'all') {
                try {
                    $this->ExSP("fix_duplicate_air_ticket_refunds($atr_id)");
                } catch (Exception $e) {
                    Log::error('Error fixing air ticket refund: ' . $e->getMessage());
                }
            }
            return 1;
        }
        return 0;
    }

    public function AjaxDeleteRestorePoints(Request $request)
    {
        return DB::statement("UPDATE restore_points SET status = '0' WHERE id IN($request->ids)") ? 1 : 0;
    }

    public function CreateRollBackStatement($type, $data)
    {
        switch ($type) {
            case ('fix_duplicate_refund'):
                $res = $this->CreateRollBackForDuplicateRefundFix($type, $data);
                break;
            default:
                $res = 0;
        }
        return $res;
    }

    public function CreateRollBackForDuplicateRefundFix($type, $data)
    {
        $atr_id            = $data['atr_id'];      // Air Ticket Refund ID
        $at_ids            = $data['at_ids'];      // Account Transaction IDS
        $st_ids            = $data['st_ids'];      // Supplier Transaction IDS 
        $comment           = $data['comment'];     // Comment for the operation 
        $created_by        = $data['created_by'];  // Restore point created by
        $resp              = 0;                    // Response variable         
        $air_ticket_refund = DB::select("SELECT agent_id, book_ticket_id FROM air_ticket_refunds WHERE id = $atr_id");
        $agent_id          = !empty($air_ticket_refund) ? $air_ticket_refund[0]->agent_id : 0;
        $ticket_id         = !empty($air_ticket_refund) ? $air_ticket_refund[0]->book_ticket_id : 0;
        $agent_details     = DB::select("SELECT opening_balance FROM agents WHERE id = $agent_id");
        $statement         = "";

        if (!empty($agent_details)) {
            $agent_opening_balance  = $agent_details[0]->opening_balance;
            $statement             .= isset($agent_details[0]->opening_balance) ? "UPDATE agents SET opening_balance = $agent_opening_balance WHERE id = $agent_id;" : "";
        }
        $statement .= ($atr_id > 0) ? "UPDATE air_ticket_refunds SET deleted_at = NULL WHERE id = $atr_id;" : "";

        if (!empty($at_ids)) {
            foreach ($at_ids as $account_transaction_id) {
                if ($account_transaction_id != '') {
                    $statement .= "UPDATE account_transaction SET deleted_at = NULL WHERE id = $account_transaction_id;";
                }
            }
        }

        if (!empty($st_ids)) {
            foreach ($st_ids as $supplier_transaction_id) {
                if ($supplier_transaction_id != '') {
                    $statement .= "UPDATE supplier_transactions SET deleted_at = NULL WHERE id = $supplier_transaction_id;";
                }
            }
        }

        if ($supplier_transaction_id != '') {
            $supplier_transaction = DB::select("SELECT supplier_id FROM supplier_transactions WHERE id = $supplier_transaction_id");
            $supplier_id          = !empty($supplier_transaction) ? $supplier_transaction[0]->supplier_id : 0;

            if ($supplier_id != '') {
                $owner = DB::select("SELECT opening_balance FROM owners WHERE id=$supplier_id");

                if (isset($owner[0]->opening_balance)) {
                    $statement .= "UPDATE owners SET opening_balance = " . $owner[0]->opening_balance . " WHERE id = $supplier_id;";
                }
            }
        }

        if ($ticket_id > 0) {
            $book_ticket                 = DB::select("SELECT purchase_entry_id FROM book_tickets WHERE id = $ticket_id");
            $purchase_entry_id           = !empty($book_ticket) ? $book_ticket[0]->purchase_entry_id : 0;
            $purchase_entry              = DB::select("SELECT available, sold FROM purchase_entries WHERE id = $ticket_id");
            $purchase_entries_available  = !empty($purchase_entry) ? $purchase_entry[0]->available : 0;
            $purchase_entries_sold       = !empty($purchase_entry) ? $purchase_entry[0]->sold : 0;
            $statement                  .= "UPDATE purchase_entries SET available = $purchase_entries_available, sold = $purchase_entries_sold WHERE id = $purchase_entry_id;";
        }

        if ($statement != '') {
            $restore_point_data = [
                'comment'    => $comment,
                'type'       => $type,
                'created_by' => $created_by,
                'statement'  => base64_encode(gzcompress($statement))
            ];
            $resp = RestorePoint::create($restore_point_data) ? 1 : 0;
        }
        return $resp;
    }

    public function CalculateClosingBalance()
    {
        //$AgentCodes = ['AID0362','AID0432','AID0453','AID0572','AID0585'];        
        $AgentCodes = [
            'AID0362',
            'AID0432',
            'AID0453',
            'AID0572',
            'AID0585',
            'AID0822',
            'AID12089',
            'AID12591',
            'AID1293',
            'AID13441',
            'AID1495',
            'AID1697',
            'AID18057',
            'AID19418',
            'AID2534',
            'AID2580',
            'AID3163',
            'AID4271',
            'AID4491',
            'AID4512',
            'AID4925',
            'AID6898',
            'AID7441',
            'AID8826',
            'AID8950',
            'AID0104',
            'AID0118',
            'AID0267',
            'AID0285',
            'AID0291',
            'AID0306',
            'AID0307',
            'AID0361',
            'AID0366',
            'AID0397',
            'AID0427',
            'AID0429',
            'AID0440',
            'AID0441',
            'AID0446',
            'AID0469',
            'AID0485',
            'AID0499',
            'AID0518',
            'AID0540',
            'AID0542',
            'AID0543',
            'AID0553',
            'AID0566',
            'AID0579',
            'AID0603',
            'AID0604',
            'AID0619',
            'AID0626',
            'AID0636',
            'AID0648',
            'AID0665',
            'AID0686',
            'AID0713',
            'AID0725',
            'AID0832',
            'AID0864',
            'AID0868',
            'AID0894',
            'AID0902',
            'AID0939',
            'AID0946',
            'AID0970',
            'AID0981',
            'AID10368',
            'AID1066',
            'AID1077',
            'AID1078',
            'AID11412',
            'AID1150',
            'AID1163',
            'AID1166',
            'AID11688',
            'AID1174',
            'AID1209',
            'AID1238',
            'AID1306',
            'AID1341',
            'AID13528',
            'AID1454',
            'AID1455',
            'AID1456',
            'AID1464',
            'AID14643',
            'AID1555',
            'AID1605',
            'AID1672',
            'AID1729',
            'AID1735',
            'AID1741',
            'AID1801',
            'AID1821',
            'AID1876',
            'AID1899',
            'AID1904',
            'AID1990',
            'AID2023',
            'AID21277',
            'AID2135',
            'AID22683',
            'AID2303',
            'AID2409',
            'AID24447',
            'AID2543',
            'Aid2604',
            'AID2677',
            'AID2689',
            'AID2788',
            'AID3082',
            'AID3469',
            'AID3563',
            'AID3609',
            'AID3891',
            'AID3929',
            'AID4368',
            'AID4430',
            'AID4553',
            'AID5241',
            'AID5916',
            'AID6377',
            'AID6631',
            'AID8056',
            'AID8078',
            'AID8143',
            'AID8736',
            'AID9251',
            'AID9540',
            'AID9679',
            'Aid5438',
            'AID0863',
            'AID0913',
            'AID1043',
            'AID11146',
            'AID11227',
            'AID11944',
            'AID1231',
            'AID1255',
            'AID1262',
            'AID1276',
            'AID1384',
            'AID1385',
            'AID1386',
            'AID1389',
            'AID1395',
            'AID1402',
            'AID1403',
            'AID1410',
            'AID1416',
            'AID1425',
            'AID1431',
            'AID1444',
            'AID1452',
            'AID1461',
            'AID1463',
            'AID1466',
            'AID1476',
            'AID1477',
            'AID1486',
            'AID1489',
            'AID1494',
            'AID1503',
            'AID1507',
            'AID1546',
            'AID1557',
            'AID1561',
            'AID1570',
            'AID1591',
            'AID1619',
            'AID1621',
            'AID1632',
            'AID1652',
            'AID1699',
            'AID1738',
            'AID1767',
            'AID1770',
            'AID1810',
            'AID1817',
            'AID1823',
            'Aid1856',
            'AID1936',
            'AID2136',
            'AID2274',
            'AID2289',
            'AID2400',
            'AID2481',
            'AID2535',
            'AID2603',
            'AID2675',
            'AID2871',
            'AID2872',
            'AID2986',
            'AID3001',
            'AID3323',
            'AID3377',
            'AID3401',
            'AID3529',
            'AID3810',
            'AID4247',
            'AID4422',
            'AID4680',
            'AID4801',
            'AID5010',
            'AID5086',
            'AID5106',
            'AID5154',
            'AID5208',
            'AID5951',
            'AID6282',
            'AID6438',
            'AID6598',
            'AID6727',
            'AID6864',
            'AID7740',
            'AID7813',
            'AID7954',
            'AID8082',
            'AID9692',
            'AID0129',
            'AID1880',
            'AID5824',
            'AID0252',
            'AID1547',
            'AID1239',
            'AID12428',
            'AID1257',
            'AID14313',
            'AID16829',
            'AID2419',
            'AID2428',
            'AID2453',
            'AID2523',
            'AID4077',
            'AID4452',
            'AID5610',
            'AID5659',
            'AID6414',
            'AID7074',
            'AID7381',
            'AID9999',
            'AID15595',
            'AID16464',
            'AID19195',
            'AID5108',
            'AID5279',
            'AID6640',
            'AID6957',
            'AID7337',
            'AID8116',
            'AID9725',
            'AID14797',
            'AID15303',
            'AID1997',
            'AID3446',
            'AID5857',
            'AID6918',
            'AID8789',
            'AID2802',
            'AID21509',
            'AID0247',
            'AID0423',
            'AID0555',
            'AID0618',
            'AID0833',
            'AID0937',
            'AID1056',
            'AID14694',
            'AID16770',
            'AID19933',
            'AID2226',
            'AID2273',
            'AID2720',
            'AID2736',
            'AID3183',
            'AID3206',
            'AID3273',
            'AID3538',
            'AID4131',
            'AID4360',
            'AID5866',
            'AID6120',
            'AID7124',
            'AID7350',
            'AID8178',
            'AID8921',
            'AID9102',
            'AID0009',
            'AID0019',
            'AID0026',
            'AID0028',
            'AID0031',
            'AID0037',
            'AID0044',
            'AID0045',
            'AID0057',
            'AID0062',
            'AID0074',
            'AID0082',
            'AID0083',
            'AID0093',
            'AID0094',
            'AID0107',
            'AID0112',
            'AID0125',
            'AID0126',
            'AID0134',
            'AID0168',
            'AID0193',
            'AID0212',
            'AID0220',
            'AID0224',
            'AID0228',
            'AID0234',
            'AID0261',
            'AID0264',
            'AID0280',
            'AID0283',
            'AID0286',
            'AID0295',
            'AID0297',
            'AID0365',
            'AID0380',
            'AID0403',
            'AID0430',
            'AID0472',
            'AID0590',
            'AID0594',
            'AID0598',
            'AID0629',
            'AID0732',
            'AID0781',
            'AID0789',
            'AID0805',
            'AID0808',
            'AID0828',
            'AID0835',
            'AID0885',
            'AID0900',
            'AID0935',
            'AID1010',
            'AID1053',
            'AID1130',
            'AID1167',
            'AID1175',
            'AID1224',
            'AID1285',
            'AID1303',
            'AID1357',
            'AID1422',
            'AID1726',
            'AID1850',
            'AID1885',
            'AID1892',
            'AID2148',
            'AID2279',
            'AID3003',
            'AID3733',
            'AID4300',
            'Aid6672',
            'AID6735',
            'SKY TRAVELS (KAMAL UNCLE)',
            'AID0004',
            'AID0022',
            'AID0030',
            'AID0041',
            'AID0042',
            'AID0047',
            'AID0061',
            'AID0065',
            'AID0070',
            'AID0075',
            'AID0078',
            'AID0088',
            'AID0090',
            'AID0091',
            'AID0116',
            'AID0130',
            'AID0138',
            'AID0141',
            'AID0148',
            'AID0194',
            'AID0199',
            'AID0205',
            'AID0225',
            'AID0246',
            'AID0273',
            'AID0278',
            'AID0347',
            'AID0356',
            'AID0508',
            'AID0617',
            'AID0752',
            'AID0876',
            'AID0994',
            'AID11847',
            'AID16978',
            'AID1727',
            'AID2521',
            'AID3495',
            'DID0003',
            'AID16512',
            'AID5006',
            'AID9033',
            'AID14610',
            'AID3409',
            'AID4113',
            'AID6075',
            'AID6561',
            'AID6642',
            'AID6763',
            'AID8302',
            'AID8664',
            'AID13807',
            'AID13817',
            'AID14536',
            'AID14802',
            'AID20986',
            'AID2349',
            'AID2725',
            'AID2783',
            'AID2827',
            'AID10016',
            'AID11381',
            'AID11555',
            'AID12795',
            'AID14422',
            'AID14524',
            'AID1760',
            'AID1768',
            'AID19350',
            'AID1941',
            'AID2022',
            'AID2036',
            'AID2203',
            'AID2627',
            'AID2635',
            'AID2692',
            'AID2899',
            'AID2927',
            'AID3596',
            'AID3777',
            'AID3784',
            'AID3811',
            'AID3890',
            'AID4115',
            'AID4373',
            'AID5087',
            'AID5400',
            'AID5403',
            'AID7470',
            'AID7973',
            'AID8132',
            'AID8597',
            'AID8618',
            'AID8730',
            'AID9163',
            'AID9240',
            'AID9935',
            'AID9967',
            'DID0002',
            'DID0037',
            'DID0037',
            'AID0926',
            'Aid14680',
            'AID16996',
            'AID17442',
            'AID17668',
            'AID18084',
            'AID3157',
            'AID3288',
            'AID6447',
            'AID7365',
            'AID0797',
            'AID0812',
            'AID10589',
            'AID10845',
            'AID14645',
            'AID16906',
            'AID18281',
            'AID18845',
            'AID19225',
            'AID19445',
            'AID20773',
            'AID24030',
            'AID3334',
            'AID3410',
            'Aid3680',
            'AID4809',
            'AID5238',
            'AID5925',
            'AID6021',
            'AID6206',
            'AID6529',
            'AID6954',
            'AID7058',
            'AID7093',
            'AID7277',
            'AID7832',
            'AID8516',
            'AID8525',
            'AID8852',
            'AID9763',
            'AID0524',
            'AID0657',
            'AID0673',
            'AID0811',
            'AID10211',
            'AID1358',
            'AID1518',
            'AID16150',
            'AID1670',
            'AID16796',
            'AID19344',
            'AID2623',
            'AID2835',
            'AID2961',
            'AID4399',
            'AID4903',
            'AID4948',
            'AID6544',
            'AID7132',
            'AID8548',
            'DID0007',
            'AID0174',
            'AID0483',
            'AID10127',
            'AID11630',
            'AID11932',
            'AID12060',
            'AID14296',
            'AID14437',
            'AID16659',
            'AID1912',
            'AID21063',
            'AID4335',
            'AID5220',
            'AID5393',
            'AID5413',
            'AID6660',
            'AID7035',
            'AID7156',
            'AID7331',
            'AID7354',
            'AID7456',
            'AID7597',
            'AID7611',
            'AID7618',
            'AID7658',
            'AID7703',
            'AID7758',
            'AID8049',
            'AID8570',
            'AID8819',
            'AID9120',
            'AID9427',
            'AID9665',
            'AID0169',
            'AID0176',
            'AID0223',
            'AID0304',
            'AID0327',
            'AID0410',
            'AID0448',
            'AID0479',
            'AID0504',
            'AID0602',
            'AID0758',
            'AID0862',
            'AID10585',
            'AID1173',
            'AID1182',
            'AID1228',
            'AID12520',
            'AID13620',
            'AID13741',
            'AID1379',
            'AID14463',
            'AID14930',
            'AID15142',
            'AID15627',
            'AID16903',
            'AID1761',
            'AID1778',
            'AID19466',
            'AID21665',
            'AID21833',
            'AID2577',
            'AID2596',
            'AID2673',
            'AID2797',
            'AID2867',
            'AID3228',
            'AID3385',
            'AID3640',
            'AID3691',
            'AID4696',
            'AID5188',
            'AID5190',
            'AID5293',
            'AID5444',
            'AID5624',
            'AID5690',
            'AID5858',
            'AID6337',
            'AID6599',
            'AID6657',
            'AID6725',
            'AID6978',
            'AID7070',
            'AID8733',
            'AID9138',
            'AID9189',
            'AID9487',
            'TRAVELMASTER',
            'DID0032',
            'AID11311',
            'AID13479',
            'AID14389',
            'AID15140',
            'AID17377',
            'AID19618',
            'AID19808',
            'AID19960',
            'AID2695',
            'AID3713',
            'AID5284',
            'AID6359',
            'AID8641',
            'AID0332',
            'AID2120',
            'AID21755',
            'AID3853',
            'AID5390',
            'AID7728',
            'AID8767',
            'DID0031',
            'AID0034',
            'AID0340',
            'AID0606',
            'AID0896',
            'AID0181',
            'AID2248',
            'AID0007',
            'AID0005',
            'AID0015',
            'AID0020',
            'AID0036',
            'AID0048',
            'AID0049',
            'AID0101',
            'AID0363',
            'AID0487',
            'AID0689',
            'AID0740',
            'AID0791',
            'AID0857',
            'AID0891',
            'AID0914',
            'AID1088',
            'AID1155',
            'AID1288',
            'AID1298',
            'AID1374',
            'AID1397',
            'AID1484',
            'AID1551',
            'AID1613',
            'AID1614',
            'AID1728',
            'AID3461',
            'AID0052',
            'AID0053',
            'AID0339',
            'AID0010',
            'AID0017',
            'AID0056',
            'AID0165',
            'AID1084',
            'AID1867',
            'AID0121',
            'AID0133',
            'AID0874',
            'AID1115',
            'AID1858',
            'AID2586',
            'AID0240',
            'AID1122',
            'AID2144',
            'AID0077',
            'AID0079',
            'AID0105',
            'AID0237',
            'AID0798',
            'AID4985',
            'AID0086',
            'AID0424',
            'AID0014',
            'AID0171',
            'AID1586',
            'AID1781',
            'AID1809',
            'AID0021',
            'AID0032',
            'AID0097',
            'AID0360',
            'AID0447',
            'AID0463',
            'AID1625',
            'AID0055',
            'AID0058',
            'AID1029',
            'AID3794',
            'AID0084',
            'AID0818',
            'AID0925',
            'AID20780',
            'AID2304',
            'AID24072',
            'AID4665',
            'AID4832',
            'AID7675',
            'AID8288',
            'AID0747',
            'AID0843',
            'AID10041',
            'AID10045',
            'AID10236',
            'AID10299',
            'AID10369',
            'AID10435',
            'AID10685',
            'AID10687',
            'AID10738',
            'AID10745',
            'AID10833',
            'AID11045',
            'AID11210',
            'AID11348',
            'AID11386',
            'AID11746',
            'AID11747',
            'AID11785',
            'AID11849',
            'AID11904',
            'AID11926',
            'AID12088',
            'AID12307',
            'AID13156',
            'AID13256',
            'AID13723',
            'AID13793',
            'AID14545',
            'AID15151',
            'AID15569',
            'AID15990',
            'AID17567',
            'AID19254',
            'AID2185',
            'AID2190',
            'AID2676',
            'AID4071',
            'AID4607',
            'AID5276',
            'AID5445',
            'AID5537',
            'AID6861',
            'AID8684',
            'AID8693',
            'AID8972',
            'AID9241',
            'AID9259',
            'AID9273',
            'AID9420',
            'AID9574',
            'AID9647',
            'AID9662',
            'AID9927',
            'AID0938',
            'AID2244',
            'AID3593',
            'AID6461',
            'AID7145',
            'AID9110'
        ];

        /*$AgentCodes = [
            'AID0362',
            'AID0432',
            'AID0453',
            'AID0572',
            'AID0585',
            'AID0822'
        ];*/

        sort($AgentCodes);

        $data       = [];

        foreach ($AgentCodes as $val) {
            $data['ClosingBalance'][$val] = $this->ExSP("get_last_balance_of_all_users_till_today('$val')");
        }
        return view('maintanance.manage-closing-balance.index', compact('data'));
    }

    public function ResyncAccountTransactions(Request $request)
    {
        set_time_limit(0);

        $data                   = [];        
        $data['FinancialYears'] = $this->ExSP("get_financial_years()");        

        array_push($data['FinancialYears'], (object)[
            'id'       => 0,
            'name'     => 'Real Time (Alpha Testing)',
            'isActive' => 0       
        ]);

        sort($data['FinancialYears']);


        if(isset($request->start_date)){
            $ex_start          = hrtime(true);
            $start             = $request->start_date.' 00:00:00';
            $counts            = $this->ExSP("get_syncable_agent_and_at_count('$request->start_date')");
            $agent_count       = $counts[0]->agent_count;
            $transaction_count = $counts[0]->transaction_count;
            $sql               = "sync_all_agent_balances($request->fys_id, '$start')";
                        
            if($this->ExSP($sql)){       
                $ex_end   = hrtime(true);
                $seconds  = ($ex_end - $ex_start) / 1e+9; // Convert nanoseconds to seconds
                $duration = $this->secondsToTime($seconds);           
                $request->session()->flash("success", "Total $transaction_count Transactions For $agent_count Agents Has Been Synced Successfully In $duration Hours");
            }     
        }     
        $data['BalanceCalculations'] = $this->ExSP("get_agent_closing_balances()");
        $data['ZeroBalances']        = $this->ExSP("get_agent_zero_closing_balances()");       

        return view('maintanance.manage-resync-account-transactions.index', compact('data'));
    }

    public function secondsToTime($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
    
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

    public function ExSP($statement)
    {
        return DB::select($this->prc . "$statement");
    }

    public function ExFN($statement)
    {
        return DB::select($this->fn . "$statement");
    }


    public function updateSengridSettings() {
        $apiKey = getenv('SENDGRID_API_KEY');
        $sg = new \SendGrid($apiKey);
        $ip_addresses = [
            "20.244.57.154", "20.204.220.120", "20.244.59.217", "20.244.61.204",
            "20.219.230.141", "20.219.229.38", "20.244.58.210", "20.244.58.237",
            "20.244.59.7", "20.219.226.122", "20.244.59.121", "20.244.59.129",
            "20.244.59.139", "20.244.59.191", "20.244.59.247", "20.244.60.17",
            "20.219.230.75", "20.244.60.209", "20.244.60.222", "20.244.60.245",
            "20.244.61.30", "20.244.61.87", "20.244.61.149", "20.244.61.151",
            "20.244.57.154", "20.204.220.120", "20.244.59.217", "20.244.61.204",
            "20.219.230.141", "20.219.229.38", "20.219.230.69", "20.219.231.183",
            "20.244.58.37", "20.244.61.213", "20.219.230.89", "20.244.62.10", "20.192.170.15"
        ];
        
        $ips = array_map(function ($ip) {
            return ["ip" => $ip];
        }, $ip_addresses);
        
        $request = ["ips" => $ips];
        
        $json_request_body = json_encode($request, JSON_PRETTY_PRINT);
        
        $request_body = json_decode($json_request_body);

        try {
            $response = $sg->client->access_settings()->whitelist()->post($request_body);
            print $response->statusCode() . "\n";
            print_r($response->headers());
            print $response->body() . "\n";
        } catch (Exception $ex) {
            echo 'Caught exception: '.  $ex->getMessage();
        }
    }
}
