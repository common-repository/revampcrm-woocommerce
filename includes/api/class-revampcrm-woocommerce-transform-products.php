<?php

/**
 * Created by Vextras. 
 * Modified By RevampCo.
 *
 * Name: Ryan Hungate
 * Email: ryan@mailchimp.com
 * Date: 7/13/16
 * Time: 8:29 AM
 * 
 * Name: Waleed Meligy
 * Email: wmeligy@revampco.com
 * Date: 10/15/17
 * Time: 8:29 AM
 * 
 */

class RevampCRM_WooCommerce_Transform_Products
{
    protected $startingFrom = null;
    function __construct($startingFrom=null) {
       $this->startingFrom = $startingFrom;
   }
    /**
     * @param int $page
     * @param int $limit
     * @return \stdClass
     */
    public function compile($page = 1, $limit = 5,$startSyncTime=null)
    {
        $this->startingFrom = $startSyncTime;
        $response = (object) array(
            'endpoint' => 'products',
            'page' => $page ? $page : 1,
            'limit' => (int) $limit,
            'count' => 0,
            'stuffed' => false,
            'items' => array(),
        );

        if ((($products = $this->getProductPosts($page, $limit)) && !empty($products))) {
            foreach ($products as $post) {
                try{

                    $response->items[] = $this->transform($post);
                    
                }catch(\Exception $e){
                    revampcrm_debug("revampcrm.transformproduct"," Post ".$post->ID ."  -> ". $e->getMessage(),$e->getTraceAsString());
                }
                $response->count++;
                
            }
        }

        $response->stuffed = ($response->count > 0 && (int) $response->count === (int) $limit) ? true : false;

        return $response;
    }

    

    
    /**
     * @param WP_Post $post
     * @return ECommerceProductApiModel
     */
    public function transform(WP_Post $post)
    {
        $woo = new WC_Product($post);

        $variant_posts = $this->getProductVariantPosts($post->ID);

        $variants = $variant_posts ? array_merge(array($woo), $variant_posts) : array($woo);

        $is_variant = count($variants) > 1;

        $product = new \RevampCRM\Models\ECommerceProductApiModel();

/*
 '_id' => 'string',
        'account_id' => 'string',
        'contact_id' => 'string',
        'user_id' => 'string',
        //'created_on' => '\DateTime',
        //'updated_on' => '\DateTime',
        'vendor' => 'string',
        //'e_commerce_provider_name' => 'string',
        //'provider_store_id' => 'string',
        //'provider_product_id' => 'string',
        //'provider_product_name' => 'string',
        'product_type' => 'string',
        'tags' => 'string',
        'variants' => '\RevampCRM\Models\ECommerceProductVariant[]',
        'categories' => 'string[]',
        //'image' => 'string',
        'product_handle' => 'string'
        */
        // do_action( 'logger',$post,'debug');
        $product->setProviderProductId($post->ID);
        $product->setProductHandle(empty($post->post_name)?$post->post_title:$post->post_name);
        $product->setProviderProductName(empty($post->post_name)?$post->post_title:$post->post_name );
        $image = get_the_post_thumbnail_url($post);
        if($image !== false && !empty($image)){
            $product->setImage($image);
        }
        //$product->setDescription($post->post_content);
        $product->setCreatedOn(revampcrm_date_utc($post->post_date));
        $product->setUpdatedOn(revampcrm_date_utc($post->post_date));
        $product->setECommerceProviderName("WooCommerce");
        $product->setProviderStoreId(revampcrm_get_store_id());
        //$product->setUrl($woo->get_permalink());

        $variantsToAdd =[];
        foreach ($variants as $variant) {

            $product_variant = $this->variant($is_variant, $variant, $woo->get_title());

            $product_variant_title = $product_variant->getName();

            if (empty($product_variant_title)) {
                $product_variant->setName($woo->get_title());
            }

            //$product_variant_image = $product_variant->getImageUrl();

            // if (empty($product_variant_image)) {
            //     $product_variant->setImageUrl($product->getImageUrl());
            // }

            $variantsToAdd[] =$product_variant;
        }
        $product->setVariants($variantsToAdd);

        // Do the Categories
        $categories = get_the_terms( $post->ID, 'product_cat' );
        $categories_to_add = [];
        if(is_array($categories)){
            foreach ($categories as $term) {
                $new_cat = new \RevampCRM\Models\ECommerceCategoryApiModel();
                $new_cat->setECommerceProviderName('WooCommerce');
                $new_cat->setCreatedOn(revampcrm_date_utc($post->post_date));
                $new_cat->setUpdatedOn(revampcrm_date_utc($post->post_date));
                $new_cat->setProviderStoreId(revampcrm_get_store_id());
                $new_cat->setProviderCategoryHandle($term->slug);
                $new_cat->setProviderCategoryId($term->term_id);
                $new_cat->setTitle($term->name);
                $new_cat->setDescription($term->description);
                $categories_to_add[] = $new_cat;
            }        
        }
        $product->setCategories($categories_to_add);
        return $product;
    }

