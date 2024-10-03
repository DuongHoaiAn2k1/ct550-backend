<?php

namespace App\Http\Controllers\API\Affiliate;

use Illuminate\Http\Request;
use App\Models\AffiliateLink;
use App\Http\Controllers\Controller;

class AffiliateLinkController extends Controller
{
    public function generateLink($product_id)
    {
        try {
            $userId = auth()->user()->id;
            $existLink = AffiliateLink::where('affiliate_user_id', $userId)->where('product_id', $product_id)->first();
            if ($existLink) {
                return response()->json([
                    'success' => false,
                    'message' => 'Affiliate link already exists',
                    'data' => $existLink->affiliate_link
                ], 400);
            }
            $link = $this->generateAffiliateLink($userId, $product_id);

            $affiliateLink = new AffiliateLink();
            $affiliateLink->affiliate_link = $link;
            $affiliateLink->affiliate_user_id = $userId;
            $affiliateLink->product_id = $product_id;
            $affiliateLink->save();
            return response()->json([
                'success' => true,
                'message' => 'Affiliate link generated successfully',
                'data' => $link
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getByUser()
    {
        try {
            $userId = auth()->user()->id;
            $links = AffiliateLink::where('affiliate_user_id', $userId)->with('product')->with('product.commission')->get();
            return response()->json([
                'success' => true,
                'data' => $links
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    private function generateAffiliateLink($userId, $productId)
    {
        $baseUrl = 'https://client.dacsancamau.com:3001/product/detail/' . $productId;

        return $baseUrl . '?ref=' . $userId;
    }
}
