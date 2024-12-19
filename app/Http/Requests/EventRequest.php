<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class EventRequest
{
    
    public static function validate(array $request,string $type)
    {
        switch ($type) {
            case 'create-event':
                return self::validateType1($request);
            case 'create-address':
                return self::validateType2($request);
            case 'create-invite':
                return self::validateType3($request);
            case 'create-key-people':
                return self::validateType4($request);
            case 'edit-key-people':
                return self::validateType5($request);
            case 'update-key-people':
                return self::validateType6($request);
            case 'delete-key-people':
                return self::validateType7($request);
            case 'list-key-people':
                return self::validateType8($request);
            case 'get-evant':
                return self::validateType9($request);
            case 'get-category':
                return self::validateType10($request);
            case 'list-event':
                return self::validateType11($request);
            case 'send-request':
                return self::validateType12($request);
            case 'accept-request':
                return self::validateType13($request);
            case 'sub-event':
                return self::validateType14($request);
            case 'edit-event':
                return self::validateType15($request);
            case 'update-event':
                return self::validateType16($request);
            default:
            throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $request)
    {
        $rules = [
            'name'         =>   'required',
            'start_date'   =>   'required|date_format:Y-m-d',
            'start_time'   =>   'required|date_format:H:i',
            'end_date'     =>   'required|date_format:Y-m-d',
            'end_time'     =>   'required|date_format:H:i',
            'description'  =>  'required',
            'file.*'       =>   'required|mimetypes:image/*', 
        ];

        // if(count($request['file']) != count(json_decode($request['file_size'],true))){
        //     $rules['file_size'] = 'required|min:'.count($request['file']);
        // }

       
	
        self::failedValidation($request,$rules); 
    }

    private static function validateType2(array $request)
    {
        $rules = [
            'event_id'     =>   'required|exists:events,id',
            'venue'        =>   'required',
            'address'      =>   'required',
            'city'         =>   'required',
            'pincode'      =>   'nullable|digits:6',
            'latitude'     =>   'required',
            'longitude'    =>   'required'
            
        ];

        self::failedValidation($request,$rules); 
    }


    private static function validateType3(array $request)
    {
        $rules = [
            'event_id'     =>   'required|exists:events,id',
            'file'         =>    'required|mimetypes:image/*', 
            
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType4(array $request)
    {
        $rules = [
            'event_id'     =>   'required|exists:events,id',
            'name'         =>    'required', 
            'type'         =>    'required', 
            'file'         =>    'required|mimetypes:image/*',
            
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType5(array $request)
    {
        $rules = [
            'key_people_id'     =>   'required|exists:event_keypeoples,id',  
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType6(array $request)
    {
        $rules = [
            'key_people_id'     =>   'required|exists:event_keypeoples,id',
            'name'              =>    'required', 
            'type'              =>    'required', 
            'file'              =>    'required|mimetypes:image/*', 
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType7(array $request)
    {
        $rules = [
            'key_people_id'     =>   'required|exists:event_keypeoples,id',  
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType8(array $request)
    {
        $rules = [
            'event_id'     =>   'required|exists:events,id',
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType9(array $request)
    {
        $rules = [
            'event_id'     =>   'required',
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType10(array $request)
    {
        $rules = [
            'is_public'     =>   'required|in:0,1',
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType11(array $request)
    {
        $rules = [
            'type'     =>   'required|in:public,private',
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType12(array $request)
    {
        $rules = [
            'event_id'     =>   'required|exists:events,id',
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType13(array $request)
    {
        $rules = [
            'people_id'     =>   'required|exists:event_peoples,id',
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType14(array $request)
    {
        $rules = [
            'sub_event_id'     =>   'required|exists:event_subevents,id',
        ];

        self::failedValidation($request,$rules); 
    }

    private static function validateType15(array $request)
    {
        $rules = [
            'event_id'     =>   'required|exists:events,id',  
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType16(array $request)
    {
        $rules = [
            'event_id'     =>   'required|exists:events,id',
            'name'         =>   'required',
            'start_date'   =>   'required|date_format:Y-m-d',
            'start_time'   =>   'required|date_format:H:i',
            'end_date'     =>   'required|date_format:Y-m-d',
            'end_time'     =>   'required|date_format:H:i',
            'description'  =>   'required',
            'venue'        =>   'required',
            'address'      =>   'required',
            'city'         =>   'required',
            'pincode'      =>   'nullable|digits:6',
            'latitude'     =>   'required',
            'longitude'    =>   'required'
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
