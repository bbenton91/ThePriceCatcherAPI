<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\RecentlyAdded;
use App\Models\RecentlyChanged;
use App\Models\MostViewed;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getProducts(Request $request){
        [$skus, $num, $sortBy] = $this->makeParams($request);

        $products = $skus == [] ? Products::select('*') : DB::table('products')->whereIn('products.product_sku', $skus);
        $products = $products->limit(min($num, 100));
        $products = $sortBy[0] != "" ? $products->orderBy($sortBy[0], $sortBy[1]) : $products;
        $products = $products->get();

        return response()->json($products);
    }

    public function getRecentlyAdded(Request $request){
        [$skus, $num, $sortBy] = $this->makeParams($request);

        $products = $skus == [] ? RecentlyAdded::select('*') : DB::table('recently_added')->whereIn('recently_added.product_sku', $skus);
        $products = $products->limit(min($num, 100));
        $products = $sortBy[0] != "" ? $products->orderBy($sortBy[0], $sortBy[1]) : $products;
        $products = $products->join('products', 'products.product_sku', '=', 'recently_added.product_sku');
        $products = $products->get();

        return response()->json($products);
    }

    public function getRecentlyChanged(Request $request){
        [$skus, $num, $sortBy] = $this->makeParams($request);

        $products = $skus == [] ? RecentlyChanged::select('*') : DB::table('recently_changed')->whereIn('recently_changed.product_sku', $skus);
        $products = $products->limit(min($num, 100));
        $products = $sortBy[0] != "" ? $products->orderBy($sortBy[0], $sortBy[1]) : $products;
        $products = $products->join('products', 'products.product_sku', '=', 'recently_changed.product_sku');
        $products = $products->get();

        return response()->json($products);
    }

    public function getMostViewed(Request $request){
        [$skus, $num, $sortBy] = $this->makeParams($request, ["counter"]);

        $products = $skus == [] ? MostViewed::select('*') : DB::table('most_viewed')->whereIn('most_viewed.product_sku', $skus);
        $products = $products->limit(min($num, 100));
        $products = $products->join('products', 'products.product_sku', '=', 'most_viewed.product_sku');
        $products = $sortBy[0] != "" ? $products->orderBy($sortBy[0], $sortBy[1]) : $products;
        $products = $products->get();

        return response()->json($products);
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

        return [$skus, $num, $sortBy];
    }
}
