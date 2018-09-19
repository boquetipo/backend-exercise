<?php 

namespace App\Service;

use phpDocumentor\Reflection\Types\Integer;

class Recipe
{
    /**
    * Get recipes
    * @param $search String search query (e.g. 'chicken')
    * @param $ingredients String|Array ingredients (e.g. 'salt, pepper')
    * @param $page Integer page to show (e.g. 1)
    */
    public function get($search = null, $ingredients = null, $page = 1)
    {
        $params = [];

        if (null !== $search) {
            $params['q'] = $search;
        }

        if (is_array($ingredients)) {
            if (count($ingredients) > 0) {
                $params['i'] = implode(',', $ingredients);
            }
        } elseif ('' != $ingredients) {
            $params['i'] = $ingredients;
        }

        if (intval($page) > 0) {
            $params['p'] = intval($page);
        }

        $parameters = [];
        foreach ($params as $key => $value) {
            $parameters[] = $key . '=' . $value;
        }

        $client = new \GuzzleHttp\Client();

        try {
            $res = $client->request('GET', 'http://www.recipepuppy.com/api/?' . (count($parameters) > 0 ? implode('&', $parameters) : ''));
        } catch (\Exception $e) {
            //TODO send email to webmaster with $e object info
            return '';
        }

        if (200 != $res->getStatusCode()) {
            return '';
        }

        return $res->getBody();
    }

    /**
     * Return an array with from a json of recipes
     * @param $items
     */
    public function get_list($items_json){
        $items = json_decode($items_json);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $items;
    }
}