<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Profile;
use App\Models\User;
use App\Models\Posts;
use App\Models\Style;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use jeremykenedy\LaravelRoles\Models\Permission;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;

class StylesManagementController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $styles = Style::where('status', 1)->get();
        
        return View('stylesmanagement.show-styles', compact('styles'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('stylesmanagement.create-style');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:255'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $style =  Style::create([
                'name'   => $request->input('name'),
                'status' => 1
            ]);

            $style->save();

            return redirect('styles')->with('success', 'Successfully created style!');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = User::find($id);

        return view('stylesmanagement.show-style')->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $style = Style::findOrFail($id);
        
        $data = [
            'style'          => $style
        ];

        return view('stylesmanagement.edit-style')->with($data);
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
        $style = Style::find($id);

        $validator = Validator::make($request->all(), [
            'name'      => 'required|max:255'
        ]);

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        } else {
            $style->name = $request->input('name');

            $style->status = 1;

            $style->save();
            return back()->with('success', 'Successfully updated style!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $style = Style::findOrFail($id);

        $style->status = 0;
        $style->save();
        return redirect('styles')->with('success', trans('stylesmanagement.deleteSuccess'));

    }
}
