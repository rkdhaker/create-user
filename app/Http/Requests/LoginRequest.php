<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class LoginRequest
{
    
    public static function validate(array $data, string $type)
    {
        switch ($type) {
            case 'send-otp':
                return self::validateType1($data);
            case 'otp-verification':
                return self::validateType2($data);
            default:
                throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $data)
    {
        $rules = [
            'type'     => 'required|in:0,1',
            'phone_no' => 'required_if:type,0|digits:10',
            'email'    => 'required_if:type,1|email',
        ];
        self::failedValidation($data,$rules); 
    }

    private static function validateType2(array $data)
    {
        $rules = [
            'token'    => 'required|exists:otp_verifications,token',
            'otp'      => 'required|digits:5',
            'type'     => 'required|in:0,1',
            'phone_no' => 'required_if:type,0|exists:otp_verifications,phone_no',
            'email'    => 'required_if:type,1|exists:otp_verifications,email',
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
