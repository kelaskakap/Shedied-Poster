<?php

namespace SheDied;

use SheDied\parser\InterfaceParser;
use SheDied\parser\AbstractParserWithGallery;
use SheDied\parser\gadget\IGadget;
use SheDied\parser\gadget\Gadget;
use SheDied\parser\jogja\OLXParser;
use SheDied\parser\jogja\Loker15Parser;
use SheDied\helpers\Lima;
use SheDied\SheDieDConfig;
use SheDied\helpers\Numbers;

class WPWrapper
{

    public function __construct()
    {
        ;
    }

    public static function get_page_by_title($post_title, $output = 'OBJECT', $post_type = 'post')
    {
        return get_page_by_title($post_title, $output, $post_type);
    }

    public static function get_option($option)
    {
        return get_option($option);
    }

    public static function plugin_dir_path($file)
    {
        return plugin_dir_path($file);
    }

    public static function wp_insert_post(InterfaceParser $parser)
    {
        //file_put_contents('/tmp/' . str_replace(' ', '_', $parser->getTitle()) . '.txt', var_export($parser->getContent(), true));
        return wp_insert_post($parser->toWordpressPost());
    }

    public static function current_time($type = 'mysql', $gmt = 0)
    {
        return current_time($type, $gmt);
    }

    public static function get_cat_name($category_id)
    {
        return get_cat_name($category_id);
    }

    public static function generate_featured_image(InterfaceParser $parser, $post_id)
    {

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        if (SheDieDConfig::SITE_DOMAIN != Lima::JOGJA_TRADE)
        {
            $attach_id = media_sideload_image($parser->getFeaturedImage(), $post_id, null, 'id');

            return set_post_thumbnail($post_id, $attach_id);
        } else
        {
            if ($parser instanceof OLXParser)
            {
                if ($parser->getSourceCategory() != 13)
                {
                    $content = strtolower($parser->getTitle());
                    $content = str_replace(' ', '-', $content);

                    $xf['name'] = "{$content}-feature.jpg";
                    $xf['tmp_name'] = download_url($parser->getFeaturedImage());

                    $attach_id = media_handle_sideload($xf, $post_id);
                    return set_post_thumbnail($post_id, $attach_id);
                } else
                {
                    //96 default media lowongan kerja
                    return set_post_thumbnail($post_id, 96);
                }
            } elseif ($parser instanceof Loker15Parser)
            {
                //96 default media lowongan kerja
                return set_post_thumbnail($post_id, 96);
            } else
            {
                $content = strtolower($parser->getTitle());
                $content = str_replace(' ', '-', $content);

                $xf['name'] = "{$content}-feature.jpg";
                $xf['tmp_name'] = download_url($parser->getFeaturedImage());

                $attach_id = media_handle_sideload($xf, $post_id);
                return set_post_thumbnail($post_id, $attach_id);
            }
        }
    }

    private static function get_attachment_id_from_src($image_src, $default)
    {
        if (is_string($image_src))
        {
            global $wpdb;
            $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
            $id = $wpdb->get_var($query);
            if ($id)
            {
                return $id;
            }
        }
        return $default;
    }

    public static function pojokjogja_set_source($post_id, $td_source = '', $td_source_url = '')
    {
        $meta_value['td_source'] = $td_source;
        $meta_value['td_source_url'] = $td_source_url;
        update_post_meta($post_id, 'td_post_theme_settings', $meta_value);
    }

    public static function add_to_yoast_seo($post_id, $meta_title = '', $meta_description = '', $meta_keywords = '')
    {
        $ret = false;
        //if check doesn't work over cli, so must be disabled
        //if (is_plugin_active('wordpress-seo/wp-seo.php')) {
        $updated_title = update_post_meta($post_id, '_yoast_wpseo_title', $meta_title);
        $updated_desc = update_post_meta($post_id, '_yoast_wpseo_metadesc', $meta_description);
        $updated_kw = update_post_meta($post_id, '_yoast_wpseo_metakeywords', $meta_keywords);

        if ($updated_title && $updated_desc && $updated_kw)
        {
            $ret = true;
        }
        //}
        return $ret;
    }

