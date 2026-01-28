<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;




class location_cnt extends Controller
{
    // public function index(Request $req){

    //     {
    //         // Get latitude and longitude from the request
    //         $latitude = $req->input('latitude');
    //         $longitude = $req->input('longitude');

    //         // Google API Key
    //         $googleApiKey = env('GOOGLE_MAPS_API_KEY');

    //         $geocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$googleApiKey}";

    //         // // Make the request to the Google Geocoding API
    //         // $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
    //         //     'latlng' => "{$latitude},{$longitude}",
    //         //     'key' => $googleApiKey
    //         // ]);

    //         // // // Decode the response
    //         //  $location = $response->json();

    //         // Initialize cURL session
    //     $ch = curl_init();

    //     // Set cURL options
    //     curl_setopt($ch, CURLOPT_URL, $geocodingUrl);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Optional: Disable SSL verification (if needed)

    //     // Execute cURL request
    //     $response = curl_exec($ch);

    //     // Check for cURL errors
    //     if ($response === false) {
    //         $error = curl_error($ch);
    //         curl_close($ch);
    //         // Handle error (e.g., log it or return a response)
    //         return response()->json(['error' => 'cURL error: ' . $error], 500);
    //     }

    //     // Close cURL session
    //     curl_close($ch);

    //     // Decode the response (Google API returns JSON)
    //     $location = json_decode($response, true);


    //         // Check if the response was successful
    //         if ($location['status'] === 'OK') {
    //             $district = null;

    //             $result = $location['results'][0];
    //             $formattedAddress = $result['formatted_address'];

    //         foreach ($location['results'][0]['address_components'] as $component) {
    //             // Look for the district (usually administrative_area_level_2) or locality (city)
    //             if (in_array('administrative_area_level_2', $component['types'])) {
    //                 $district = $component['long_name'];  // District
    //                 break;
    //             }

    //             // If no district is found, you can try to use locality as a fallback
    //             if (in_array('locality', $component['types'])) {
    //                 $district = $component['long_name'];  // Locality (City or Town)
    //                 break;
    //             }
    //         }

    //         $user = Auth::user()->id;

    //         // if(!is_null(Auth::user()->store_id)){

    //         // $st_time = DB::table('stores')->where('id',Auth::user()->store_id)->select('stores.store_start_time','stores.store_end_time')->first();

    //         // // dd($st_time);

    //         // }


    //         $alreadyLoggedIn = DB::table('attendance')->where('user_id', $user)->whereDate('c_on', date("Y-m-d"))->exists();

    //         $c_time = Carbon::now(); // Get the current time using Carbon

    //         if (!$alreadyLoggedIn) {
    //               $last_id  = DB::table('attendance')->insertGetId([
    //                     'user_id' => $user,
    //                     'attend_status' => 'Present',
    //                     'in_location' => $district,
    //                     'in_add' => $formattedAddress,
    //                     'in_time' => now()->format('H:i:s'),
    //                     'c_on' => now()->format('Y-m-d'),
    //                     'status' => 'Active'
    //                 ]);



    //                 // $start_time = Carbon::parse(!is_null(Auth::user()->store_id) ? Auth::user()->store_rel->store_start_time : Auth::user()->st_time);
    //                 $start_time = Carbon::parse((Auth::user()->st_time));

    //                 // Calculate the 5-minute range (+5 and -5 minutes)
    //                 $start_time_plus_5 = $start_time->copy()->addMinutes(10);
    //                 $start_time_minus_5 = $start_time->copy()->subMinutes(10);


    //                 if (!($c_time >= $start_time_minus_5 && $c_time <= $start_time_plus_5)) {

    //                     $late = $start_time->diff($c_time)->format('%H:%I');

    //                     if($c_time >= $start_time_plus_5){

