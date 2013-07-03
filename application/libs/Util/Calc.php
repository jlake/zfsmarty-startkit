<?php
/**
 * 共通計算 機能
 * @author ou
 *
 */
class Lib_Util_Calc
{
    /**
     * 消費税計算
     *
     * @param   float $origPrice 税抜価格
     * @param   float $taxRate 税率
     * @return  float
     */
    public static function getTax($origPrice, $taxRate = Lib_App_Const::TAX_RATE) {
        return floor($origPrice * $taxRate);
    }
}