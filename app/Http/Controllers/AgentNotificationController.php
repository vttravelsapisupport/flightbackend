<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\FlightTicket\Agent;
use App\Models\FlightTicket\Airport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LandingPageNotification;
use App\Models\AgentNotification as AgentNotificationModel;

class AgentNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $agent_notifications = AgentNotificationModel::orderBy('id', 'DESC')->paginate(50);
        return view('notifications.agent-notification.list', compact('agent_notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $agents = Agent::where('status', 1)
            ->orderBy('company_name', 'ASC')
            ->pluck('company_name', 'id')->all();
        $sectors = Airport::where('status', 1)
            ->orderBy('name', 'ASC')
            ->pluck('cityCode', 'id')->all();
        return view('notifications.agent-notification.create', compact('agents', 'sectors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $notification_type  =  $request->notification_type;
        $notification_level = $request->notification_level;
        $title = $request->title;
        $body = $request->body;

        AgentNotificationModel::create([
            'title' => $title,
            'body' => $body,
            'notification_type' => $notification_type,
            'notification_level' => $notification_level,
            'created_by' => Auth::id()
        ]);
        // if ($notification_level == 1) {
        //     $activeAgents = Agent::where('status', 1)->get();
        //     foreach ($activeAgents as $key => $agent) {
        //         $user = User::where('email', $agent->email)->first();
        //         //$user->notify(new LandingPageNotification($user, $request));
        //         if($user)
        //         Notification::send($user, new LandingPageNotification($request));
        //     }
        // }
        $request->session()->flash('success', 'Successfully Saved');
        return redirect(route('agent-notification.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Agent::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data =  AgentNotificationModel::find($id);
        return view('notifications.agent-notification.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data =  AgentNotificationModel::find($id);
        
        $notification_type  =  $request->notification_type;
        $notification_level = $request->notification_level;
        $title = $request->title;
        $body = $request->body;
        $status = $request->status;

        $data->update([
            'title' => $title,
            'body' => $body,
            'notification_type' => $notification_type,
            'notification_level' => $notification_level,
            'status' => $status
        ]);

        $request->session()->flash('success', 'Successfully Updated');
        return redirect(route('agent-notification.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
