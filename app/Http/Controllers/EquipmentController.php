<?php

namespace App\Http\Controllers;

use App\Models\category;
use App\Models\equipment;
use App\Models\reservation;
use Illuminate\Http\Request;
use App\Models\reservation_details;
use Illuminate\Support\Facades\Auth;

class EquipmentController extends Controller
{
    public function submitEquipment(Request $request){
    $newEquipment = new equipment();
    $validatedCategoryData = $request->validate([
        'categoryID' => 'required|exists:category_table,categoryID',
    ]);
        $newEquipment->equipmentName = $request->equipmentName;
        $newEquipment->quantity = $request->quantity;
        $newEquipment->description = $request->description;
        $newEquipment->barcode = $request->barcode;
        $newEquipment->price = $request->price;
        $newEquipment->location = $request->location;
        $newEquipment->categoryID = $request->categoryID;
        $newEquipment->condition = $request->condition;
        
        if($request->hasfile('image'))
        {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time().'.'.$extenstion;
            $file->move(public_path('/uploads'), $filename);
            $newEquipment->image = $filename;
        }

        $res = $newEquipment->save();

        return $res;
    }

    public function getMaxStock(){
        $getEquipmentStock = equipment::find($quantity['id']);
        return $getEquipmentStock;
    }

    public function getEquipments(){
        $getEquipment = equipment::all();
        return $getEquipment;
    }
    public function getEquipment(Request $request){
        $getEquipment=equipment::find($request->edit_id);
        // dd($getEquipment);
            // dd($request->data); 
        return $getEquipment;

    }
    public function updateEquipment(Request $request){
       
        $data = $request->data;
        $updatedInfo = equipment::find($data['id']);
        $updatedInfo->equipmentName = $data['equipmentName'];
        $updatedInfo->quantity = $data['quantity'];
        $updatedInfo->description = $data['description'];
        $updatedInfo->barcode = $data['barcode'];
        $updatedInfo->price = $data['price'];
        $updatedInfo->categoryID = $data['categoryID'];
        $updatedInfo->condition = $data['condition'];
        $res = $updatedInfo->save();
    
        return $res;
    }
    public function addDisposeEquipment(Request $request){
       
        $data = $request->data;
        $updatedInfo = equipment::find($data['id']);
        $updatedInfo->quantity = $data['quantity'];
        $res = $updatedInfo->save();

        return $res;
    }




    public function deleteEquipment(Request $request){
        $deleteEquipment = equipment::find($request->ID);

        $res = $deleteEquipment->delete();
    
        return $res;
    }

    public function submitEquipmentOrder(Request $request){
        $newReservationDetail = new reservation_details();
        $validatedData = $request->validate([
            'equipment_id' => 'required|exists:equipments,equipment_id',
            'quantity' => 'required|integer|min:0',
        ]);
        $newReservationDetail->reservationNumber = $request->reservationNumber;
        $newReservationDetail->equipment_id = $request->equipment_id;
        $newReservationDetail->quantity = $request->quantity;

        $res = $newReservationDetail->save();

        return $res;
    }

    public function returnMissingEquipment(Request $request)
{
    // Validate request data
    $validatedData = $request->validate([
        'equipmentID' => 'required|exists:equipment,equipmentID',
        'missingQuantity' => 'required|numeric|min:1',
    ]);

    // Find the equipment by ID
    $equipment = Equipment::findOrFail($validatedData['equipmentID']);

    // Update the quantity
    $newQuantity = $equipment->quantity - $validatedData['missingQuantity'];

    // Ensure quantity doesn't go below 0
    $newQuantity = max(0, $newQuantity);

    // Update the equipment with the new quantity
    $equipment->quantity = $newQuantity;
    $equipment->save();

    return response()->json(['message' => 'Missing equipment updated successfully.']);
}


}
