<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class NotificationRequest
{
    
    public static function validate(array $request,string $type)
    {
        switch ($type) {
            case 'create-notification':
                return self::validateType1($request);
            case 'get-notification':
                return self::validateType2($request);
           
            default:
            throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $request)
    {
        $rules = [
            'event_id'      =>   'nullable|exists:events,id',
            'message'       =>   'required',
            'time'          =>   'nullable|date_format:H:i',
            'date'          =>   'nullable|date_format:Y-m-d',
            'restaurant_id' =>   'nullable|exists:restaurants,id',
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType2(array $request)
    {
        $rules = [
            'event_id'      =>   'nullable|exists:events,id',
            'restaurant_id' =>   'nullable|exists:restaurants,id',
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
