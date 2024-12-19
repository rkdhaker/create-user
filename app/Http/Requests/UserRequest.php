<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserRequest
{
    
    public static function validate(array $data, string $type)
    {
        switch ($type) {
            case 'setup-profile':
                return self::validateType1($data);
            case 'update-profile':
                return self::validateType2($data);
           
            default:
                throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $data)
    {
        $rules = [
            'first_name'     => 'required|max:30',
            'surname'        => 'nullable|max:30',
          
        ];
        self::failedValidation($data,$rules); 
    }


    private static function validateType2(array $data)
    {
        $rules = [
            'image'     => 'required',
           
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
