<?php

namespace App\Http\Controllers;

use App\Models\Seminar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SeminarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private $preSpywareMax = 0;
    private $SpywareMax = 0;
    private $preMalwareMax = 0;
    private $MalwareMax = 0;

    public function index()
    {
        $data = [
            "title" => "Seminar | SI FEST",
            "nav" => [
                "active" => 'Seminar',
            ],
            "assets" => 'Seminar',
        ];

        return view("web/seminar/seminar", $data);
    }

    public function create(Request $request)
    {
        // $user = Seminar::where('email', Auth::user()->email)->first();
        // $count_user_spyware = Seminar::where('type', "Spyware")->count() - $this->preSpywareMax;
        // $count_user_malware = Seminar::where('type', "Malware")->count() - $this->preMalwareMax;

        // $slot_user_spyware = $this->SpywareMax - $count_user_spyware;
        // // $slot_user_spyware = 0;
        // $slot_user_malware = $this->MalwareMax - $count_user_malware;

        // if($count_user_spyware >= $this->SpywareMax && $count_user_malware >= $this->MalwareMax) {
        //     return back()->with("error", "Sorry, we were out of slot! stay tuned, more information on instragram @sifest.unsri");
        // }

        // if ($user) {
        //     return back()->with("error", "You have registered for the Seminar");
        // }

        // $data = [
        //     "title" => " Registration | SI FEST",
        //     "nav" => [
        //         "active" => 'Registration',
        //     ],
        //     "slot" => [
        //         "spyware" => $slot_user_spyware,
        //         "malware" => $slot_user_malware,
        //     ],
        //     "assets" => "Form",
        // ];

        // return view("web/registration/seminar", $data);

        $numberOfEntries = $request->input('jumlahData', 1); // Mengambil jumlah data dari input atau default ke 1

        $user = Seminar::where('email', Auth::user()->email)->first();
        $count_user_spyware = Seminar::where('type', "Spyware")->count() - $this->preSpywareMax;
        $count_user_malware = Seminar::where('type', "Malware")->count() - $this->preMalwareMax;

        // $slot_user_spyware = $this->SpywareMax - $count_user_spyware;
        $slot_user_spyware = 0;
        // $slot_user_malware = $this->MalwareMax - $count_user_malware;
        $slot_user_malware = 0;

        if ($count_user_spyware >= $this->SpywareMax && $count_user_malware >= $this->MalwareMax) {
            return back()->with("error", "Sorry, we were out of slot! Stay tuned for more information on Instagram @sifest.unsri");
        }

        if ($user) {
            return back()->with("error", "You have registered for the Seminar");
        }

        $data = [
            "title" => " Registration | SI FEST",
            "nav" => [
                "active" => 'Registration',
            ],
            "slot" => [
                "spyware" => $slot_user_spyware,
                "malware" => $slot_user_malware,
            ],
            "assets" => "Form",
            "jumlahData" => $numberOfEntries, // Mengirim jumlah data ke halaman pendaftaran
        ];

        return view("web/registration/seminar", $data);

        // return back()->with("error", "Sorry, online registration closed");
    }

    public function registration (Request $request)
    {
        // $validation = $request->validate([
        //     "name" => "required", "string",
        //     "email" => "required", "string", "email:dns", "unique:users",
        //     "phone_number" => "required", "numeric", "min:11", "max:15",
        //     "line" => "required", "string",
        //     "type" => "required",
        //     "metode" => "required",
        //     "payment" => "required", "image", "mimes:jpeg,png,jpg,gif,svg", "max:2048",
        // ]);

        $numberOfEntries = $request->input('jumlahData', 1); // Mengambil jumlah data dari input atau default ke 1

        $validation = $request->validate([
            "name" => "required|string",
            "jumlahData" => "required",
            // "email" => "required|string|email:dns|unique:users",
            "email" => "required|string|email:dns",
            // "phone_number" => "required|numeric|min:11|max:15",
            "phone_number" => "required|numeric|min:11",
            "line" => "required|string",
            "type" => "required",
            "metode" => "required",
            "payment" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        ]);

        if($request->file('payment')){
            $payment = $request->file('payment');
            $filepayment = date('YmdHi').".".$payment->getClientOriginalName();
            $payment->move(public_path('payment'), $filepayment);
            $validation['payment'] = $filepayment;

            // $validation['name'] = ucwords(strtolower($request->name));

            // $Seminar = new Seminar($validation);

            // $Seminar->save();

            for ($i = 0; $i < $numberOfEntries; $i++) {
                $validation['name'] = ucwords(strtolower($request->name));

                $Seminar = new Seminar($validation);
                $Seminar->save();
            }

            return redirect()->intended('dashboard')->with("success", "Registration is successful, wait for verification from admin");
        }

        return back()->with("error", "Registration failed, try again");
    }

    public function registrationOTS (Request $request)
    {
        $validation = $request->validate([
            "name" => "required", "string",
            // "email" => "required", "string", "email:dns", "unique:seminars",
            "email" => "required", "string", "email:dns",
            // "phone_number" => "required", "numeric", "min:11", "max:15",
            "phone_number" => "required", "numeric", "min:11", "max:50",
            "line" => "required", "string",
            "type" => "required",
            "verification" => "required",
            "metode" => "required",
            "jumlahData" => "required",
        ]);

        $validation['name'] = ucwords(strtolower($request->name));
        $Seminar = new Seminar($validation);
        $Seminar->save();
        return redirect()->intended('/sifest2024/admin/seminar')->with("success", "Registration is successful");
    }
}
