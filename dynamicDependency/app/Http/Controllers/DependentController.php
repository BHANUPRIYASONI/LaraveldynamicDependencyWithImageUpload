<?php

namespace App\Http\Controllers;

use App\Models\Project3;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Response;
use Redirect;
use Session;
use App\Http\Controllers\validation_errors;

class DependentController extends Controller
{
  //

  public function index()
  {
    $submit = "Save";
    $url = url("register");
    $title = "Register User";
    $data = compact('title', 'url', 'submit');
    return view('home')->with($data);
  }

  public function nameValidation(Request $request)
  {
    $username =  $request->username;
    $user = Project3::where('userName', $username)->get();
    print_r($user);
    if($user==0)
    {
      $this->session->set_flashdata('errors', "user exist");
      echo 'true';
    }
    else
    {
      echo 'false';
    }
  }


  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'username' => 'required',
      'country' => 'required',
      'state' => 'required',
      'city' => 'required',
      'photo' => 'required'
  ]);
    // $request->validate([
    //   'username' => 'required',
    //   'country' => 'required',
    //   'state' => 'required',
    //   'city' => 'required',
    //   'photo' => 'required'
    // ]);

    if ($validator->fails()) {
      return redirect()
                  ->withErrors($validator)
                  ->withInput();
  }

  $validated = $validator->safe()->only(['username', 'country']);
  $validated = $validator->safe()->except(['username', 'country']);

        die;
    if ($request->username != '' && $request->country != '' && $request->state != '' && $request->city != '' && $request->photo != '') {
      $userId = $request['userid'];

      if (!$userId) {
        // Insert Query
        $user = new Project3;
        $user->userName = $request['username'];
        $user->country = $request['country'];
        $user->state = $request['state'];
        $user->city = $request['city'];

        $image = $request->file('photo');
        $ext = $image->getClientOriginalExtension();
        $filename = time() . '.' . $ext;
        $path = $image->move(public_path() . '/uploads/users', $filename);

        $user->profilePicture = $path;
        $user->save();
        $status = 200;
        $data = compact('status');
        return response()->json($data);
      } else {
        // Update Query
        $image = $request->file('photo');
        $ext = $image->getClientOriginalExtension();
        $filename = time() . '.' . $ext;
        $path = $image->move(public_path() . '/uploads/users', $filename);

        $user = Project3::where('user_id', $userId)->update(array(
          'userName' => $request['username'],
          'country' => $request['country'],
          'state'  => $request['state'],
          'city'  => $request['city'],
          'profilePicture'  => $path
        ));
        $status = 200;
        $data = compact('status');
        return response()->json($data);
      }
    } else {
      $this->session()->flash('error', 'All fields are required');
      return redirect();
    }
  }

  public function getCountry()
  {
    $countryList = Country::get(["id", "countryName"]);
    $data = compact('countryList');
    return response()->json($data);
  }

  public function getState(Request $request)
  {
    $country_id = $request['country_id'];
    $stateList = State::where("country_id", $country_id)->get(["id", "stateName"]);
    $data = compact('stateList');
    return response()->json($data);
  }

  public function getCity(Request $request)
  {
    $state_id = $request['state_id'];
    $cityList = City::where("state_id", $state_id)->get(["id", "cityName"]);
    $data = compact('cityList');
    return response()->json($data);
  }

  public function getData()
  {
    $userData = Project3::join('countrys', 'Project3s.country', '=', 'countrys.id')
      ->join('states', 'Project3s.state', '=', 'states.id')
      ->join('citys', 'Project3s.city', '=', 'citys.id')
      ->select('Project3s.user_id', 'Project3s.userName', 'countrys.countryName', 'states.stateName', 'citys.cityName')
      ->get();
    $data = compact('userData');
    return response()->json($data);
  }

  public function getInfoById(Request $request)
  {
    $userId = $request['user_id'];
    $userData = Project3::where('user_id', $userId)->get();
    $submit = "Update";
    $title = "Update User";
    $data = compact('userData', 'submit', 'title');
    return response()->json($data);
  }

  public function deleteData(Request $request)
  {
    $userId = $request['user_id'];
    $user = Project3::where('user_id', $userId)->delete();
    // print_r($user);die;
    if (!is_null($user)) {
      $status = 200;
    } else {
      $status = 400;
    }
    $data = compact('status');
    return response()->json($data);
  }
}
