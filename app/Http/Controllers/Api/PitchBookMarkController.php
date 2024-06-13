<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PitchBookMarkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookmark_pitches = $this->PitchesBookMarkObj->getPitchesBookMark([
            'user_id' => \Auth::user()->id
        ])->ToArray();

        $get_pitch_ids = array_column($bookmark_pitches, 'pitch_id');
        $data = $this->PitchObj->getPitch([
            'ids_in' => $get_pitch_ids,
            'paginate' => 15,
            'orderBy_name' => 'pitches.id',
            'orderBy_value' => 'DESC'
        ]);
        return $this->sendResponse($data, 'Pitches bookmark list');
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
        $requested_data = $request->all();
        $rules = array(
            'pitch_id' => 'required|exists:pitches,id',
        );
        $validator = \Validator::make($requested_data, $rules);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), ["error" => $validator->errors()->first()]);
        }
        $requested_data['user_id'] =  \Auth::user()->id;

        $pitch_detail = $this->PitchObj->getPitch([
            'id' => $requested_data['pitch_id'],
            'detail' => true
        ]);
        $pitch_bookmark_record = $this->PitchesBookMarkObj->getPitchesBookMark([
            'user_id' => \Auth::user()->id,
            'pitch_id' => $requested_data['pitch_id'],
            'detail' => true
        ]);
        if (!$pitch_bookmark_record) {
            $this->PitchObj->saveUpdatePitch([
                'update_id' => $requested_data['pitch_id'],
                'bookmark_count' => ($pitch_detail->bookmark_count) + 1
                // 'bookmark_count' => count($data->ToArray()) + $pitch_detail->shares_count,
            ]);
            $data = $this->PitchesBookMarkObj->saveUpdatePitchesBookMark($requested_data);
            return $this->sendResponse($data, 'Bookmark pitch successfully');
        }
        else{
            return $this->sendError("error", 'You already bookedmark');
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
        $pitchBookmark = $this->PitchesBookMarkObj->getPitchesBookMark([
            'id' => $id,
            'user_id' => \Auth::user()->id,
            'detail' => true,
        ]);
        if ($pitchBookmark) {
            $pitch_detail = $this->PitchObj->getPitch([
                'id' => $pitchBookmark->pitch_id,
                // 'user_id' => \Auth::user()->id,
                'detail' => true,
            ]);
            if ($pitch_detail->bookmark_count > 0) {
                $this->PitchObj->saveUpdatePitch([
                    'update_id' => $pitchBookmark->pitch_id,
                    'bookmark_count' => ($pitch_detail->bookmark_count) - 1
                ]);
                $data = $this->PitchesBookMarkObj->deletePitchesBookMark($id);
                return $this->sendResponse($data, 'Remove from bookmark successfully');
            }
            else{
                return $this->sendError("error", "You already removed from booked mark");
            }
        }else{
            return $this->sendError("error", "Record not found");
        }
    }
}
