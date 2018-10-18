<?php

  namespace App\Util;

  Class api {

    public static function get ($entity) {

        $result = file_get_contents("http://127.0.0.1:8001/api/dependencias.json");

        $result = json_decode($result);

        return $result;

    }


/*
    public static function getById ($entity, $id) {

        $result = file_get_contents("http://127.0.0.1:8000/api/edificios/".$id."/.json");

        $result = json_decode($result);

        return $result;

    }

    */

  }

?>
