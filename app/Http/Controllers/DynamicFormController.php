<?php

namespace App\Http\Controllers;

use App\Models\DynamicForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class DynamicFormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dynamicForms = DynamicForm::get();
        if ($request->ajax()) {
            $allData = DataTables::of($dynamicForms)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" data-toggle="tolltip" data-id="' . $row->id . '" data-original-title="Edit" class="edit btn btn-primary btn-sm editDynamic" id="editDynamic">Edit</a>';
                    $btn.= '<a href="javascript:void(0)" data-toggle="tolltip" data-id="' . $row->id . '" data-original-title="Delete" class="edit btn btn-danger btn-sm deleteDynamic">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
                return $allData;
        }
        return view('dynamic-form',compact('dynamicForms'));
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
        $request->validate([
            'moreFields.*.title' => 'required'
        ]);
     
        foreach ($request->moreFields as $key => $value) {
            DynamicForm::updateOrCreate($value);
        }
     
        return back()->with('success', 'Data Has Been Created Successfully.');
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
        $dynamicForms = DynamicForm::find($id);
        return Response::json($dynamicForms);
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
        $request->validate([
            'moreFields.*.title' => 'required'
        ]);
     
        foreach ($request->moreFields as $key => $value) {
            DynamicForm::updateOrCreate(['id'=>$value['id']], $value);
        }
     
        return back()->with('success', 'Data Has Been Created Successfully.');
    }
     

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dynamicForms = DynamicForm::where('id',$id)->delete();
        return response()->json([
            'status'=> true,
            'info' => "Berhasil Di Hapus"
        ],201);
    }
}
