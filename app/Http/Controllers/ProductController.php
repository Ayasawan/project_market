<?php
namespace App\Http\Controllers;
use App\Http\Resources\ProductResource;
use App\Models\Product;
//use Carbon\Carbon;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpParser\Lexer\TokenEmulator\ReadonlyTokenEmulator;
class ProductController extends Controller
{
    use ApiResponseTrait;
    //show all product
    //نرتيب تصاعدي
    // $products = ProductResource::collection(Product::get()->sortBy('price'));
    //return $this->apiResponse($products, 'ok', 200);
    public function index()
    {
        //نرتيب تصاعدي
        foreach (Product::query()->get()->sortBy('price') as $exp) {
            $now = Carbon::now();
            $exp_date = Carbon::parse($exp['exp_date']);
            $result = $now->diffInDays($exp_date, false);
            if ($result < 0) {
                $exp->delete();
            }
        }
        $products = ProductResource::collection(Product::get()->sortBy('price'));
        return $this->apiResponse($products, 'ok', 200);
    }
    public function indexx()
    {
        //نرتيب تنازلي
        foreach (Product::query()->get()->sortByDesc('price') as $exp) {
            $now = Carbon::now();
            $exp_date = Carbon::parse($exp['exp_date']);
            $result = $now->diffInDays($exp_date, false);

            if ($result < 0) {
                $exp->delete();
            }
        }
        $products = ProductResource::collection(Product::get()->sortByDesc('price'));

        return $this->apiResponse($products, 'ok', 200);
    }
    public function store(Request $request)
    {
        $input=$request->all();
        $validator = Validator::make( $input, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'exp_date' => 'required',
            'img_url' => ['nullable',],
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $file_name = $this->saveImage($request->img_url, 'images/product');
        $product =Product::query()->create([
            'name' =>$request->name,
            'description' =>$request->description,
            'price' =>$request->price,
            'quantity' =>$request->quantity,
            'exp_date' =>$request->exp_date,
            'img_url' =>$file_name,
            'owner_id' =>auth()->id(),
            'category_id' =>$request->category_id,
        ]);

        foreach ((array)$request->list_discounts as $discount) {
            $product->discounts()->create([
                'date' => $discount['date'],
                'discount_percentage' => $discount['discount_percentage'],
            ]);
        }

        if ($product) {
            return $this->apiResponse(new ProductResource($product), 'the product save', 201);
        }
        return $this->apiResponse(null, 'the product not save', 400);
    }
    //show one product
    public function show($id)
    {
        $product=Product::find($id);
        $product->increment('views');

        $discounts =$product->discounts()->get();
        $maxDiscount = null;

        foreach ($discounts as $discount){
            if (Carbon::parse($discount['date']) <= now()){
                $maxDiscount = $discount;
            }
        }
        //dd();
        if(!is_null($maxDiscount)){
            $discount_value =
                ($product->price*$maxDiscount['discount_percentage'])/100;
            $new_price = $product->price -$discount_value;
        }else{
            $new_price =$product->price;
        }
        $product->setAttribute('current_price',$new_price);


        if(!$product) {
            return $this->apiResponse(null, 'the product not found', 400);
        }
        if($product){
            return  $this->apiResponse(new ProductResource($product), 'OK',201 );
        }
    }
    //update one product
    public function update(Request $request, $id)
    {
        $product= Product::find($id);
      if( $product->owner_id !=Auth::id()){
          return  $this->apiResponse(null ,'the   not right user ',400);
      }
        if(!$product)
        {
            return $this->apiResponse(null ,'the   Product not found ',404);
        }
        $product->update($request->all());
        if($product)
        {
            return $this->apiResponse(new ProductResource($product) , 'the Product update',201);

        }
    }
//delete one product
    public function destroy($id)
    {
        $product = Product::find($id);
        if( $product->owner_id !=Auth::id()){
            return  $this->apiResponse(null ,'the   not right user ',400);
        }
        if (!$product) {
            return $this->apiResponse(null, 'the product not found ', 404);

        }
        $product->delete($id);
        if ($product)
            return $this->apiResponse(null, 'the product delete ', 200);
    }
    //search on one product
    public function search($name)
    {
        $product=Product::where("name","like","%".$name."%")->get();
        if($product) {
            return $this->apiResponse($product, 'ok', 200);
        }
    }
//    public function search(Request $request)
//    {
//        $data = Carbon::now()->toDateTimeString();
//        Product::query()->where('exp_date','<',$data)->delete();
//        $data = $request->get('data');
//        $search = Product::query()->where('name','like',"%{$data}%")
//            ->orWhere('exp_date','like',"%{$data}%")
//            ->orWhere('category_id','like',"%{$data}%")->get();
//
//        if(count($search) == 0){
//            return $this->apiResponse(null,'no result',200);
//        }
//
//        return $this->apiResponse($search,'the product get','200');
//
//    }


}
