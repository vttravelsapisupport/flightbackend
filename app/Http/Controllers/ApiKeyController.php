<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\FlightTicket\Agent;


class ApiKeyController extends Controller
{
    public function index()
    {
        $apiKeys = ApiKey::latest()->paginate(10);
        return view('api_keys.index', compact('apiKeys'));
    }

    public function create()
    {
        return view('api_keys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'agent_code' => 'required|string|max:255',
        ]);

        $agent = Agent::where('code', $request->agent_code)->first();

        if (!$agent) {
            return back()->withErrors(['agent_code' => 'Invalid Agent Code.'])->withInput();
        }
        $data = $request->all();
        $data['agent_id'] = $agent->id;
        $data['api_key_primary']   = Str::random(32);  // or Str::random(32)
        $data['api_key_secondary'] = Str::random(32);

        ApiKey::create( $data);

        return redirect()->route('api_keys.index')->with('success', 'API Key created successfully.');
    }

    public function show(ApiKey $apiKey)
    {
        return view('api_keys.show', compact('apiKey'));
    }

    public function edit(ApiKey $apiKey)
    {
        return view('api_keys.edit', compact('apiKey'));
    }

    public function update(Request $request, ApiKey $apiKey)
    {
        $request->validate([
            'agent_code' => 'required|string|max:255',
        ]);
        $data = $request->all();
        if($request->agent_code != $apiKey->agent_code){
            $agent = Agent::where('code', $request->agent_code)->first();

            if (!$agent) {
                return back()->withErrors(['agent_code' => 'Invalid Agent Code.'])->withInput();
            }
            $data['agent_id'] = $agent->id;
        }
        
        $apiKey->update($data);

        return redirect()->route('api_keys.index')->with('success', 'API Key updated successfully.');
    }

    public function destroy(ApiKey $apiKey)
    {
        $apiKey->delete();
        return redirect()->route('api_keys.index')->with('success', 'API Key deleted successfully.');
    }
}
