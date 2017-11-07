<?php
/**
 * Created by PhpStorm.
 * User: LuisDiego
 * Date: 31/10/2017
 * Time: 11:40
 */

namespace backend\wizdomWebServices\MovimientosVacacionesWebService;


use backend\models\MovimientoVacaciones;
use backend\wizdomWebServices\AbstractWizdomWebService;

class MovimientosVacacionesWebService extends  AbstractWizdomWebService
{
    /**
     * @var MovimientosVacacionesWebService $movimientoVacaciones;
     */

    private $movimientoVacaciones;


    public function __construct(MovimientoVacaciones $movimientoVacaciones, $config = [])
    {
        $this->movimientoVacaciones = $movimientoVacaciones;
        $this->path = "uo_utiles_vacaciones_ws.asmx?wsdl";
        parent::__construct($config);
    }



    public function of_procesarmovimiento()
    {
        $compania = $this->movimientoVacaciones->getAttribute('compania');
        $tipoMov = $this->movimientoVacaciones->getAttribute('tipo_mov');
        $consecutivoMovimiento = $this->movimientoVacaciones->getAttribute('consecutivo_movimiento');
        $username = \Yii::$app->getDb()->username;
        $password = \Yii::$app->getDb()->password;
        $databaseName = \Yii::$app->params['dbname'];
        return parent::of_procesarmovimiento(["as_compania"=>$compania,"as_tipo_mov"=>$tipoMov,"al_identificador_mov"=>$consecutivoMovimiento,
                                        "as_username"=>$username,'as_password'=>$password,'as_database'=>$databaseName]);


    }






}