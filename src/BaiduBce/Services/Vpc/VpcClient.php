<?php
/**
 * Created by IntelliJ IDEA.
 * User: zhangjing60
 * Date: 17/8/14
 * Time: 下午4:16
 */

namespace BaiduBce\Services\Vpc;
use BaiduBce\Auth\BceV1Signer;
use BaiduBce\BceBaseClient;
use BaiduBce\Http\BceHttpClient;
use BaiduBce\Http\HttpHeaders;
use BaiduBce\Http\HttpMethod;
use BaiduBce\Http\HttpContentTypes;
//include '/Users/zhangjing60/baidu/bce-sdk-bcc-branch/php/src/BaiduBce/BceBaseClient.php';
//include '/Users/zhangjing60/baidu/bce-sdk-bcc-branch/php/src/BaiduBce/Auth/BceV1Signer.php';



class VpcClient extends BceBaseClient {

    private $signer;
    private $httpClient;
    private $prefix = '/v1';

    /**
     * VpcClient constructor.
     * @param array $config
     */
    function __construct(array $config)
    {
        parent::__construct($config, 'vpc');
        $this->signer = new BceV1Signer();
        $this->httpClient = new BceHttpClient();
    }

    /**
     * @param string $name
     *        The name of vpc to be created.
     * @param string $cidr
     *        The CIDR of the vpc.
     * @param string $description
     *        The description of the vpc.
     * @param string $clientToken
     *        An ASCII string whose length is less than 64.
     *        The request will be idempotent if clientToken is provided.
     *        If the clientToken is not specified by the user, a random String generated by default algorithm will be used.
     * @param array $options
     * @return mixed
     */
    public function createVpc($name, $cidr,
                                 $description = null, $clientToken = null, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        $params = array();
        $body = array();
        if(empty($clientToken)) {
            $params['clientToken'] = $this->generateClientToken();
        }
        else {
            $params['clientToken'] = $clientToken;
        }
        if(empty($name)) {
            throw new \InvalidArgumentException(
                'request $name  should not be empty .'
            );
        }
        if(empty($cidr)) {
            throw new \InvalidArgumentException(
                'request $cidr  should not be empty .'
            );
        }
        $body['name'] = $name;
        $body['cidr'] = $cidr;
        if(!empty($description)) {
            $body['description'] = $description;
        }
        return $this->sendRequest(
            HttpMethod::POST,
            array(
                'config' => $config,
                'params' => $params,
                'body' => json_encode($body),
            ),
            '/vpc'
        );
    }

