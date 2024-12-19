<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class BookingTicketRequest
{
    
    public static function validate(array $request,string $type)
    {
        switch ($type) {
            case 'add-ticket':
                return self::validateType1($request);
            case 'delete-ticket':
                    return self::validateType2($request); 
            default:
				
				
                throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $request)
    {
        $rules = [
            'event_id'          =>   'required',
            'price'             =>   'required',
            'max_quantity'      =>   'required',
            'per_user_quantity' =>   'required',
            'name'              =>  'required',
            
        ];
	
        self::failedValidation($request,$rules); 
    }

    private static function validateType2(array $request)
    {
        $rules = [
            'ticket_id'          =>   'required|exists:tickets,id', 
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
