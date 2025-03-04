<?php

namespace App\Http\Controllers\Common\excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Excel
{
    public static $letter = ['1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L', '13' => 'M', '14' => 'N', '15' => 'O', '16' => 'P', '17' => 'Q', '18' => 'R', '19' => 'S', '20' => 'T', '21' => 'U', '22' => 'V', '23' => 'W', '24' => 'X', '25' => 'Y', '26' => 'Z', '27' => 'AA', '28' => 'AB', '29' => 'AC', '30' => 'AD', '31' => 'AE', '32' => 'AF', '33' => 'AG', '34' => 'AH', '35' => 'AI', '36' => 'AJ', '37' => 'AK', '38' => 'AL', '39' => 'AM', '40' => 'AN', '41' => 'AO', '42' => 'AP', '43' => 'AQ', '44' => 'AR', '45' => 'AS', '46' => 'AT', '47' => 'AU', '48' => 'AV', '49' => 'AW', '50' => 'AX', '51' => 'AY', '52' => 'AZ', '53' => 'BA'];
    public static $row;
    public static $list;
    public static $sheet;
    public static $spreadsheet;
    public static $writer;
    public static $name;
    public static $format;

    public static function alter_row($row = 1)
    {
        self::$row = $row;
    }

    public static function alter_list($list = 1)
    {
        self::$list = $list;
    }

    public static function init()
    {
        # 实例化 Spreadsheet 对象
        self::$spreadsheet = new Spreadsheet();
        # 获取活动工作薄
        self::$sheet = self::$spreadsheet->getActiveSheet();
        self::alter_row();
        self::alter_list();
    }

    //定义excel文件名
    public static function name($name, $format = 'xls')
    {
        self::writerName($name, $format);
    }

    //数据写入excel文件
    public static function all_setValue($data, $alter = [])
    {

        if (!empty($alter['row'])) {
            self::alter_row($alter['row']);
        }
        if (!empty($alter['list'])) {
            self::alter_list($alter['list']);
        }

        // $value 行     $v 列
        foreach ($data as $key => $value) {
            self::alter_list();
            foreach ($value as $k => $v) {
                if (is_array($v)) {
                    $style = [];
                    if (!empty($v['style'])) {
                        $style = $v['style'];
                        unset($v['style']);
                    }
                    if (!empty($style['newline'])) {
                        if ($style['newline']) {
                            $values = '';
                            foreach ($v as $ks => $vs) {
                                if ($values == '') {
                                    $values .= $vs;
                                } else {
                                    $values = $values . PHP_EOL . $vs;
                                }
                            }
                            self::setValue(self::$letter[self::$list] . self::$row, $values);
                            self::$sheet->getStyle(self::$letter[self::$list] . self::$row)->getAlignment()->setWrapText(true);
                        }
                    }
                    if (!empty($v['value'])) {
                        self::setValue(self::$letter[self::$list] . self::$row, $v['value']);
                    }
                } else {
                    self::setValue(self::$letter[self::$list] . self::$row, $v);
                }

                self::$list++;
            }
            self::$row++;
        }
    }

    //单元格写入数据
    public static function setValue($place, $value)
    {
        self::$sheet->getCell($place)->setValue($value);
    }

    public static function writerName($name, $format = 'xls')
    {
        self::$format = ucfirst(strtolower($format));
        self::format();
        self::$name = $name . "." . self::$format;
        self::$writer->save(self::$name);
    }

    public static function format()
    {
        switch (self::$format) {
            case 'Xls':
                self::$writer = new Xls(self::$spreadsheet);
                break;
            case 'Xlsx':
                self::$writer = new Xlsx(self::$spreadsheet);
                break;
        }
    }

    public static function save($file_name)
    {
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter(self::$spreadsheet, 'Xls');
        $writer->save($file_name);
    }
}