    /**
     * Return a list of vpcs owned by the authenticated user.
     * @param string $marker
     *        The optional parameter marker specified in the original request to specify
     *        where in the results to begin listing.
     *        Together with the marker, specifies the list result which listing should begin.
     *        If the marker is not specified, the list result will listing from the first one.
     * @param int $maxkeys
     *        The optional parameter to specifies the max number of list result to return.
     *        The default value is 1000.
     * @param boolean $isDefault
     *        The option param demotes whether the vpc is default vpc.
     * @param array $options
     * @return mixed
     */
    public function listVpcs($marker = null, $maxkeys = null, $isDefault = null, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        $params = array();
        if(!empty($marker)) {
            $params['marker'] = $marker;
        }
        if(!empty($maxkeys)) {
            $params['maxKeys'] = $maxkeys;
        }
        if(is_bool($isDefault)) {
            $params['isDefault'] = $isDefault;
        }

        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/vpc'
        );
        
    }

    /**
     * Get the detail information of specified vpc.
     * @param string $vpcId
     *        The id of the vpc
     * @param array $options
     * @return \stdClass
     */
    public function getVpc($vpcId, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        if(empty($vpcId)) {
            throw new \InvalidArgumentException(
                'request $vpcId  should not be empty .'
            );
        }
        return $this->sendRequest(
            HttpMethod::GET,
            array(
                'config' => $config,
            ),
            '/vpc/' . $vpcId
        );
    }

    /**
     * Delete the specified vpc owned by the user.All resource in the vpc must be deleted before the vpc itself
     * can be deleted.
     * @param string $vpcId
     *        The id of the specified vpc
     * @param string $clientToken
     *        An ASCII string whose length is less than 64.
     *        The request will be idempotent if clientToken is provided.
     *        If the clientToken is not specified by the user, a random String generated by default algorithm will be used.
     * @param array $options
     * @return mixed
     */
    public function deleteVpc( $vpcId, $clientToken = null, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        $params = array();
        if(empty($vpcId)) {
            throw new \InvalidArgumentException(
                'request $vpcId  should not be empty .'
            );
        }
        if(empty($clientToken)) {
            $params['clientToken'] = $this->generateClientToken();
        }
        else {
            $params['clientToken'] = $clientToken;
        }
        return $this->sendRequest(
            HttpMethod::DELETE,
            array(
                'config' => $config,
                'params' => $params,
            ),
            '/vpc/' . $vpcId
        );
    }

    /**
     * Modify the special attribute to new value of the vpc owned by the user.
     * @param string $vpcId
     *        The id of the specified vpc
     * @param string $name
     *        The name of the specified vpc
     * @param string $description
     *        The option param to describe the vpc
     * @param string $clientToken
     *        An ASCII string whose length is less than 64.
     *        The request will be idempotent if clientToken is provided.
     *        If the clientToken is not specified by the user, a random String generated by default algorithm will be used.
     * @param array $options
     * @return mixed
     */
    public function updateVpc($vpcId,$name, $description = null, $clientToken = null, $options = array()) {
        list($config) = $this->parseOptions($options, 'config');
        $params = array();
        $body = array();
        if(empty($clientToken)) {
            $params['clientToken'] = $this->generateClientToken();
        }
        else {
            $params['clientToken'] = $clientToken;
        }
        if(empty($vpcId)) {
            throw new \InvalidArgumentException(
                'request $vpcId  should not be empty .'
            );
        }
        if(empty($name)) {
            throw new \InvalidArgumentException(
                'request $name  should not be empty .'
            );
        }
        if(!empty($description)) {
            $body['description'] = $description;
        }
        $params['modifyAttribute'] = null;
        $body['name'] = $name;
        return $this->sendRequest(
            HttpMethod::PUT,
            array(
                'config' => $config,
                'body'=>json_encode($body),
                'params' => $params,
            ),
            '/vpc/' .$vpcId
        );
    }

    /**
     * Create HttpClient and send request
     *
     * @param string $httpMethod
     *          The Http request method
     *
     * @param array $varArgs
     *          The extra arguments
     *
     * @param string $requestPath
     *          The Http request uri
     *
     * @return mixed The Http response and headers.
     */
    private function sendRequest($httpMethod, array $varArgs, $requestPath = '/')
    {
        $defaultArgs = array(
            'config' => array(),
            'body' => null,
            'headers' => array(),
            'params' => array(),
        );

        $args = array_merge($defaultArgs, $varArgs);
        if (empty($args['config'])) {
            $config = $this->config;
        } else {
            $config = array_merge(
                array(),
                $this->config,
                $args['config']
            );
        }
        if (!isset($args['headers'][HttpHeaders::CONTENT_TYPE])) {
            $args['headers'][HttpHeaders::CONTENT_TYPE] = HttpContentTypes::JSON;
        }
        $path = $this->prefix . $requestPath;
        $response = $this->httpClient->sendRequest(
            $config,
            $httpMethod,
            $path,
            $args['body'],
            $args['headers'],
            $args['params'],
            $this->signer
        );

        $result = $this->parseJsonResult($response['body']);

        return $result;
    }

    /**
     * The default method to generate the random String for clientToken if the optional parameter clientToken
     * is not specified by the user.
     *
     * The default algorithm is Mersenne Twister to generate a random UUID,
     * @return string
     */
    public static function generateClientToken()
    {
        $uuid = md5(uniqid(mt_rand(), true));
        return $uuid;
    }
}