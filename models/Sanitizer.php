<?php

class Sanitizer {
    public static function sanitizeString($string) {
        return filter_var(trim($string), FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public static function sanitizeInt($int) {
        return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function sanitizeDate($date) {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) ? $date : null;
    }

    public static function sanitizeCPF($cpf) {
        return preg_match('/^\d{11}$/', $cpf) ? $cpf : null;
    }

    public static function sanitizeRG($rg) {
        return preg_match('/^\d{7,20}$/', $rg) ? $rg : null;
    }

    public static function sanitizeTelefone($telefone) {
        return preg_match('/^\d{10,11}$/', $telefone) ? $telefone : null;
    }
}