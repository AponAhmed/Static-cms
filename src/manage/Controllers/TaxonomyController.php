<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\StaticPage;
use Aponahmed\Cmsstatic\manage\Model\Taxonomy;

class TaxonomyController extends Controller
{

    function __construct($global = false)
    {
        $this->global = $global;
        parent::__construct();
        $this->setRoute();
    }

    function setRoute()
    {
        switch ($this->childSegment) {
            case 'add':
                # code...
                $this->addTaxonomy();
                break;
            case 'remove':
                # code...
                $this->deleteTaxonomy();
                break;
            default:
                break;
        }
    }

    function addTaxonomy()
    {
        $res = ['error' => true, 'message' => ""];
        $inputs = $this->getInput();
        if (property_exists($inputs, 'taxonomy')) {
            $name = $inputs->name;
            $taxo = new Taxonomy($inputs->taxonomy);
            $taxo->set($name, []);
            $res['error'] = false;
            $res['message'] = "$name Taxonomy is Added";
            $res['item'] = $name;
        } else {
            $res['error'] = true;
            $res['message'] = 'Taxonomy is required';
        }
        echo json_encode($res);
    }

    function deleteTaxonomy()
    {
        try {
            $inputs = $this->getInput();
            if (property_exists($inputs, 'taxonomy')) {
                $name = $inputs->name;
                $taxo = new Taxonomy($inputs->taxonomy);
                //Term's Object Removal
                $pageSlugs = $taxo->getObjects($name);

                foreach ($pageSlugs as $slug) {
                    //var_dump($slug);
                    $object = null;
                    switch ($inputs->taxonomy) {
                        case "static-category":
                            $object = new StaticPage($slug);
                            break;
                        default:
                            break;
                    }

                    if ($object) {
                        $obtaxonomy = $object->taxonomy;
                        $index = array_search($name, $obtaxonomy[$inputs->taxonomy]);
                        if ($index !== false) {
                            unset($obtaxonomy[$inputs->taxonomy][$index]);
                        }
                        //var_dump($obtaxonomy);
                        //$obtaxonomy = array_values($obtaxonomy);
                        $object->update(['taxonomy' => $obtaxonomy]);
                    }
                }
                //Remove Terms From Taxonomy
                $taxo->removeTerm($name);
            }

            echo json_encode(['error' => false, 'success']);
        } catch (\Exception $e) {
            //throw $th;
            echo json_encode(['error' => true, 'Error:', $e->getMessage()]);
        }
    }
}
