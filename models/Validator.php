<?php

class Validator {
    public static function validarNome($nome) {
        if (!preg_match('/\s/', $nome)) {
            throw new Exception("Erro: O nome deve conter pelo menos um sobrenome.");
        }
    }

    public static function validarDataNascimento($data_nascimento) {
        if ($data_nascimento >= date('Y-m-d')) {
            throw new Exception("Erro: A data de nascimento deve ser anterior à data atual.");
        }
    }

    public static function validarCPF($cpf) {
        if (!preg_match('/^\d{11}$/', $cpf)) {
            throw new Exception("Erro: CPF inválido! Deve conter exatamente 11 números.");
        }
    }

    public static function validarRG($rg) {
        if (!preg_match('/^\d{7,20}$/', $rg)) {
            throw new Exception("Erro: RG inválido! Deve conter entre 7 e 20 números.");
        }
    }

    public static function validarTelefone($telefone) {
        if (!preg_match('/^\d{10,11}$/', $telefone)) {
            throw new Exception("Erro: Telefone inválido! Deve conter 10 ou 11 números.");
        }
    }
}