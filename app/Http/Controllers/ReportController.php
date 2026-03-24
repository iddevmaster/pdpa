<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Consent;
use App\Models\PdpaForm;
use Illuminate\Http\Request;
use DataTables;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request = null)
    {
        //

        // $datas = [];
        // $data = Consent::where('status','Y')->get();

        // foreach ($data as $key => $sky) {
        //     $branch = Branch::where('id',$sky->branch_id)->first();
        //     $form = PdpaForm::where('id',$sky->pdpaform_id)->first();

        //     $date = Carbon::createFromFormat('Y-m-d H:i:s', $sky->created_at)->format('Y-m-d H:i:s');

        //     $datas[$key]['id'] = $sky->id;
        //     $datas[$key]['pdpaform_id'] = $form->code_form;
        //     $datas[$key]['branch_id'] = $branch->name;
        //     $datas[$key]['telephone'] = $sky->telephone;
        //     $datas[$key]['note'] = $form->note;
        //     $datas[$key]['ip'] = $sky->ip;
        //     $datas[$key]['created_at'] = $date;


        // }

        $typebranch = Branch::where('status','Y')->get();






        return view('page.report.index')->with('branch',$typebranch);
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


    public function getDataalltable(Request $request)
    {


        $datas = [];

        $data = Consent::where('status','Y');

        if($request->name){
            $data->where('telephone', 'like', '%'.$request->name.'%')->orWhere('ip','like','%'.$request->name.'%');
        }
        if($request->branch_id){
            $data->where('branch_id',$request->branch_id);
        }


       $report = $data->whereYear('created_at', Carbon::now()->year)->get();

        foreach ($report as $key => $sky) {
            $branch = Branch::where('id',$sky->branch_id)->first();
            $form = PdpaForm::where('id',$sky->pdpaform_id)->first();

            $date = Carbon::createFromFormat('Y-m-d H:i:s', $sky->created_at)->format('Y-m-d H:i:s');

            $datas[$key]['id'] = $sky->id;
            $datas[$key]['pdpaform_id'] = $form->code_form;
            $datas[$key]['branch_id'] = $branch->name;
            $datas[$key]['telephone'] = $sky->telephone;
            $datas[$key]['note'] = $form->note;
            $datas[$key]['ip'] = $sky->ip;
            $datas[$key]['created_at'] = $date;
        }

      return DataTables::of($datas)->make(true);
    }


    public function downloadPDF($id){


        $con = Consent::where('id',$id)->first();
        $form = PdpaForm::where('id',$con->pdpaform_id)->first();
        $branch = Branch::where('id',$con->branch_id)->first();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', $con->created_at)->format('Y-m-d H:i:s');

        $data = [
            'branch' => $branch->name,
            'note' => $form->note,
            'des' => $form->des,
            'agree' => $form->agree,
            'ip' => $con->ip,
            'number' => $con->telephone,
            'created_at' => $date,
        ];



        $pdf = PDF::loadView('pdf',$data);
        return $pdf->download('pdpa.pdf');

      }




}
