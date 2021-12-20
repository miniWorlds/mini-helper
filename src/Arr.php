<?php
declare(strict_types = 1);

namespace mini\helper;


class Arr
{
    /**
     * 表单多维数据转换笛卡儿积
     * 转换前：{"x":0,"a":[1,2,3],"b":[11,22,33],"c":[111,222,3333,444],"d":[1111,2222,3333]}
     * 转换为：[{"a":1,"b":11,"c":111,"d":1111},{"a":2,"b":22,"c":222,"d":2222},{"a":3,"b":33,"c":3333,"d":3333}]
     *
     * @param array $arr (表单二维数组)
     * @param boolean $fill (false=返回数据长度取最短,反之取最长,空值自动补充)
     * @return array
     */
    public static function formToLinear(array $arr, bool $fill=false) :array
    {
        $keys = [];
        $count = $fill ? 0 : PHP_INT_MAX;
        foreach ($arr as $k => $v) {
            if (is_array($v)) {
                $keys[] = $k;
                $count = $fill ? max($count, count($v)) : min($count, count($v));
            }
        }
        if (empty($keys)) {
            return [];
        }
        $data = [];
        for ($i = 0; $i < $count; $i++) {
            foreach ($keys as $v) {
                $data[$i][$v] = isset($arr[$v][$i]) ? $arr[$v][$i] : null;
            }
        }
        return $data;
    }

    /**
     * 线性结构转换成树形结构
     * 转换前：[{"n":"a","nn":"aa","id":1,"pid":0},{"n":"b","nn":"bb","id":2,"pid":1},{"n":"c","nn":"cc","id":3,"pid":1},{"n":"d","nn":"dd","id":4,"pid":1},{"n":"e","nn":"ee","id":5,"pid":3},{"n":"f","nn":"ff","id":6,"pid":3}]
     * 转换后：[{"n":"a","nn":"aa","children":[{"n":"b","nn":"bb","sub":[{"n":"c","nn":"cc"}]},{"n":"d","nn":"dd","children":[{"n":"e","nn":"ee"},{"n":"f","nn":"ff"}]}]}]
     *
     * @param array $data 线行结构数组
     * @param int $pid (父级起始值)
     * @param string $field (父级字段名)
     * @param string $child (子级目录名)
     * @return array
     */
    public static function linearToTree(array $data, int $pid=0, string $field='pid', string $child='children') :array
    {
        $tree = array();
        foreach ($data as $key => $value) {
            if ($value[$field] == $pid) {
                $value[$child] = self::linearToTree($data, $value['id'], $field, $child);
                $tree[] = $value;
            }
        }
        return $tree;
    }

    /**
     * 树形结构转线性结构
     * 转换前：[{"n":"a","nn":"aa","children":[{"n":"b","nn":"bb","children":[{"n":"c","nn":"cc"}]},{"n":"d","nn":"dd","sub":[{"n":"e","nn":"ee"},{"n":"f","nn":"ff"}]}]}]
     * 转换后：[{"n":"a","nn":"aa","id":1,"pid":0},{"n":"b","nn":"bb","id":2,"pid":1},{"n":"c","nn":"cc","id":3,"pid":1}, {"n":"d","nn":"dd","id":4,"pid":1},{"n":"e","nn":"ee","id":5,"pid":3},{"n":"f","nn":"ff","id":6,"pid":3}]
     *
     * @param array $data 数组
     * @param string $child 树形结构子数组名称
     * @param string $idName 自动生成id名称
     * @param string $parentIdName 自动生成父类id
     * @param int $parentId 此值请勿给参数
     * @return array
     */
    public static function treeToLinear(array $data, string $child='children', string $idName='id', string $parentIdName='pid', int $parentId=0) :array
    {
        $result = [];
        $i = 1;
        foreach ($data as $key => $row) {
            $row[$idName] = $i++;
            $row[$parentIdName] = $parentId;

            $sub = $row[$child] ?? null;
            unset($row[$child]);

            $result[] = $row;
            if (!empty($sub)) {
                $childData = self::treeToLinear($sub, $child, $idName, $parentIdName, $row[$idName]);
                foreach ($childData as $childRow) {
                    $v[$idName] = $i++;
                    $result[] = $childRow;
                }
            }
        }
        return $result;
    }

    /**
     * 多级线性结构排序
     * 转换前：
     * [{"id":1,"pid":0,"name":"a"},{"id":2,"pid":0,"name":"b"},{"id":3,"pid":1,"name":"c"},
     * {"id":4,"pid":2,"name":"d"},{"id":5,"pid":4,"name":"e"},{"id":6,"pid":5,"name":"f"},
     * {"id":7,"pid":3,"name":"g"}]
     * 转换后：
     * [{"id":1,"pid":0,"name":"a","level":1},{"id":3,"pid":1,"name":"c","level":2},{"id":7,"pid":3,"name":"g","level":3},
     * {"id":2,"pid":0,"name":"b","level":1},{"id":4,"pid":2,"name":"d","level":2},{"id":5,"pid":4,"name":"e","level":3},
     * {"id":6,"pid":5,"name":"f","level":4}]
     *
     * @param array $data 线性结构数组
     * @param int $pid 父级开始值
     * @param string $field 父级字段名
     * @param string $pk 主键字段名
     * @param string $html 额外增外前缀符号
     * @param int $level 层级表示(此值请勿给参数)
     * @param bool $clear 是否清空缓存(此值请勿给参数)
     * @return array
     */
    public static function multilevelLinearSort(array $data, int $pid=0, string $field='pid', string $pk='id', string $html='|--', int $level=0, bool $clear=true) :array
    {
        static $list = [];
        if ($clear) $list = [];

        foreach ($data as $key => $value) {
            if ($value[$field] == $pid) {
                $value['html'] = str_repeat($html, $level);
                $list[] = $value;
                unset($data[$key]);
                self::multilevelLinearSort($data, $value[$pk], $field, $pk, $html, $level + 1, false);
            }
        }

        return $list;
    }
}