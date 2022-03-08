<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
    use ApiResponseTrait;
    // view all a products
    public function index()
    {
       $categories=  CategoryResource::collection(Category::get());
       return $this->apiResponse($categories, 'ok',200);
    }
    //add a product
    public function store(Request $request)
    {
        $validator= Validator::make($request->all(),[
            'name'  => 'required' ,
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null ,$validator->errors(),400);
        }
        $category = Category::create($request->all());
        if($category){
            return  $this->apiResponse(new CategoryResource($category), 'the category save',201 );
        }
        return  $this->apiResponse(null , 'the category not save' ,400);
    }
    // view a one product
    public function show($id)
    {
        $category= Category::find($id);
        if($category){
         return $this->apiResponse(new CategoryResource($category) , 'ok' ,200);
       }
        return $this->apiResponse(null ,'the category not found' ,404);

    }
    //update on product
    public function update(Request $request, $id)
    {
        $validator= Validator::make($request->all(),[
            'name'  => 'required' ,
        ]);
        if($validator->fails())
        {
            return $this->apiResponse(null ,$validator->errors(),400);
        }
        $category= Category::find($id);
        if(!$category)
        {
            return $this->apiResponse(null ,'the category not found ',404);
        }
        $category->update($request->all());
       if($category)
{
    return $this->apiResponse(new CategoryResource($category) , 'the category update',201);

}
    }
    // delete one product
    public function destroy($id)
    {
        $category= Category::find($id);
  if(!$category)
  {
      return $this->apiResponse(null ,'the category not found ',404);
  }
        $category->delete($id);
        if($category)
         return $this->apiResponse(null ,'the category delete ',200);
        }
    public function search($name)
    {
        return Category::where("name","like","%".$name."%")->get();
    }
    }
