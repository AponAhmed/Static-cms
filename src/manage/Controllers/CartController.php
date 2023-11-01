<?php

namespace Aponahmed\Cmsstatic\manage\Controllers;

use Aponahmed\Cmsstatic\manage\Model\Media;
use Aponahmed\Cmsstatic\Traits\Main;
use Aponahmed\Cmsstatic\Utilities\ApiRequest;
use Aponahmed\Cmsstatic\Utilities\Cart;
use Exception;

class CartController extends Controller
{
    use Main;
    public Cart $cart;
    private static $qty = 1;

    function __construct($global)
    {
        $this->global = $global;
        parent::__construct();
        $this->cart = new Cart();
        $this->router();
    }

    function router()
    {
        switch ($this->childSegment) {
            case 'add-item':
                $this->addItem();
                break;
            case 'remove-item':
                $this->removeItem();
                break;
            case 'send':
                $this->send();
                break;
            default:
                $this->viewCart();
        }
    }

    function cartHtml()
    {
        ob_start();
?>
        <table style="font-family: arial;font-size: 14px;width:70%;border:1px solid #ddd;border-collapse:collapse;border-spacing:0;">
            <thead>
                <tr>
                    <th style="border-bottom: 1px solid #aeaeae;text-align: left;">Item</th>
                    <th style="border-bottom: 1px solid #aeaeae;text-align: left;">info</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($this->cartData(100) as $item) {
                ?>
                    <tr>
                        <td style="border-bottom: 1px solid #aeaeae;text-align: left;">
                            <img src="<?php echo $item['imageUrl'] ?>" alt="<?php echo $item['name'] ?>">
                        </td>
                        <td style="border-bottom: 1px solid #aeaeae;text-align: left;">
                            <a href="<?php echo $item['url'] ?>"><?php echo $item['name'] ?></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
        </table>
<?php
        return ob_get_clean();
    }

    function send()
    {
        $param = $this->getInput();

        $apiUrl = $this->GetSetting('contact_api_path');
        $apiKey = $this->GetSetting('contact_api_key');
        $apiRequest = new ApiRequest($apiUrl, $apiKey);


        //Message Build 
        $message = "<div>\n";
        $message .= "<b>Name:</b> $param->name";
        $message .= "<br/>\n<b>Email:</b> $param->email";
        $message .= "<br/>\n<b>WhatsApp:</b> $param->whatsapp";
        $message .= "<br>\n<br>\n";
        $message .= nl2br(trim($param->message));
        $message .= "<br/><br/><br/>" . $this->cartHtml();
        $message .= "\n</div>";

        $data = [];
        $data['name'] = $param->name;
        $data['subject'] = "Request For Quotation (" . self::$siteurl . ")";
        $data['email'] = $param->email;
        $data['whatsapp'] = $param->whatsapp;
        $data['message'] = $message;
        $data['ip'] = $this->getClientIP();

        try {
            $apiResponse = $apiRequest->send($data);
            $this->cart->destroy();
            echo $apiResponse;
        } catch (Exception $e) {
            echo  $e->getMessage();
        }
    }

    function addItem()
    {
        // sleep(2);
        $inputs = $this->getInput();
        try {
            //code...
            $id = $inputs->id;
            $name = $inputs->name;
            if ($this->cart->isItemExists($id)) {
                echo json_encode(['error' => true, 'message' => 'Item already exists in List.']);
                return;
            } else {
                if ($this->cart->add($id, self::$qty, ['name' => $name])) {
                    echo json_encode(['error' => false, 'items' => $this->cartData()]);
                    return;
                }
                echo json_encode(['error' => true, 'message' => 'Item could not added']);
                return;
            }
        } catch (\Exception $e) {
            //throw $th;
            echo json_encode(['error' => true, 'message' => $e->getMessage()]);
            return;
        }
    }

    function removeItem()
    {
        $inputs = $this->getInput();
        try {
            if ($this->cart->remove($inputs->id)) {
                echo json_encode(['error' => false]);
                return;
            } else {
                echo json_encode(['error' => true]);
                return;
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    function cartData($imgSize = 50)
    {
        $itemsData = [];
        /**
         * id
         * image url
         * name
         */

        //var_dump();
        foreach ($this->cart->getItems() as $item) {
            $media = Media::getInstanse($item[0]['id']);
            // var_dump($media);
            $item = [
                'id' => $item[0]['id'],
                'name' => $item[0]['attributes']['name'],
                'imageUrl' => $media->getSize($imgSize),
                'url' => $media->getUrl()

            ];
            $itemsData[] = $item;
        }
        return $itemsData;
    }

    function viewCart()
    {
        header('Content-Type: application/json');


        if ($this->cart->isEmpty()) {
            echo json_encode(['error' => true, 'items' => $this->cartData()]);
        } else {
            echo json_encode(['error' => false, 'items' => $this->cartData()]);
        }
    }
}
