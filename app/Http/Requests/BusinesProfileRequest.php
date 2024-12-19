<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class BusinesProfileRequest
{
    
    public static function validate(array $data,string $type)
    {
        switch ($type) {
            case 'create-business':
                return self::validateType1($data);
            case 'gstin_verification':
                return self::validateType2($data);
            case 'aadhaar_verification':
                return self::validateType3($data);
            case 'pan_verification':
                return self::validateType4($data); 
            case 'cin_verification':
                return self::validateType5($data); 
            case 'send_aadhaar_verification':
                return self::validateType6($data);
            case 'busniess_document':
                    return self::validateType7($data);
            case 'busniess_profile':
                    return self::validateType8($data);
            default:

            throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $data)
    {
        $rules = [
            'busines_type' => 'required|in:business,individual', // Validate KYC type
        ];
    
        // Check the business type and add rules accordingly
        if (isset($data['busines_type']) && $data['busines_type'] === 'business') {
            $rules = array_merge($rules, [
                'name'               => 'required|string|max:255',
                'type'               => 'required|in:proprietor,partnership,company',
                'address'            => 'required|string|max:255',
                'email'              => 'required|email',
                'mobile_no'          => 'required|numeric|digits:10',
                'person_name'        => 'required|string|max:255',
                'contact_number'     => 'required|numeric|digits:10',
            ]);
        } elseif (isset($data['busines_type']) && $data['busines_type'] === 'individual') {
            $rules = array_merge($rules, [
                'name'  => 'required|string|max:255',
                'address'    => 'required|string|max:255',
                'mobile_no'  => 'required|numeric|digits:10',
                'email'      => 'required|email',
            ]);
        }
    
        self::failedValidation($data, $rules);
    }
    private static function validateType2(array $data)
    {
        $rules = [
            'value' => 'required|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[A-Z0-9]{1}[Z]{1}[A-Z0-9]{1}$/',
            
        ];
	
        self::failedValidation($data,$rules); 
    }

    private static function validateType3(array $data)
    {
        $rules = [
            'value' => 'required|regex:/^\d{12}$/',
            'otp'   => 'required|numeric|digits:6',
        ];
	
        self::failedValidation($data,$rules); 
    }

    private static function validateType4(array $data)
    {
        $rules = [
            'value' => 'required|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', 
            
        ];
	
        self::failedValidation($data,$rules); 
    }

    private static function validateType5(array $data)
    {
        $rules = [
            'value' => 'required|regex:/^[LU]{1}[0-9]{5}[A-Z]{2}[0-9]{4}[A-Z]{3}[0-9]{6}$/',
        ];
	
        self::failedValidation($data,$rules); 
    }

    private static function validateType6(array $data)
    {
        $rules = [
            'value' => 'required|regex:/^\d{12}$/',
        ];
	
        self::failedValidation($data,$rules); 
    }

    private static function validateType7(array $data)
    {
        $rules = [
            'value' =>'required|file|max:2048',
        ];
	
        self::failedValidation($data,$rules); 
    }

    private static function validateType8(array $data)
    {
        $rules = [
            'value' =>'required|file|max:2048',
        ];
	
        self::failedValidation($data,$rules); 
    }


    private static function failedValidation($data,$rules)
    {

        
        $validator = Validator::make($data, $rules)->getMessageBag()->first();
        if($validator){
            throw new HttpResponseException(response()->json([
                'status'    => false,
                'message'   => $validator,
    
            ]));
        }  
    }
}
