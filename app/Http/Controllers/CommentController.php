<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
//use Ramsey\Uuid\Rfc4122\Validator;
class CommentController extends Controller
{
    use ApiResponseTrait;
    public function index( Product $product)
    {
        $comments = $product->comments()->get();
        return response()->json($comments);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            "value" => ['required', 'string','min:1', 'max:400'],
        ]);
        $comment = $product->comments()->create([
            'value' => $request->value ,
            'owner_id' => Auth::id(),
        ]);
        return response()->json($comment);
    }
    public function update(Request $request, $id ,$id_comment)
    {
        $validator=Validator::make($request->all(),[
            'value'  => [ 'required' ,'string' ,'min:1' ]
        ]);
        if($validator->fails()) {
            return $this->apiResponse(null ,$validator->errors(),400);}
        $product= Product::find($id);
        if(!$product) {
            return $this->apiResponse(null ,'the   Product not found ',404);
        }
        $comment=Comment::find($id_comment);
        if(!$comment)
        {
            return $this->apiResponse(null ,'the  comment not found ',404);
        }
        $comment->update($request->all());
      return $this->apiResponse(new CommentResource($comment) , 'the comment update',201);

//        $comment->update($comment->all());
//        if($comment)
//        {
//            return $this->apiResponse(new CommentResource($comment) , 'the comment update',201);
//        }
    }
    public function destroy($id)
    {
        $comment = Comment::find($id);

        if (!$comment) {
            return $this->apiResponse(null, 'the comment not found ', 404);

        }
        $comment->delete($id);
        if ($comment)
            return $this->apiResponse(null, 'the comment delete ', 200);
    }


}
