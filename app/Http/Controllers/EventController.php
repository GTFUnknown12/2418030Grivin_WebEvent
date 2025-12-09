<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Pembeli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::where('is_active', true)
            ->orderBy('start_date', 'asc')
            ->get();

        return view('events.index', compact('events'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_attendees' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'price' => $request->price,
            'max_attendees' => $request->max_attendees,
            'image_url' => $imagePath ? Storage::url($imagePath) : null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_attendees' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $event->image_url;
        if ($request->hasFile('image')) {
            if ($event->image_url) {
                $oldImage = str_replace('/storage/', '', $event->image_url);
                Storage::disk('public')->delete($oldImage);
            }
            $imagePath = $request->file('image')->store('events', 'public');
            $imagePath = Storage::url($imagePath);
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'location' => $request->location,
            'price' => $request->price,
            'max_attendees' => $request->max_attendees,
            'image_url' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if ($event->image_url) {
            $oldImage = str_replace('/storage/', '', $event->image_url);
            Storage::disk('public')->delete($oldImage);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }

    public function registerEvent(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'company' => 'nullable|string',
        ]);

        $registrationData = [
            'event_id' => $event->id,
            'pembeli_id' => Auth::guard('pembeli')->id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'registration_date' => now(),
            'status' => 'pending',
        ];

        $registrations = [];
        if (file_exists(storage_path('app/registrations.json'))) {
            $registrations = json_decode(file_get_contents(storage_path('app/registrations.json')), true);
        }

        $registrations[] = $registrationData;
        file_put_contents(storage_path('app/registrations.json'), json_encode($registrations, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', "Thank you, {$request->name}! You have successfully registered for {$event->title}.");
    }
}