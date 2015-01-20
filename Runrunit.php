<?php

/**
* Classe para auxiliar no uso da api do Runrun.it para fins de estudo
*
* @author Gustavo de Oliveira <gustavo@uppererp.com.br>
* @see http://runrun.it/api/documentation
* @version 1.0
*/

namespace Guga\Runrunit;

class Runrunit
{
    /**
    * @var string Usada para a comunicação da API
    */
    protected $hostApi = "https://secure.runrun.it/api/";

    /**
    * @var string Versão da API
    */
    protected $versaoApi = "v1.0";

    /**
    * @var string App Key
    */
    protected $appKey;

    /**
    * @var string User Token
    */
    protected $userToken;

    /**
    * @param string $versao
    */
    public function setVersaoApi($versao)
    {
        $this->versaoApi = $versao;
    }

    /**
    * Definimos a App Key
    * @param string $minhaAppKey
    */
    public function setAppKey($minhaAppKey)
    {
        $this->appKey = $minhaAppKey;
    }

    /**
    * Definimos o User Token
    * @param string $meuUserToken
    */
    public function setUserToken($meuUserToken)
    {
        $this->userToken = $meuUserToken;
    }

    /**
    * Retornamos a App Key
    * @return string
    */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
    * Retornamos o User Token
    * @return string
    */
    public function getUserToken()
    {
        return $this->userToken;
    }

    /**
    * Retornamos a versão da API
    * @return string
    */
    public function getVersaoApi()
    {
        return $this->versaoApi;
    }

    /**
    * Método para realizar a ação na API
    * @param   string   $url
    * @param   string   $metodo
    * @param   array    $data
    */
    protected function doCall($url, $metodo, Array $data = null)
    {
        // Métodos Permitidos pela API
        $metodosPermitidos = array('GET', 'POST', 'PUT', 'DELETE');

        // Verifica se o método passado é válido
        if(!in_array($metodo, $metodosPermitidos)) {
            throw new Exception('Método (' . htmlentities($metodo) . ') não permitido, os métodos permitidos são: '. implode(', ', $metodosPermitidos));
        }

        // Tratando as formas de requisição
        // Deve existir outra maneira de se realizar as requisições
        $headers = array(
            'App-Key: ' . $this->getAppKey(),
            'User-Token: ' . $this->getUserToken(),
            'Content-Type: application/json',
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->hostApi.$this->getVersaoApi().'/'.$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Se a pessoa estiver realizando um post
        if($metodo == 'POST') {

            if($data === null) {
                $data = array();
            }

            $postData = '';
            foreach($data as $k => $v) {
                $postData .= $k . '='.$v;
            }
            rtrim($postData, '&');
            curl_setopt($ch, CURLOPT_POST, count($postData));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }


        $return = curl_exec($ch);
        curl_close($ch);

        return $return;
    }

    /**
    * Método para listar todas as Taks
    * @return string json
    */
    public function listarTodasTasks()
    {
        return $this->doCall('tasks', 'GET');
    }

    /**
    * Método para listar uma task específica
    * @param int $id
    * @return string json
    */
    public function listarTask($id)
    {
        return $this->doCall('tasks/'.(int)$id.'/', 'GET');
    }

    /**
    * Método para dar Play em uma task
    * @param int $id
    * @return string json
    */
    public function playTask($id)
    {
        return $this->doCall('tasks/'.(int)$id.'/play', 'POST');
    }

    /**
    * Método para dar Pause em uma task
    * @param int $id
    * @return string json
    */
    public function pauseTask($id)
    {
        return $this->doCall('tasks/'.(int)$id.'/pause', 'POST');
    }

    /**
    * Método para Fechar uma task
    * @param int $id
    * @return string json
    */
    public function closeTask($id)
    {
        return $this->doCall('tasks/'.(int)$id.'/close', 'POST');
    }

}