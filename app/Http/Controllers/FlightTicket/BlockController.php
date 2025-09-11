<?php

namespace App\Http\Controllers\FlightTicket;

use App\Http\Controllers\Controller;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\BlockTicket;
use App\PurchaseEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'purchase_id' => 'required|integer'
        ]);
        $ticket_info = PurchaseEntry::find($request->purchase_id);
        if (!($ticket_info->quantity >= 1)) {
            $request->session()->flash('error', 'No Inventory Available');
            return redirect('blocks.index');
        }

        $data = PurchaseEntry::findOrFail($request->purchase_id);
        $agents = Agent::orderBy('company_name', 'ASC')->where('status', 1)->get();
        // $agents-distributors = Agent::select('company_name', 'code')->orderBy('company_name', 'ASC')->get();

        return view('flight-tickets.blocks.create', compact('data', 'agents'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'ticket_id' => 'required',
            'agent_id' => 'required',
            'quantity' => 'required|integer',
            'remarks' => 'required'
        ]);
        $ticket_info = PurchaseEntry::find($request->ticket_id);
        $agent_info  = Agent::find($request->agent_id);

        $block_ticket_data = [
            'agent_id' => $agent_info->id,
            'purchase_entry_id' => $ticket_info->id,
            'quantity' => $request->quantity,
            'remarks' => $request->remarks,
            'created_by' =>  Auth::id(),
        ];
        $available_ticket  = $ticket_info->quantity - $ticket_info->blocks - $ticket_info->sold;
        if (!($available_ticket >= $request->quantity)) {
            $request->session()->flash('error', 'Sorry we could process your booking as inventory is over');
            return redirect(route('bookings.index'));
        }

        $resp = BlockTicket::create($block_ticket_data);
        $ticket_info->increment('blocks', $request->quantity);
        $ticket_info->decrement('available', $request->quantity);

        if ($resp) {
            // send email
            //            // send sms
            activity('Ticket Seat Blocked')
                ->performedOn($resp)
                ->event('created')
                ->log('Blocked '.$request->quantity.' seat(s) of '.$ticket_info->pnr.' PNR for '.$agent_info->company_name.'  ('.$agent_info->code.')');
            $request->session()->flash('success', 'Successfully Blocked Ticket');
            return redirect(route('bookings.index'));
        } else {
            $request->session()->flash('error', 'Opps Something went wrong');
            return redirect(route('bookings.index'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BlockTicket  $blockTicket
     * @return \Illuminate\Http\Response
     */
    public function show(Request  $request, $id)
    {
        $blockTicket = BlockTicket::find($id);
        $data = PurchaseEntry::find($blockTicket->purchase_entry_id);


        $data->decrement('blocks', $blockTicket->quantity);
        $data->increment('available', $blockTicket->quantity);
        $resp = $blockTicket->delete();
        if ($resp) {
            $request->session()->flash('success', 'Successfully Release the Ticket');
            return redirect('/reports/block-reports');
        }
    }





    public function releaseBlockTicket(Request  $request)
    {
        $blockTicket = BlockTicket::find($request->release_id);
        $data = PurchaseEntry::find($blockTicket->purchase_entry_id);

        if($blockTicket->quantity < $request->release_quantity) {
            $request->session()->flash('error', 'Cannot release the ticket. Invalid value given');
            return redirect('/reports/block-reports');
        }

        $data->decrement('blocks', $request->release_quantity);
        $data->increment('available', $request->release_quantity);

        if($blockTicket->quantity == $request->release_quantity) {
            $resp = $blockTicket->delete();
        }else{
            $blockTicket->decrement('quantity', $request->release_quantity);
            $blockTicket->remarks = $request->release_remarks;
            $resp = $blockTicket->save();
        }

        if ($resp) {
            activity('Block Ticket Released !')
                ->performedOn($blockTicket)
                ->event('edited')
                ->log($request->release_quantity.' Block Seat of PNR '.$data->pnr.' has been released');

            $request->session()->flash('success', 'Successfully Release the Ticket');
            return redirect('/reports/block-reports');
        }
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BlockTicket  $blockTicket
     * @return \Illuminate\Http\Response
     */
    public function edit(BlockTicket $blockTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BlockTicket  $blockTicket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BlockTicket $blockTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlockTicket  $blockTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlockTicket $blockTicket)
    {
        //
    }
}
