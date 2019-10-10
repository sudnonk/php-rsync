<?php


    namespace sudnonk\Rsync\Option;


    class SSHOptionDirectory {
        /**
         * @const OPTIONS
         */
        public const OPTIONS = [
            "p" => true,
            "l" => true,
            "i" => true,
            "C" => false,
            "c" => true,
            "1" => false,
            "2" => false,
            "4" => false,
            "6" => false,
            "K" => false,
            "k" => false,
            "A" => false,
            "a" => false,
            "X" => false,
            "x" => false,
            "Y" => false,
            "f" => false,
            "F" => true,
            "o" => true,
            "E" => true,
            "q" => false,
            "v" => false
        ];

        public static function is_exists(string $option): bool {
            return (in_array($option, array_keys(self::OPTIONS), true));
        }

        public static function is_accept_param(string $option): bool {
            return self::OPTIONS[$option];
        }
    }