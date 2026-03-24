<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\PdpaForm;
use Illuminate\Http\Request;
use App\Helpers\UserSystemInfoHelper;
use App\Models\Consent;
use Stevebauman\Location\Facades\Location;


class ConsentController extends Controller
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //



        $getpapd = PdpaForm::where('token',$id)->where('status','Y')->first();
        $collection = collect($getpapd);


        if($getpapd){
            $getpapd->branch_id;
            $branc = Branch::where('id',$getpapd->branch_id)->first();

            $collection->prepend($branc->name, 'branch');
        }else {
            return abort(500);
        }

    return view('page.consent.index')->with('item',$collection);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function saveconsent(Request $request)
    {


$getip = UserSystemInfoHelper::get_ip();
$getbrowser = UserSystemInfoHelper::get_browsers();
$getdevice = UserSystemInfoHelper::get_device();
$getos = UserSystemInfoHelper::get_os();

$getfom = PdpaForm::where('token',$request->token)->first();
$branch = Branch::where('id',$getfom->branch_id)->first();


$save = Consent::create([
    'pdpaform_id' => $getfom->id,
    'branch_id' => $getfom->branch_id,
    'telephone' => $request->number,
    'ip' => $getip,
    'browser' => $getbrowser,
    'device' => $getdevice,
    'os' => $getos,
    'details' => $request->userAgent,
    'status' => 'Y'
]);

$datas = [];
$datas['telephone'] = $request->number;
$datas['ip'] = $getip;
$datas['branch_name'] = $branch->name;
$datas['line_token'] = $getfom->linenoti;



$linealert = UserSystemInfoHelper::Teleconfirm($datas);




        return response()->json([
            'msg_return' => 'บันทึกสำเร็จ',
            'code_return' => 1
        ]);
    }



}