    public static function pojokjogja_set_source_for_jobstreet($post_id, $td_source = '', $td_source_url = '', $company = '')
    {
        update_post_meta($post_id, 'job_source', $td_source);
        update_post_meta($post_id, 'job_source_url', $td_source_url);
        update_post_meta($post_id, 'job_company', $company);
    }

    public static function pojokjogja_set_expdate_jobstreet($post_id, parser\JobstreetParser $parser)
    {
        update_post_meta($post_id, 'job_expdate', $parser->getExpDate());
    }

    public static function pojokjogja_set_tags_for_jobstreet($post_id, $company = '', $tags = [])
    {
        wp_set_post_tags($post_id, strtoupper($company), true);
        wp_set_post_terms($post_id, $tags, 'post_tag', true);
    }

    public static function pojokjogja_set_map_to_post($post_id, InterfaceParser $parser)
    {
        $map = false;
        if ($parser->hasMap() && is_plugin_active('mappress-google-maps-for-wordpress/mappress.php'))
        {
            $poi = new \Mappress_Poi([
                'title' => $parser->getTitle(),
                'point' => [
                    'lat' => $parser->getLatitude(),
                    'lng' => $parser->getLongitude()
                ]
            ]);
            $map = new \Mappress_Map([
                'width' => 425,
                'height' => 350,
                'title' => $parser->getTitle()
            ]);
            $map->pois = [$poi];
            $map->display();
            $map->save($post_id);
        }
        return $map;
    }

    public static function mappress_get_map($mapid = '')
    {
        $map = false;
        if (is_plugin_active('mappress-google-maps-for-wordpress/mappress.php'))
        {
            $map = \Mappress_Map::get($mapid);
        }
        return $map;
    }

    public static function mappress_update_map($post_id, \Mappress_Map $map)
    {
        return $map->save($post_id);
    }

