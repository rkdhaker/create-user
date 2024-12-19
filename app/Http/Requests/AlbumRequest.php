<?php
namespace App\Http\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class AlbumRequest
{
    
    public static function validate(array $request,string $type)
    {
        switch ($type) {
            case 'create-album':
                return self::validateType1($request);
            case 'edit-album':
                return self::validateType2($request);
            case 'update-album':
                return self::validateType3($request);
            case 'delete-album':
                return self::validateType4($request);
            case 'list-album':
                return self::validateType5($request);
            case 'delete-gallery':
                return self::validateType6($request);
            default:
            throw new \InvalidArgumentException("Invalid validation type: $type");
        }
    }

    private static function validateType1(array $request)
    {
        $rules = [
            'event_id'      =>   'required|exists:events,id',
            'name'          =>   'required',
        ];
        self::failedValidation($request,$rules); 
    }

    
    private static function validateType2(array $request)
    {
        $rules = [
            'album_id'     =>   'required|exists:albums,id',  
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType3(array $request)
    {
        $rules = [
            'album_id' => 'required|exists:albums,id',
            'name'   =>   'required',
        ];
        self::failedValidation($request,$rules); 
    }

    private static function validateType4(array $request)
    {
        $rules = [
            'album_id'     =>   'required|exists:event_subevents,id',  
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

    private static function validateType6(array $request)
    {
        $rules = [
            'post_id'     =>   'required|exists:post_media,id',
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
