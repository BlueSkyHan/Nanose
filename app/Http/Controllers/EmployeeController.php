<?php

namespace App\Http\Controllers;

use App\Employee;
use App\EmployeeType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($store_id)
    {
        $employees = Employee::where('store_id', $store_id)->orderBy('created_at', 'DESC')->get();
        return view('employee.showAll', compact('employees', 'store_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($store_id)
    {
        $employeeTypes = EmployeeType::orderBy('created_at')->pluck('name', 'id')->all();
        return view('employee.create', compact('employeeTypes', 'store_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $store_id)
    {
        $input = $request->all();
        if(!User::where('username', $input['username'])->first()){
            $user = User::create(['username'=>$input['username'], 'password'=>bcrypt($input['password'])]);
            $employee = new Employee;
            $employee->employee_type_id = $input['employee_type_id'];
            $employee->store_id = $store_id;
            $employee->user_id = $user->id;
            $employee->name = $input['name'];
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('dist/img/employee', $filename);
            $employee->photo_path = $filename;
            $employee->save();
        }
        return $this->index($store_id);
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
    public function edit($store_id, $id)
    {
        $employeeTypes = EmployeeType::orderBy('created_at')->pluck('name', 'id')->all();
        $employee = Employee::findOrFail($id);
        return view('employee.edit', compact('employeeTypes', 'employee', 'store_id', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $store_id, $id)
    {
        $input = $request->all();
        $employee = Employee::findOrFail($id);
        $input['username'] = isset($input['username']) ? $input['username'] : $employee->user->username;
        $employee->user()->update(['username' => $input['username'], 'password' => bcrypt($input['password'])]);
        if(isset($input['employee_type_id'])){
            $employee->employee_type_id = $input['employee_type_id'];
        }
        $employee->name = $input['name'];
        if($file = $request->file('photo')){
            if(file_exists($employee->photo_path)){
                unlink($employee->photo_path);
            }
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move('dist/img/employee', $filename);
            $employee->photo_path = $filename;
        }
        $employee->save();
        if(Auth::user()->isAdmin()){
            return $this->index($store_id);
        }else{
            return redirect('/');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($store_id, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->user->delete();
        if(file_exists($employee->photo_path)){
            unlink($employee->photo_path);
        }
        $employee->delete();
        return $this->index($store_id);
    }
}
