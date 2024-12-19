<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class RestaurantRequest
{
    
    public static function validate(array $data, string $type)
    {
        switch ($type) {
            case 'detail':
                return self::validateType1($data);
            case 'follow-unfollow':
                return self::validateType2($data); 
            case 'submit-review':
                return self::validateType3($data);  
            case 'get-follower':
                return self::validateType4($data);        
            default:
                throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $data)
    {
        $rules = [
            'restaurant_id'     => 'required|exists:restaurants,id',
        ];
        self::failedValidation($data,$rules); 
    }

    private static function validateType2(array $data)
    {
        $rules = [
            'restaurant_id'     => 'nullable|exists:restaurants,id',
            'event_id'          => 'nullable|exists:events,id'
        ];
        self::failedValidation($data,$rules); 
    }

    private static function validateType3(array $data)
    {
        $rules = [
            'restaurant_id'  =>  'required|exists:restaurants,id',
            'rating'         =>  'required_if:owner,0',
            'review_id'      =>  'required_if:owner,1|exists:reviews,id',
            'owner'          =>  'required|in:0,1',
        ];
        self::failedValidation($data,$rules); 
    }

    private static function validateType4(array $data)
    {
        $rules = [
            'restaurant_id'     => 'nullable|exists:restaurants,id',
            'event_id'          => 'nullable|exists:events,id'
        ];
        self::failedValidation($data,$rules); 
    }



    private static function failedValidation($data,$rules)
    {
        $validator = Validator::make($data, $rules)->getMessageBag()->first();
        if($validator){
            throw new HttpResponseException(response()->json([
                'status'   => false,
                'message'   => $validator,
    
            ]));
        }
       
    }
}
