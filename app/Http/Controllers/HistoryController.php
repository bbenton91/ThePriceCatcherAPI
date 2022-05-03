<?php

namespace App\Http\Controllers;

use App\Models\PriceHistory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HistoryController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getHistory(Request $req){
        [$skus, $num, $skip, $sortBy] = $this->makeParams($req, ["counter"]);

        if($skus == [])
            return response()->json(["error" => "Skus must be supplied for this API call"]);

        $productJoin = DB::table('products')->select(['product_sku', 'product_name', 'description', 'product_url', 'image_url', 'department_id']);

        $products = DB::table('price_histories')->whereIn('price_histories.product_sku', $skus);
        $products = $products->offset($skip)->take($num); // We get 1 extra result just to check if we have more waiting
        $products = $products->joinSub($productJoin, 'pr', 'pr.product_sku', '=', 'price_histories.product_sku');
        $products = $sortBy[0] != "" ? $products->orderBy($sortBy[0], $sortBy[1]) : $products;
        $products = $products->get();

        return $this->buildResponse($products, $num);
    }

    private function buildResponse(Collection $products, int $num){
        $sliced = $products->slice(0, $num);

        $finalProducts = []; // Our map of sku => products
        foreach($sliced as $product){
            if(!isset($finalProducts[$product->product_sku])){
                $finalProducts[$product->product_sku] = [];
                $finalProducts[$product->product_sku]['info'] = [
                    'product_sku' => $product->product_sku,
                    'product_name' => $product->product_name,
                    'description' => $product->description,
                    'product_url' => $product->product_url,
                    'image_url' => $product->image_url,
                    'department_id' => $product->department_id,
                ];
                $finalProducts[$product->product_sku]['history'] = [];
            }

            $finalProducts[$product->product_sku]['history'][] = [
                'regular_price' => $product->regular_price,
                'sale_price' => $product->sale_price,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
                'start_date' => $product->start_date,
            ];
        }

        $response = [
            "result" => "success",
            "count" => min(count($sliced), 100),
            "products" => $finalProducts,
            "more" => count($products) > $num
        ];

        return response()->json($response);
    }

    private function makeParams(Request $request, array $extraSortOptions = []){
        $skus = $request->input('skus', '()');
        if(strlen($skus)){
            $skus = substr($skus, 1);
            $skus = substr($skus, 0, strlen($skus)-1);
            $skus = explode(',', $skus);
            if(count($skus) == 1 && $skus[0] == "")
                $skus = [];
            else
                for ($i=0; $i < count($skus); $i++) { 
                    $skus[$i] = intval($skus[$i]);
                }
        }
        
        $num = $request->input('num', 100);
        $skip = $request->input('skip', 0);

        $sortOptions = ["product_sku","regular_price","sale_price","department_id","created_at","updated_at"];
        $sortOptions = array_merge($sortOptions, $extraSortOptions);

        // sortBy should be received in the format (sale_price,asc)
        $sortBy = $request->input('sort', "");
        if(strlen($sortBy)){
            $sortBy = substr($sortBy, 1);
            $sortBy = substr($sortBy, 0, strlen($sortBy)-1);
            $sortBy = explode(',', $sortBy);

            // We make sure it's a valid choice to sort by
            if(!in_array($sortBy[0], $sortOptions)){
                $sortBy[1] = ""; // Set both of these to empty to invalidate it
                $sortBy[0] = "";
            }

            // We make sure it's a valid sorting type
            switch($sortBy[1]){
                case "asc":
                case "desc":
                    break;
                default:
                    $sortBy[1] = ""; // Set both of these to empty to invalidate it
                    $sortBy[0] = "";
                    break;
            }
        }else{
            $sortBy = ["", ""];
        }

        return [$skus, $num, $skip, $sortBy];
    }
}
