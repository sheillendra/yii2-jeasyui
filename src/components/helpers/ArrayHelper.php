<?php

namespace sheillendra\jeasyui\components\helpers;

class ArrayHelper extends \yii\helpers\ArrayHelper {

    private static $_parent_array;
    private static $_temp_parent_array;

    /**
     * 
     * @param string $keyMustHaveByParent
     * @param string $key
     * @param string $value 
     * @param array $array checked array
     * @return array returned array when have $key and $value like in params
     */
    public static function getArrayByKeyValue($keyMustHaveByParent, $key, $value, $array) {
        foreach ($array as $k => $v) {
            if (is_array($v) && isset($v[$keyMustHaveByParent])) {
                self::$_temp_parent_array = $v;
            }
            if (is_array($v)) {
                self::getArrayByKeyValue($keyMustHaveByParent, $key, $value, $v);
            } elseif ($k === $key && urldecode ($v) === $value) {
                self::$_parent_array = self::$_temp_parent_array;
                break;
            }
        }

        return self::$_parent_array;
    }

    /**
     * Menyortir index $array sesuai dengan susunan array $sortaArray
     * contoh penggunaan yaitu untuk menyortir susunan column di satu table
     * karena postgresql tidak ada fitur after atau before column
     * jadi gii menggenarate field nya tidak sesuai urutan yang kita mau
     * 
     * @param array $array
     * @param array $sortArray 
     * @return array
     */
    public static function indexSort(array $array, array $sortArray){
        $newArray = [];
        foreach($sortArray as $k){
            if(isset($array[$k])){
                $newArray[$k] = $array[$k];
            }
        }
        return $newArray;
    }

}
