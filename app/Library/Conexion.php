<?php
/**
 * Created by PhpStorm.
 * User: rvalle
 * Date: 13/12/2018
 * Time: 13:05
 */

namespace App\Library;




use PDO;

class Conexion
{

    private $tipo_de_base = 'mysql';
    //Produccion
    //private $host = '10.0.0.27';
    //private $nombre_de_base = 'comafe';
    //private $usuario = 'comafe_intranet';
    //private $contrasena = 'rh0EVVGjqBKtm4iI8sGGEgyVH';
    //Desarrollo
    //private        $host           = 'localhost';
    //private        $nombre_de_base = 'comafe';
    //private        $usuario        = 'mycomafe';
    //private        $contrasena     = 'LzOo$rTgFF89';
    //private static $instancia      = null;

    //Prueba
    private        $host           = '127.0.0.1';
    private        $nombre_de_base = 'b2b';
    private        $usuario        = 'paco';
    private        $contrasena     = 'qwerty';
    private static $instancia      = null;

    /**
     * Conexion constructor.
     */
    private function __construct()
    {

            //echo 'Crear conexion'.'<BR>';
            self::$instancia = new PDO(
                $this->tipo_de_base . ':host=' . $this->host . ';dbname=' . $this->nombre_de_base . ';charset=utf8',
                $this->usuario,
                $this->contrasena
            );
            self::$instancia->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    }

    public static function getInstancia()
    {
        if (!self::$instancia) {
            new self();
        }

        return self::$instancia;
    }

    public static function getAutoId()
    {
        return Conexion::getInstancia()->lastInsertId();
    }

    public static function cerrar()
    {
        self::$instancia = null;
    }
}