    //                         DB::table('attd_ot')->insert([
    //                             'attd_id' => $last_id,
    //                             'cat' => 'late',
    //                             'time' => $late,
    //                             'status' => 'pending',
    //                             'created_at' => now(),
    //                             'updated_at' => now()
    //                         ]);
    //                     }
    //                 }


    //             }
    //         else{

    //             $get_last = DB::table('attendance')->where('user_id', $user)->whereDate('c_on', date("Y-m-d"))->orderBy('id', 'desc')->first();  // Get the first record

    //             DB::table('attendance')
    //             ->where('user_id', $user)
    //             ->whereDate('c_on', now()->format('Y-m-d'))
    //             ->whereNull('out_add')
    //             ->update([
    //                 'out_time' => now()->format('H:i:s'),
    //                 'out_location' => $district,
    //                 'out_add' => $formattedAddress,
    //                 'u_by'=>now()->format('Y-m-d')
    //             ]);

    //             // $end_time = Carbon::parse(!is_null(Auth::user()->store_id) ? Auth::user()->store_rel->store_end_time : Auth::user()->end_time);
    //             // $end_time = Carbon::parse(Auth::user()->end_time);

    //             // $end_time = Carbon::createFromFormat('H:i:s', Auth::user()->end_time);

    //             $time1 = Carbon::createFromFormat('H:i:s',Auth::user()->end_time);
    //             $time2 = Carbon::createFromFormat('H:i:s', $c_time->format('H:i:s'));


    //             // dd($end_time);

    //             if ($time2->gt($time1)) {

    //                 //   dd($time2,$time1);

    //                     // Define the two times


    //                     // Calculate the difference
    //                     $ot = $time1->diff($time2)->format('%H:%I');

    //                     DB::table('attd_ot')->insert([
    //                         'attd_id' => $get_last->id,
    //                         'cat' => 'ot',
    //                         'time' => $ot,
    //                         'status' => 'pending',
    //                         'created_at' => now(),
    //                         'updated_at' => now()
    //                     ]);
    //                 }

    //         }



    //         session()->flash('status', 'success');   // Use 'flash' to keep the session only for the next request
    //         session()->flash('message', $alreadyLoggedIn ? 'CheckOut Updated' : 'CheckIn Updated');

    //         // Return the district (or locality) as the response
    //             return response()->json([
    //                 'status'=>'success',
    //                 'attd_status'=>'CheckIn Updated',
    //                 'district' => $district ?? 'District not found'
    //             ]);
    //         }


    //         // Handle errors (e.g., location not found)
    //           return response()->json(['status'=>'Failed','attd_status'=>'CheckOut Update Failed','error' => 'Location Not Found'], 404);
    //     }
    // }
    
      public function index(Request $req)
    {

        // Get latitude and longitude from the request
        $latitude = $req->input('latitude');
        $longitude = $req->input('longitude');

        // Google API Key
        $googleApiKey = env('GOOGLE_MAPS_API_KEY');

        $geocodingUrl = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$googleApiKey}";

        // // Make the request to the Google Geocoding API
        // $response = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
        //     'latlng' => "{$latitude},{$longitude}",
        //     'key' => $googleApiKey
        // ]);

        // // // Decode the response
        //  $location = $response->json();

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $geocodingUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Optional: Disable SSL verification (if needed)

