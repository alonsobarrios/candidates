<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCandidateRequest;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $candidates = Cache::remember('candidates:'.auth()->user()->id, (auth()->factory()->getTTL() * 60), function(){
            return Candidate::all();
        });

        return response()->json([
            'meta' => [ 
                'success' => true, 
                'errors' => []
            ],
            "data" => $candidates
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $errors = $this->validator($request->all());
            if (count($errors) > 0) {
                return response()->json([
                    'meta' => [ 
                        'success' => false, 
                        'errors' => collect($errors)->first()
                    ]
                ], 422);
            }

            $candidate = new Candidate();
            $fields = $request->only($candidate->getFillable());
            $fields['created_by'] = auth()->user()->id;
            $candidate->fill($fields);
            $candidate->save();

            Cache::forget('candidates:'.auth()->user()->id);
            
            return response()->json([
                'meta' => [ 
                    'success' => true, 
                    'errors' => []
                ],
                "data" => $candidate->fresh()
            ], 201);
        } catch (\Exception $exc) {
            Log::error($exc->getMessage());
            return response()->json([
                'meta' => [ 
                    'success' => false, 
                    'errors' => [$exc->getMessage()]
                ]
            ], $exc->getCode());
        }
    }

    /**
     * Validator request.
     *
     * @param  array  $data
     * @return array ErrorsMessages
     */
    private function validator(array $data)
    {
        $validateRequest = new StoreCandidateRequest();
        $validator = Validator::make($data, $validateRequest->rules(), $validateRequest->messages());

        return $validator->errors()->messages();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $candidate = Candidate::findOrFail($id);
            return response()->json([
                'meta' => [ 
                    'success' => true, 
                    'errors' => []
                ],
                "data" => $candidate
            ], 200);
        } catch (\Exception $exc) {
            Log::error($exc->getMessage());
            return response()->json([
                'meta' => [ 
                    'success' => false, 
                    'errors' => ["No lead found."]
                ]
            ], 404);
        }
    }
}
