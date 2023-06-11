<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetAtlasCodeRequest;
use App\Models\JahadiGroups;
use App\Models\SubmittedWorks;
use Illuminate\Http\Request;

class CoreController extends Controller
{
  public function getAtlasCode(GetAtlasCodeRequest $request)
  {
    $jahadiGroup = JahadiGroups::where([
      'group_supervisor_national_code' => $request->group_supervisor_national_code,
    ])->first();
    if ($jahadiGroup) {
      return $this->success(
        null,
        message: 'کد اطلس گروه جهادی ' . $jahadiGroup->group_name . ' ' . $jahadiGroup->group_registeration_number . ' می باشد.',
      );
    } else {
      return $this->error(
        null,
        message: 'گروه جهادی یافت نشد',
        code: 400,
      );
    }
  }

  public function getSubmittedWorks(Request $request)
  {
    if ($request->get('token') == 'wyzMjqDXeeaGWJVgdysutJ6C9E3MX11t38LD2K60') {
      $submittedWorks = SubmittedWorks::with('jahadiGroups', 'individuals', 'groups')
        ->get();
      return $this->success(data: $submittedWorks);
    } else {
      return $this->error(
        data: null,
        message: 'توکن صحیح نمی باشد',
        code: 403
      );
    }
  }

  public function download($filename)
  {
    $path = storage_path('app/uploads/' . $filename);

    if (file_exists($path)) {
      return response()->download($path);
    } else {
      abort(404);
    }
  }
}