        // Execute cURL request
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);

            // Handle error (e.g., log it or return a response)
            return response()->json(['error' => 'cURL error: '.$error], 500);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the response (Google API returns JSON)
        $location = json_decode($response, true);

        // dd($location);

        // Check if the response was successful
        if ($location['status'] === 'OK') {
            $district = null;

            $result = $location['results'][0];
            $formattedAddress = $result['formatted_address'];

            foreach ($location['results'][0]['address_components'] as $component) {
                // Look for the district (usually administrative_area_level_2) or locality (city)
                if (in_array('administrative_area_level_2', $component['types'])) {
                    $district = $component['long_name'];  // District
                    break;
                }

                // If no district is found, you can try to use locality as a fallback
                if (in_array('locality', $component['types'])) {
                    $district = $component['long_name'];  // Locality (City or Town)
                    break;
                }
            }

            $user = Auth::user()->id;

            // if(!is_null(Auth::user()->store_id)){

            // $st_time = DB::table('stores')->where('id',Auth::user()->store_id)->select('stores.store_start_time','stores.store_end_time')->first();

            // // dd($st_time);

            // }

            $alreadyLoggedIn = DB::table('attendance')->where('user_id', $user)->whereDate('c_on', date('Y-m-d'))->exists();

            $c_time = Carbon::now(); // Get the current time using Carbon

            if (! $alreadyLoggedIn) {
                $last_id = DB::table('attendance')->insertGetId([
                    'user_id' => $user,
                    'attend_status' => 'Present',
                    'in_location' => $district,
                    'in_add' => $formattedAddress,
                    'in_time' => now()->format('H:i:s'),
                    'c_on' => now()->format('Y-m-d'),
                    'status' => 'Active',
                ]);

                // $start_time = Carbon::parse(!is_null(Auth::user()->store_id) ? Auth::user()->store_rel->store_start_time : Auth::user()->st_time);
                $start_time = Carbon::parse((Auth::user()->st_time));

                // Calculate the 5-minute range (+5 and -5 minutes)
                $start_time_plus_5 = $start_time->copy()->addMinutes(10);
                $start_time_minus_5 = $start_time->copy()->subMinutes(10);

                if (! ($c_time >= $start_time_minus_5 && $c_time <= $start_time_plus_5)) {

                    $late = $start_time->diff($c_time)->format('%H:%I');

                    if ($c_time >= $start_time_plus_5) {

                        DB::table('attd_ot')->insert([
                            'attd_id' => $last_id,
                            'cat' => 'late',
                            'time' => $late,
                            'status' => 'pending',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

            } else {

                $get_last = DB::table('attendance')->where('user_id', $user)->whereDate('c_on', date('Y-m-d'))->orderBy('id', 'desc')->first();  // Get the first record

                DB::table('attendance')
                    ->where('user_id', $user)
                    ->whereDate('c_on', now()->format('Y-m-d'))
                    ->whereNull('out_add')
                    ->update([
                        'out_time' => now()->format('H:i:s'),
                        'out_location' => $district,
                        'out_add' => $formattedAddress,
                        'u_by' => now()->format('Y-m-d'),
                    ]);

                // $end_time = Carbon::parse(!is_null(Auth::user()->store_id) ? Auth::user()->store_rel->store_end_time : Auth::user()->end_time);
                // $end_time = Carbon::parse(Auth::user()->end_time);

                // $end_time = Carbon::createFromFormat('H:i:s', Auth::user()->end_time);

                $time1 = Carbon::createFromFormat('H:i:s', Auth::user()->end_time);
                $time2 = Carbon::createFromFormat('H:i:s', $c_time->format('H:i:s'));

                // dd($end_time);

                if ($time2->gt($time1)) {

                    //   dd($time2,$time1);

                    // Define the two times

                    // Calculate the difference
                    $ot = $time1->diff($time2)->format('%H:%I');

                    DB::table('attd_ot')->insert([
                        'attd_id' => $get_last->id,
                        'cat' => 'ot',
                        'time' => $ot,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

            }

            session()->flash('status', 'success');   // Use 'flash' to keep the session only for the next request
            session()->flash('message', $alreadyLoggedIn ? 'CheckOut Updated' : 'CheckIn Updated');

            // Return the district (or locality) as the response
            return response()->json([
                'status' => 'success',
                'attd_status' => 'CheckIn Updated',
                'district' => $district ?? 'District not found',
            ]);
        }

        // Handle errors (e.g., location not found)
        return response()->json(['status' => 'Failed', 'attd_status' => 'CheckOut Update Failed', 'error' => 'Location Not Found'], 404);

    }
}