    /**
     * @param $is_variant
     * @param WP_Post $post
     * @param string $fallback_title
     * @return \RevampCRM\Models\ECommerceProductVariant
     */
    public function variant($is_variant, $post, $fallback_title = null)
    {
        if ($post instanceof WC_Product || $post instanceof WC_Product_Variation) {
            $woo = $post;
        } else {
            if (isset($post->post_type) && $post->post_type === 'product_variation') {
                $woo = new WC_Product_Variation($post->ID);
            } else {
                $woo = new WC_Product($post);
            }
        }

        $variant = new \RevampCRM\Models\ECommerceProductVariant();

/*        'provider_variant_id' => 'string',
        'name' => 'string',
        'sku' => 'string',
        'price' => 'string'
        */
        $variant->setProviderVariantId($woo->get_id());
        //$variant->setUrl($woo->get_permalink());
        //$variant->setBackorders($woo->backorders_allowed());
        //$variant->setImageUrl(get_the_post_thumbnail_url($post));
        //$variant->setInventoryQuantity(($woo->managing_stock() ? $woo->get_stock_quantity() : 1));
        $variant->setPrice($woo->get_price());
        $variant->setSku($woo->get_sku());

        if ($woo instanceof WC_Product_Variation) {

            $variation_title = $woo->get_title();
            if (empty($variation_title)) $variation_title = $fallback_title;

            $title = array($variation_title);

            foreach ($woo->get_variation_attributes() as $attribute => $value) {
                if (is_string($value)) {
                    $name = ucfirst(str_replace(array('attribute_pa_', 'attribute_'), '', $attribute));
                    $title[] = "$name = $value";
                }
            }

            $variant->setName(implode(' :: ', $title));
            //$variant->setVisibility(($woo->variation_is_visible() ? 'visible' : ''));
        } else {
            //$variant->setVisibility(($woo->is_visible() ? 'visible' : ''));
            $variant->setName($woo->get_title());
        }

        return $variant;
    }

    /**
     * @param int $page
     * @param int $posts
     * @return array|bool
     */
    public function getProductPosts($page = 1, $posts = 5)
    {
        if(false && function_exists("wc_get_products")){
            $filters = array(
                // 'post_type' => array_merge(array_keys(wc_get_product_types()), array('product')),
                // 'posts_per_page' => $posts,
                'paged' => $page,
                'orderby' => 'ID',
                'order' => 'ASC',
                'status' => array_diff(get_post_stati(), ['inherit','trash','auto-draft']),
            );
            if(!empty($this->startingFrom) && @is_a($this->startingFrom,'DateTime')){
                try{
                    $filters["date_modified"] = ">= ". $this->startingFrom->sub(date_interval_create_from_date_string('1 day'))->format('Y-m-d H:i:s T');
                }catch(\Exception $ex){

                }
            }
            $products = wc_get_products($filters);

            if (empty($products)) {

                sleep(2);

                $products = wc_get_products($filters);

                if (empty($products)) {
                    return false;
                }
            }

            return $products;
            
        }else{
            $filters = array(
                'post_type' => array_merge(array_keys(wc_get_product_types()), array('product')),
                'posts_per_page' => $posts,
                'paged' => $page,
                'orderby' => 'ID',
                'order' => 'ASC',
                'post_status' => array_diff(get_post_stati(), ['inherit','trash','auto-draft']),
            );
            if(!empty($this->startingFrom) && @is_a($this->startingFrom,'DateTime')){
                try{
                    $filters["date_query"] = array(
                        array(
                            'after'         => $this->startingFrom->sub(date_interval_create_from_date_string('1 day'))->format('Y-m-d H:i:s T'),
                            'inclusive'     => true,
                        ),
                    );
                }catch(\Exception $ex){

                }
            }
            $products = get_posts($filters);

            if (empty($products)) {

                sleep(2);

                $products = get_posts($filters);

                if (empty($products)) {
                    return false;
                }
            }

            return $products;
        }
    }

    /**
     * @param $id
     * @return array|bool
     */
    public function getProductVariantPosts($id)
    {
        $variants = get_posts(array(
            'numberposts' => 99999,
            'order' => 'ASC',
            'orderby' => 'ID',
            'post_type' => 'product_variation',
            'post_parent' => $id,
        ));

        if (empty($variants)) {
            return false;
        }

        return $variants;
    }

    /**
     * @param $id
     * @return RevampCRM\Models\ECommerceProductApiModel
     */
    public static function deleted($id)
    {
        $store_id = revampcrm_get_store_id();
        $api = revampcrm_get_api();

        if ($api !== false && !($product = $api->getStoreProduct($store_id, "deleted_{$id}"))) {
            $product = new RevampCRM\Models\ECommerceProductApiModel();

            $product->setId("deleted_{$id}");
            $product->setTitle("deleted_{$id}");

            $variant = new RevampCRM\Models\ECommerceProductVariant();
            $variant->setId("deleted_{$id}");
            $variant->setTitle("deleted_{$id}");

            $product->addVariant($variant);

            return $api->addStoreProduct($store_id, $product);
        }

        return $product;
    }
}
