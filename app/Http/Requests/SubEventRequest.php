<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class SubEventRequest
{
    
    public static function validate(array $request,string $type)
    {
        switch ($type) {
            case 'create-sub-event':
                return self::validateType1($request);
            case 'edit-sub-event':
                return self::validateType2($request);
            case 'update-sub-event':
                return self::validateType3($request);
            case 'delete-sub-event':
                return self::validateType4($request);
            case 'list-sub-event':
                return self::validateType5($request);
            default:
            throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $request)
    {
        $rules = [
            'event_id'      =>   'required|exists:events,id',
            'name'          =>   'required',
            'date'          =>   'required|date_format:Y-m-d',
            'time'          =>   'required|date_format:H:i',
            'end_date'      =>   'required|date_format:Y-m-d',
            'end_time'      =>   'required|date_format:H:i',
            'description'   =>  'required',
            'file'          =>   'required|mimetypes:image/jpeg,image/png', 
            'venue'         =>   'required',
            'address'       =>   'required',
            'city'          =>   'required',
            'pincode'       =>   'nullable|digits:6',
            'latitude'      =>   'required',
            'longitude'     =>   'required'
        ];

        // if(count($request['file']) != count(json_decode($request['file_size'],true))){
        //     $rules['file_size'] = 'required|min:'.count($request['file']);
        // }

       
	
        self::failedValidation($request,$rules); 
    }

    
    private static function validateType2(array $request)
    {
        $rules = [
            'sub_event_id'     =>   'required|exists:event_subevents,id',  
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType3(array $request)
    {
        $rules = [
            'sub_event_id' => 'required|exists:event_subevents,id',
            'name'   =>   'required',
            'date'   =>   'required|date_format:Y-m-d',
            'time'   =>   'required|date_format:H:i',
            'end_date'      =>   'required|date_format:Y-m-d',
            'end_time'      =>   'required|date_format:H:i',
            'description'  =>  'required',
            'file'         =>   'nullable|mimetypes:image/jpeg,image/png', 
            'venue'        =>   'required',
            'address'      =>   'required',
            'city'         =>   'required',
            'pincode'      =>   'required|digits:6',
            'latitude'     =>   'required',
            'longitude'    =>   'required'
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType4(array $request)
    {
        $rules = [
            'sub_event_id'     =>   'required|exists:event_subevents,id',  
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType5(array $request)
    {
        $rules = [
            'event_id'     =>   'required|exists:events,id',
        ];

        self::failedValidation($request,$rules); 
    }


    


  


    private static function failedValidation($request,$rules)
    {
        $validator = Validator::make($request, $rules)->getMessageBag()->first();
        if($validator){
            throw new HttpResponseException(response()->json([
                'status'    => false,
                'message'   => $validator,
    
            ]));
        }  
    }
}
