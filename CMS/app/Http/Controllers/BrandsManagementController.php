<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Profile;
use App\Models\User;
use App\Models\Posts;
use App\Models\Brand;
use App\Traits\ActivationTrait;
use App\Traits\CaptchaTrait;
use App\Traits\CaptureIpTrait;
use Auth;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use jeremykenedy\LaravelRoles\Models\Permission;
use jeremykenedy\LaravelRoles\Models\Role;
use Validator;

class BrandsManagementController extends Controller
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

        $brands = Brand::where('status', 1)->get();
        
        return View('brandmanagement.show-brands', compact('brands'));

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

        //$brand = Brand::findOrFail($id);
        $brand = \DB::table('brand')->where('id', $id)->first()->brand;
        \DB::table('brand')->where('id', $id)->delete();
        $posts = \DB::table('posts')->where('brand','like','%'.$brand.'%')->get();
        
        foreach ($posts as $post){
            $brand_list = explode(',', $post->brand);

            $flag = 0;
            $result = '';
            foreach ($brand_list as $brand_item){
                if($brand_item != '')
                    if ($brand_item != $brand) {
                        if($flag == 0){
                            $result = $brand_item;
                            $flag = 1;
                        }else {
                            $result = $result . ',' . $brand_item;
                        }
                    }
            }
            if($result != '')
                $result = ','.$result.',';
            \DB::table('posts')->where('id', $post->id)->update(['brand' => $result]);
        }
        
        return redirect('brands')->with('success', 'Successfully deleted brand!');

    }
}
