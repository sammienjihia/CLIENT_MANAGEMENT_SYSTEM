<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ClientContact;
use App\Lead;

class LeadsController extends Controller
{
    //
    /**
     * Create a new CreateFaController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * This function enables anyone to create a lead
     * Below is the expected body of the request
     * {
     *      "description":"some description",
     *      "user_id":"id of the user creating the lead",
     *      "source_id":"id of the source of the lead",
     *
     *      *******Client Contact details*******
     *      "first_name":"Client first name",
     *      "last_name":"Client last name"
     *      "email":"Client email",
     *      "phone_number":"Client phone number",
     *      "alternative_email":"Client's alternate email",
     *      "alternate_phone_number":"client alternate phone number",
     *      "city":"Client's city of residence",
     *      "address":"Client's address"
     * }
     */
    public function createLead(Request $request){
        /**
         * This operation can be done by anyone
         */

        // validate your inputs
        $validator = Validator::make($request->all(), [
            'description'=> 'required|string',
            'source_id'=>'required|integer',
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|email|unique:client_contacts',
            'phone_number'=>'required|string|min:9|max:10|unique:client_contacts',
            'alternative_email'=>'string|unique:client_contacts',
            'alternative_phone_number'=>'string|min:9|max:10|unique:client_contacts',
            'address'=> 'string',
            'city'=>'string',
            'zip_code'=>'integer',
            'source_id'=>'required|integer',
            'product_id'=>'required|integer',
            'user_id'=>'integer',

        ]);

        if ($validator->fails()){
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $description = $request->description;
        $lastName = $request->last_name;
        $firstName = $request->first_name;
        $email = $request->email;
        $phoneNumber = $request->phone_number;
        $alternativeEmail = $request->alternative_email;
        $alternativePhoneNumber = $request->alternative_phone_number;
        $address = $request->address;
        $city = $request->city;
        $zipCode = $request->zip_code;
        $sourceId = $request->source_id;
        $productId = $request->product_id;
        $userId = $request->user_id;


        // Create contact details
        try{
            $clientContacts = ClientContact::create([
                'first_name'=> $firstName,
                'last_name'=> $lastName,
                'email'=> $email,
                'phone_number'=>$phoneNumber,
                'alternative_email'=>$alternativeEmail,
                'alternative_phone_number'=>$alternativePhoneNumber,
                'address'=>$address,
                'city'=>$city,
                'zip_code'=>$zipCode

            ]);

            // return response()->json(['status'=>'1', 'msg'=>'FA added successfully with password '.$password], 201);


        } catch(\Exception $e){
            return response()->json(['error'=>'Client contact not created', 'msg'=>$e]);
        }

        // Create lead
        try{
            $lead_code = $this->generateLeadCode();
            Lead::create([
                'description'=>$description,
                'lead_code'=> $lead_code,
                'source_id'=>$sourceId,
                'user_id'=>$userId,
                'client_contact_id'=>$clientContacts->id,
                'product_id'=>$productId,
            ]);
            return response()->json(['status'=>'1', 'msg'=>'Lead with lead code '.$lead_code.' added successfully'], 201);
        } catch(\Exception $e){
            return response()->json(['error'=>'Lead not created ', 'msg'=>$e, 'sourceid'=>$sourceId,  'client contact id'=>$clientContacts->id, 'user_id'=>auth()->user()->id, 'lead Code'=>$this->generateLeadCode()]);
        }


    }

    // Function to generate lead code
    /**
      * This function generates a random leadcode
    */
    public function generateLeadCode(){
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $code = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++){
            $n = rand(0, $alphaLength);
            $code[] = $alphabet[$n];
        }
        return implode($code);
    }


    /**
     * This function gets all the leads
     */
    public function getAllLeads(){

    }

    /**
     * This function gets all the leads assigned to an authenticated FA
     */
    public function getMyLeads(){

    }

    /**
     * This function get's all available leads
     */
    public function getAllAvailableLeads(){

    }

    /**
     * This function allows an FA to take up a lead
     */
    public function takeUpLead(){

    }

    /**
     * This function allows an FA to take up an availale lead
     */


}
