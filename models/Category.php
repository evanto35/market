<?php

/**
 * Description of Category
 *
 * @author rodnoy
 */
class Category extends Db{

    public static function getCategoriesList() {

        $categoryList = array();

        $result = self::getConnection()->query('SELECT id, name FROM category '
                . 'ORDER BY sort_order ASC');

        $i = 0;
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $i++;
        }
        return $categoryList;
    }

}
