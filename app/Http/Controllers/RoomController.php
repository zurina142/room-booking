<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomCategory;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::with('room_category')
        ->where('enabled', 1)
        ->orderBy('updated_at', 'desc')
        //->latest() //order by created_by
        ->paginate(5);

        return view('room.index',[
            'rooms' => $rooms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('room.edit', [
            'room' => new Room,
            'room_categories' => RoomCategory::where('enabled', 1)->get()
            
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'max:255'],
            'capacity' => ['required', 'max:4'],
            'room_category_id' => ['required'],
        ]);

        Room::create([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'room_category_id' => $request->room_category_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('room.index')->with('success', 'Room created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit(Room $room)
    {
        return view('room.edit', [
            'room' => $room,
            'room_categories' => RoomCategory::where('enabled', 1)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
        //dd($request->all());

        $request->validate([
            'name' => ['required', 'max:255'],
            'capacity' => ['required', 'max:4'],
            'room_category_id' => ['required'],
        ], [
            'name.required' => 'The :attribute is required.',
            'name.max' => 'The :attribute may not be greater than :max characters.',
            'capacity.required' => 'The :attribute is required.',
            'capacity.max' => 'The :attribute may not be greater than :max characters.',
            'room_category_id.required' => 'The :attribute is required.',
        ]);

        $room->update([
            'name' => $request->name,
            'capacity' => $request->capacity,
            'user_id' => auth()->id(),
            'room_category_id' => $request->room_category_id,
        ]);

        return redirect()->route('room.index')->with('success', 'Room updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {

        //dd($room->id);
        $room->delete();

        return redirect()->route('room.index')->with('success', 'Room deleted.');
    }
}