    public static function homedesigning_upload_gallery(AbstractParserWithGallery $parser, $post_id)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        foreach ($parser->getGallery() as $idx => $img)
        {
            //if ($key > 10)
            //    break;

            $oimg = (object) $img;
            #upload image
            $attach_id = media_sideload_image($oimg->image, $post_id, null, 'id');

            #update meta, jika ada
            //update_post_meta($attach_id, '_wp_attachment_image_alt', 'My Alt Text');
            #set caption jika perlu
            $content = $oimg->excerpt;
            if ($content && $oimg->caption)
                $content .= " | " . $oimg->caption . ".";

            $array['ID'] = $attach_id;
            $array['post_excerpt'] = $oimg->caption;
            $array['post_content'] = $content;

            wp_update_post($array);

            if ($parser->attach())
            {
                //post dengan gallery wokwokwok
                //update source image
                $imghtml = wp_get_attachment_image($attach_id, 'full');
                $img['html'] = $imghtml;

                $parser->updatePhotoSource($idx, $img);
            }
        }
    }

    static public function homedesigning_meta($post_id, $no_sidebar = false, $source, $url_source)
    {
        update_post_meta($post_id, '_gmr_sidebar_key', $no_sidebar);
        update_post_meta($post_id, '_source_name', strtoupper($source));
        update_post_meta($post_id, '_source_url', $url_source);
    }

    public static function reviews_set_Gadget_Specs($post_id, IGadget $gadget)
    {
        $meta_value['review_tab_title'] = 'Specifications';
        $meta_value['review_tab_content'] = $gadget->specsTable();

        add_post_meta($post_id, 'review_tabs', $meta_value);
    }

    public static function reviews_CRON_set_Categories($post_id, InterfaceParser $gadget)
    {
        if (defined('DOING_CRON'))
        {
            wp_set_object_terms($post_id, $gadget->getCategoryId(), 'review-category');
        }
    }

    public static function reviews_set_Gadget_Support($post_id, IGadget $gadget)
    {
        $meta_value['review_tab_title'] = 'Support';
        $meta_value['review_tab_content'] = $gadget->getProductSupport();

        add_post_meta($post_id, 'review_tabs', $meta_value);
    }

    public static function reviews_set_Gadget_Photos($post_id, IGadget $gadget)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        foreach ($gadget->getProductPhotos() as $photo_url)
        {

            $media_id = media_sideload_image($photo_url, $post_id, null, 'id');

            if (is_numeric($media_id))
            {

                add_post_meta($post_id, 'review_images', $media_id);
            }
        }
    }

    static public function reviews_set_Tags($post_id, $tags, $append)
    {

        return wp_set_post_terms($post_id, $tags, 'review-tag', $append);
    }

    static public function wp_set_tags($post_id, $tags, $append)
    {

        return wp_set_post_tags($post_id, $tags, $append);
    }

    static public function reviews_set_Author_Avg($post_id, Gadget $gadget)
    {

        add_post_meta($post_id, 'author_average', $gadget->getAuthor_Rate());
    }

    static public function reviews_set_default_Scores($post_id, Gadget $gadget)
    {

        foreach ($gadget->getScores() as $point)
        {

            add_post_meta($post_id, 'reviews_score', $point);
        }
    }

    public static function olx_upload_photos(OLXParser $parser, $post_id)
    {

        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        foreach ($parser->getPhotos() as $key => $imgurl)
        {

            $content = strtolower($parser->getTitle());
            $content = str_replace(' ', '-', $content);
            $content = "{$content}-{$key}";


            $xf['name'] = "{$content}.jpg";
            $xf['tmp_name'] = download_url($imgurl);

            $attach_id = media_handle_sideload($xf, $post_id);

            #update meta, jika ada
            //update_post_meta($attach_id, '_wp_attachment_image_alt', 'My Alt Text');
            #set caption jika perlu            
            $content = "{$content}-{$attach_id}";

            $array['ID'] = $attach_id;
            $array['post_excerpt'] = $content;
            $array['post_content'] = $content;

            wp_update_post($array);

            #menampilkan di post. belum perlu.
            //$imghtml = wp_get_attachment_image($attach_id);
            //$galeri = image_add_caption($imghtml, $attach_id, $img->caption, '', '', '', '');
        }
    }

    public static function olx_meta($post_id, OLXParser $parser)
    {

        $meta_value['price'] = $parser->price;
        $meta_value['tayang'] = $parser->tayang;
        $meta_value['seller'] = $parser->seller;
        $meta_value['source'] = $parser->getUrl();

        add_post_meta($post_id, 'olx_meta', $meta_value);
    }

    public static function meta_wajib($post_id, InterfaceParser $parser)
    {
        update_post_meta($post_id, '_source_name', strtoupper($parser->getHost()));
        update_post_meta($post_id, '_source_url', $parser->getUrl());
    }

    static public function homedesigning_update_post_with_gallery(AbstractParserWithGallery $parser, $post_id)
    {
        if ($parser->attach())
        {
            $post_with_imported_images = array(
                'ID' => $post_id,
                'post_content' => $parser->buildPostWithGallery(),
            );

            wp_update_post($post_with_imported_images);
        }
    }

    static public function param_Query_for_Helper(Numbers $h)
    {

        $params = [];

        if (SheDieDConfig::SITE_DOMAIN == $h->getIdentity())
        {
            //ngemie.com untuk gofood
            $params = get_transient('gofood_param_query');

            if ($params)
                return $params;
        }

        return $params;
    }

    static public function update_param_Query_for_Helper(Numbers $h)
    {

        if (SheDieDConfig::SITE_DOMAIN == $h->getIdentity())
        {
            //ngemie.com untuk gofood
            $params = set_transient('gofood_param_query', $h->getQuery_Param());
        }
    }

}
