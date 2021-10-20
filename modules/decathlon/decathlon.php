<?php

if (!defined('_PS_VERSION_'))
    exit();

class Decathlon extends Module
{

    private $decathlon_product;
    private $decathlon_endpoint_auth;
    private $decathlon_token;
    private $decathlon_table_name;

    public function __construct()
    {
        $this->decathlon_table_name = 'decathlon_products';
        $this->name = 'decathlon';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Fernando Torres';
        $this->need_instance = 1;
        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);
        $this->bootstrap = true;
        $this->decathlon_endpoint_auth = "https://idpdecathlon.oxylane.com/as/token.oauth2";

        parent::__construct();

        $this->displayName = $this->l('Decathlon', 'decathlon');
        $description = 'Para este punto será necesario crear un módulo que contenga un hook creado por usted o usar algún hook creado por Prestashop para mostrar la información del producto, la cual fue obtenida en el paso anterior.';
        $this->description = $this->l($description, 'decathlon');

        $this->confirmUninstall = $this->l('¿Está seguro que desea desinstalarme? :(', 'decathlon');
    }

    /**
     * This function create decathlon table if not exists, which will be useful for save products retrived from the api
     *
     * @return array with success value
     */
    private function createDecathlonTable(): array
    {
        $response = ['success' => true];
        $create_table = "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . $this->decathlon_table_name . "(
            id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            product_id INT(11) NOT NULL,
            product_name VARCHAR(255) DEFAULT NULL,
            product_description LONGTEXT DEFAULT NULL,
            created_at DATETIME DEFAULT NULL,
            updated_at DATETIME DEFAULT NULL        
        )";
        Db::getInstance()->Execute($create_table) or die ((Db::getInstance()->getMsgError()));
        return $response;
    }


    /**
     * This function deletes the decathlon table
     *
     * @return array with success value
     */
    private function deleteDecathlonTable(): array
    {
        $response = ['success' => true];
        $create_table = "DROP TABLE IF EXISTS " . _DB_PREFIX_ . $this->decathlon_table_name;
        Db::getInstance()->Execute($create_table) or die ((Db::getInstance()->getMsgError()));
        return $response;
    }

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);


        $this->createDecathlonTable();

        return parent::install() &&
            $this->registerHook('displayHome') && Configuration::updateValue('decathlon_product_id', '8551453');
    }

    public function uninstall()
    {
        $this->deleteDecathlonTable();

        if (!parent::uninstall() || !Configuration::deleteByName('decathlon_product_id'))
            return false;
        return true;
    }


    /**
     * Return an array with the product index which is the content of the retrived product from api service
     *
     * @param array $params
     * @return array
     */
    private function getProduct($params = []): array
    {

        $endpoint = "https://api-eu.decathlon.net/spid/v4/superModel/model/{$params['product_id']}?availabilityDate=2021-08-06&locales=es_MX";
        $curl_connection = curl_init($endpoint);

        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array(
            'x-api-key: 4a39000a-e495-4d95-98fd-bd00bc4345bb',
            'Authorization: Bearer ' . $params['token'],
        ));
        $apiResponse = curl_exec($curl_connection);
        curl_close($curl_connection);

        $response = ['success' => true, 'data' => ['product' => json_decode($apiResponse, true)]];

        return $response;
    }


    /**
     * Return an array with data content, this data content contains token value for use in future decathlon's requests
     *
     * @return array
     */
    private function getToken(): array
    {
        $params_curl = "grant_type=client_credentials";
        $curl_connection = curl_init();
        curl_setopt($curl_connection, CURLOPT_POST, 1);
        curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $params_curl);
        curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array(
            'Authorization: Basic QzRiODg4NjExYzhkMWQ1NDA1YzJmN2VmMDg0NjRmOTUyOThhYjg2Nzg6UzU2M2h2TW1TSDJYeWtnWGFXN0ZxRmJ3Tlg2cHJLM09RSElPaXVyTHVWSG1Wb2JRbmo4eUE0ZEtIYTA3VGlLWg==',
        ));
        curl_setopt($curl_connection, CURLOPT_URL, $this->decathlon_endpoint_auth);
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        $curl_response = curl_exec($curl_connection);
        curl_close($curl_connection);
        $response = json_decode($curl_response, true);
        $response = ['success' => true, 'data' => ['token' => $response["access_token"]]];
        return $response;
    }


    /**
     * @param array $params
     * @throws PrestaShopDatabaseException
     */
    private function saveProduct($params = [])
    {
        /** @var \Db $db */
        // connect to database
        try {
            $db = \Db::getInstance();

            // check if product already exists, if not, we insert
            $request = "SELECT COUNT(1) product_exist FROM ".(_DB_PREFIX_ . $this->decathlon_table_name)." WHERE( " . (_DB_PREFIX_ . $this->decathlon_table_name) . ".product_id = '{$params['product_id']}' )";
            $product_count = $db->getValue($request);

            if ( $product_count['product_exist'] <= 0 )
            {
                // insert because it doesn't exist
                $result = $db->insert($this->decathlon_table_name, [
                    'product_id' => $params['product_id'],
                    'product_name' => $params['product_name'],
                    'product_description' => $params['product_description'],
                ]);
            }
        } catch (PrestaShopDatabaseException $exception)
        {

        }
    }

    public function hookDisplayHome($params)
    {
        $token = $this->getToken();
        // return token for use in future requests
        $this->decathlon_token = $token['data']['token'];

        // get product info from decathlon based in product_id previously configured into the prestashop's module service
        $this->decathlon_product = $this->getProduct([
            'token' => $this->decathlon_token,
            'product_id' => Configuration::get('decathlon_product_id')
        ]);

        // before we call the final view, we're gonna to insert the new product into our default table (if not exists)
        $this->saveProduct([
            'product_id' => Configuration::get('decathlon_product_id'),
            'product_name' => $this->decathlon_product['data']['product']['models'][0]['webLabel'][0]['value'],
            'product_description' => $this->decathlon_product['data']['product']['designedFor'][0]['value'],
        ]);

        // here, we call the view for draw the product information retrived from decathlon's api
        $this->context->smarty->assign(
            [
                'decathlon_product_id' => Configuration::get('decathlon_product_id'),
                'decathlon_product' => $this->decathlon_product['data']['product'],
            ]
        );
        return $this->display(__FILE__, 'decathlon_product.tpl');
    }

    public function displayForm()
    {
        // < init fields for form array >
        $fields_form[0]['form'] = array(
            'legend' => [
                'title' => $this->l('Productos de Decathlon'),
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('El id del producto que desea obtener'),
                    'name' => 'decathlon_product_id',
                    //'lang' => true,
                    'size' => 20,
                    'required' => true
                ],
            ],
            'submit' => [
                'title' => $this->l('Guardar'),
                'class' => 'btn btn-default pull-right'
            ]
        );

        // < load helperForm >
        $helper = new HelperForm();

        // < module, token and currentIndex >
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        // < title and toolbar >
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit' . $this->name;
        $helper->toolbar_btn = array(
            'save' =>
                [
                    'desc' => $this->l('Save'),
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&save' . $this->name .
                        '&token=' . Tools::getAdminTokenLite('AdminModules'),
                ],
            'back' => [
                'href' => AdminController::$currentIndex . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            ]
        );

        // < load current value >
        $helper->fields_value['decathlon_product_id'] = Configuration::get('decathlon_product_id');

        return $helper->generateForm($fields_form);
    }


    public function getContent()
    {
        $output = null;


        // < here we check if the form is submited for this module >
        if (Tools::isSubmit('submit' . $this->name)) {
            $youtube_url = strval(Tools::getValue('decathlon_product_id'));

            // < make some validation, check if we have something in the input >
            if (!isset($youtube_url))
                $output .= $this->displayError($this->l('Ingrese el ID del producto.'));
            else {
                // < this will update the value of the Configuration variable >
                Configuration::updateValue('decathlon_product_id', $youtube_url);


                // < this will display the confirmation message >
                $output .= $this->displayConfirmation($this->l('ID Actualizado!'));
            }
        }
        return $output . $this->displayForm();
    }

}
