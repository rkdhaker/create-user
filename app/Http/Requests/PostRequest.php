<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class PostRequest
{
    
    public static function validate(array $request,string $type)
    {
        switch ($type) {
            case 'create-post':
                return self::validateType1($request); 
            case 'add-question':
                return self::validateType2($request);  
            case 'get-post':
                return self::validateType3($request); 
            case 'like-dislike':
                return self::validateType4($request); 
            case 'report':
                return self::validateType5($request);   
            case 'add-poll':
                return self::validateType6($request);    
			case 'delete-post':
                return self::validateType7($request);    
            default:
				
				
                throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $request)
    {
        $rules = [
            'evant_id'          =>   'nullable|exists:evants,id',
            'restaurant_id'     =>   'nullable|exists:restaurants,id',
            'file.*'            =>   'nullable|mimetypes:image/*,video/*', 
            
        ];

		
		
		
        // foreach ($request['file'] as $file) {
        //     if ($file->getClientMimeType() === 'video/mp4') {
        //         $rules['cover_image'] = 'required|min:'.count($request['file']);
        //         break;
        //     }
        // }
		
		
       
        // if(count($request['file']) != count(json_decode($request['file_size'],true))){
        //     $rules['file_size'] = 'required|min:'.count($request['file']);
        // }

       
	
        self::failedValidation($request,$rules); 
    }


    private static function validateType2(array $request)
    {
        $rules = [
            'restaurant_id' =>   'nullable|exists:restaurants,id',
            'event_id'      =>   'nullable|exists:events,id',
            'question'      =>   'required',
            'days'          =>   'required|integer',
            'option_1'      =>   'required',
            'option_2'      =>   'required',
            'file'          =>   'nullable|mimetypes:image/*,video/*', 
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType3(array $request)
    {
        $rules = [
            'restaurant_id' =>   'nullable|exists:restaurants,id',
			'event_id' 		=>   'nullable|exists:events,id',
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType4(array $request)
    {
        $rules = [
            'post_id'  =>   'required|exists:posts,id',
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType5(array $request)
    {
        $rules = [
            'post_id'         =>   'required|exists:posts,id',
            'report_id'       =>   'nullable|exists:reports,id',
            'report_post_id'  =>   'nullable|exists:reports,id',

        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType6(array $request)
    {
        $rules = [
            'option_id'         =>   'required|exists:restaurant_question_options,id',
            'post_id'           =>   'required|exists:posts,id',
            'restaurant_id'     =>   'nullable|exists:restaurants,id',
        ];
        self::failedValidation($request,$rules); 
    }
	
	private static function validateType7(array $request)
    {
        $rules = [
            'post_id'         =>   'required|exists:posts,id',  
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
